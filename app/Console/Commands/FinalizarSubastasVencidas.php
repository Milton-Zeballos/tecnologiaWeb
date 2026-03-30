<?php

namespace App\Console\Commands;

use App\Services\SubastaFinalizacionService;
use Illuminate\Console\Command;

class FinalizarSubastasVencidas extends Command
{
    protected $signature = 'subastas:finalizar';

    protected $description = 'Cierra subastas vencidas y define ganador si corresponde';

    public function handle(SubastaFinalizacionService $servicio): int
    {
        $n = $servicio->finalizarVencidas();
        $this->info("Subastas cerradas: {$n}");

        return self::SUCCESS;
    }
}
