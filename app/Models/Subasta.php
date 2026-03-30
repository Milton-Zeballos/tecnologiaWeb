<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Subasta extends Model
{
    protected $fillable = [
        'user_id',
        'titulo_subasta',
        'nombre_producto',
        'descripcion',
        'media_path',
        'media_type',
        'precio_inicial',
        'precio_minimo',
        'empieza_en',
        'duracion_minutos',
        'extension_por_oferta_minutos',
        'termina_en',
        'finalizada',
        'cerrada_en',
        'ganador_user_id',
        'ultima_oferta_monto',
    ];

    protected function casts(): array
    {
        return [
            'precio_inicial' => 'decimal:2',
            'precio_minimo' => 'decimal:2',
            'ultima_oferta_monto' => 'decimal:2',
            'empieza_en' => 'datetime',
            'termina_en' => 'datetime',
            'cerrada_en' => 'datetime',
            'finalizada' => 'boolean',
        ];
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ganador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ganador_user_id');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class);
    }

    public function precioActual(): string
    {
        if ($this->ultima_oferta_monto !== null) {
            return (string) $this->ultima_oferta_monto;
        }

        return (string) $this->precio_inicial;
    }

    public function enVentanaDeSubasta(Carbon $ahora): bool
    {
        if ($this->finalizada) {
            return false;
        }

        return $ahora->greaterThanOrEqualTo($this->empieza_en)
            && $ahora->lessThan($this->termina_en);
    }

    public function puedeOfertarUsuario(?int $userId): bool
    {
        if ($userId === null || $this->finalizada) {
            return false;
        }

        if ((int) $this->user_id === (int) $userId) {
            return false;
        }

        return $this->enVentanaDeSubasta(now());
    }

    public function urlMedia(): ?string
    {
        if (! $this->media_path) {
            return null;
        }

        return asset('storage/'.$this->media_path);
    }

    public function esVideo(): bool
    {
        return $this->media_type === 'video';
    }
}
