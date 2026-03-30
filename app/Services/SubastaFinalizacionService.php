<?php

namespace App\Services;

use App\Models\Oferta;
use App\Models\Subasta;
use Illuminate\Support\Carbon;

class SubastaFinalizacionService
{
    public function finalizarSiCorresponde(Subasta $subasta, ?Carbon $ahora = null): bool
    {
        $ahora ??= now();

        if ($subasta->finalizada) {
            return false;
        }

        if ($ahora->lessThan($subasta->termina_en)) {
            return false;
        }

        $subasta->loadMissing('ofertas');

        $mejor = Oferta::query()
            ->where('subasta_id', $subasta->id)
            ->orderByDesc('monto')
            ->orderByDesc('created_at')
            ->first();

        $ganadorId = null;
        if ($mejor !== null && (float) $mejor->monto >= (float) $subasta->precio_minimo) {
            $ganadorId = (int) $mejor->user_id;
        }

        $subasta->update([
            'finalizada' => true,
            'cerrada_en' => $ahora,
            'ganador_user_id' => $ganadorId,
        ]);

        return true;
    }

    public function finalizarVencidas(?Carbon $ahora = null): int
    {
        $ahora ??= now();
        $ids = Subasta::query()
            ->where('finalizada', false)
            ->where('termina_en', '<', $ahora)
            ->pluck('id');

        $n = 0;
        foreach ($ids as $id) {
            $s = Subasta::find($id);
            if ($s && $this->finalizarSiCorresponde($s, $ahora)) {
                $n++;
            }
        }

        return $n;
    }
}
