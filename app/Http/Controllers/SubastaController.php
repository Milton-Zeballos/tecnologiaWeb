<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Subasta;
use App\Models\User;
use App\Notifications\NuevaOfertaEnSubasta;
use App\Services\SubastaFinalizacionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubastaController extends Controller
{
    public function __construct(
        private SubastaFinalizacionService $finalizacion
    ) {}

    public function index(Request $request): View
    {
        $this->finalizacion->finalizarVencidas();

        $request->validate([
            'q' => 'nullable|string|max:200',
            'orden' => 'nullable|in:termina,precio,nombre',
        ]);

        $query = Subasta::query()->with('vendedor');

        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($q) use ($term) {
                $q->where('nombre_producto', 'like', '%'.$term.'%')
                    ->orWhere('titulo_subasta', 'like', '%'.$term.'%')
                    ->orWhere('descripcion', 'like', '%'.$term.'%');
            });
        }

        $orden = $request->input('orden', 'termina');
        match ($orden) {
            'precio' => $query
                ->orderByRaw('COALESCE(ultima_oferta_monto, precio_inicial) DESC')
                ->orderBy('termina_en'),
            'nombre' => $query->orderBy('nombre_producto')->orderBy('termina_en'),
            default => $query->orderBy('termina_en'),
        };

        $subastas = $query->get();

        return view('subastas.index', [
            'subastas' => $subastas,
            'orden' => $orden,
        ]);
    }

    public function create(): View
    {
        return view('subastas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titulo_subasta' => 'required|string|max:255',
            'nombre_producto' => 'required|string|max:255',
            'descripcion' => 'required|string|max:5000',
            'media' => 'required|file|mimes:jpg,jpeg,png,webp,gif,mp4,webm|max:51200',
            'precio_inicial' => 'required|numeric|min:0',
            'precio_minimo' => 'required|numeric|min:0',
            'empieza_en' => 'required|date',
            'duracion_minutos' => 'required|integer|min:1|max:43200',
            'extension_por_oferta_minutos' => 'required|integer|min:0|max:1440',
        ]);

        $path = $request->file('media')->store('subastas', 'public');
        $mime = $request->file('media')->getMimeType();
        $mediaType = str_starts_with((string) $mime, 'video/') ? 'video' : 'image';

        $empieza = Carbon::parse($data['empieza_en']);

        // Si la fecha/hora de inicio ya pasó, arranca desde ahora (evita cierres “en el pasado” por defectos del navegador).
        $ahora = now();
        if ($empieza->lessThan($ahora)) {
            $empieza = $ahora->copy();
        }

        $termina = $empieza->copy()->addMinutes((int) $data['duracion_minutos']);

        Subasta::create([
            'user_id' => Auth::id(),
            'titulo_subasta' => $data['titulo_subasta'],
            'nombre_producto' => $data['nombre_producto'],
            'descripcion' => $data['descripcion'],
            'media_path' => $path,
            'media_type' => $mediaType,
            'precio_inicial' => $data['precio_inicial'],
            'precio_minimo' => $data['precio_minimo'],
            'empieza_en' => $empieza,
            'duracion_minutos' => $data['duracion_minutos'],
            'extension_por_oferta_minutos' => $data['extension_por_oferta_minutos'],
            'termina_en' => $termina,
        ]);

        return redirect()->route('subastas.index')->with('success', 'Listo, ya está publicada.');
    }

    public function show(string $id): View|RedirectResponse
    {
        $subasta = Subasta::with(['vendedor', 'ganador'])->findOrFail((int) $id);
        $this->finalizacion->finalizarSiCorresponde($subasta);
        $subasta->refresh();

        $demoSinBilletera = config('ganatelo.demo_sin_billetera', true);

        return view('subastas.show', [
            'subasta' => $subasta,
            'demoSinBilletera' => $demoSinBilletera,
        ]);
    }

    public function pujar(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'incremento' => 'required|in:10,50,100',
        ]);

        $incremento = (int) $request->input('incremento');
        $subastaId = (int) $id;
        $userId = (int) Auth::id();

        try {
            $montoNuevo = DB::transaction(function () use ($subastaId, $userId, $incremento) {
                $subasta = Subasta::lockForUpdate()->findOrFail($subastaId);
                $this->finalizacion->finalizarSiCorresponde($subasta);
                $subasta->refresh();

                if (! $subasta->puedeOfertarUsuario($userId)) {
                    throw new \RuntimeException('PUJA_NO_PERMITIDA');
                }

                $montoEsperado = (float) $subasta->precioActual() + $incremento;

                Oferta::create([
                    'subasta_id' => $subasta->id,
                    'user_id' => $userId,
                    'monto' => $montoEsperado,
                    'incremento_bs' => $incremento,
                ]);

                $subasta->update([
                    'ultima_oferta_monto' => $montoEsperado,
                    'termina_en' => $subasta->termina_en->copy()->addMinutes((int) $subasta->extension_por_oferta_minutos),
                ]);

                return $montoEsperado;
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'PUJA_NO_PERMITIDA') {
                return back()->withErrors(['puja' => 'No se puede pujar en este momento.']);
            }
            throw $e;
        }

        $subasta = Subasta::findOrFail($subastaId);
        $oferente = Auth::user();
        $montoTxt = number_format((float) $montoNuevo, 2, ',', '.');

        $avisarIds = Oferta::query()
            ->where('subasta_id', $subasta->id)
            ->where('user_id', '!=', $userId)
            ->where('user_id', '!=', $subasta->user_id)
            ->distinct()
            ->pluck('user_id');

        foreach ($avisarIds as $uid) {
            $u = User::find($uid);
            if ($u) {
                $u->notify(new NuevaOfertaEnSubasta(
                    $subasta,
                    $montoTxt,
                    $oferente->name
                ));
            }
        }

        return back()->with('success', 'Puja de +'.$incremento.' Bs registrada. +'.$subasta->extension_por_oferta_minutos.' min al cierre.');
    }
}
