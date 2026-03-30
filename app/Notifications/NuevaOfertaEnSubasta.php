<?php

namespace App\Notifications;

use App\Models\Subasta;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NuevaOfertaEnSubasta extends Notification
{
    use Queueable;

    public function __construct(
        public Subasta $subasta,
        public string $montoFormateado,
        public string $nombreOferente
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'subasta_id' => $this->subasta->id,
            'titulo' => $this->subasta->titulo_subasta,
            'nombre_producto' => $this->subasta->nombre_producto,
            'monto' => $this->montoFormateado,
            'oferente' => $this->nombreOferente,
            'mensaje' => "{$this->nombreOferente} subió la puja a {$this->montoFormateado} Bs en «{$this->subasta->nombre_producto}».",
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
