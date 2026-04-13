<?php

namespace App\Console\Commands;

use App\Models\Ride;
use Illuminate\Console\Command;

class ExpirePendingRides extends Command
{
    protected $signature = 'rides:expire-pending';

    protected $description = 'Cancela automáticamente solicitudes de viaje pendientes vencidas.';

    public function handle()
    {
        $expiredRides = Ride::query()
            ->where('status', 'pending')
            ->where('created_at', '<', Ride::pendingCutoff())
            ->get();

        foreach ($expiredRides as $ride) {
            $ride->markAsCancelled();
            $ride->save();
        }

        $this->info('Solicitudes vencidas canceladas: ' . $expiredRides->count());

        return 0;
    }
}
