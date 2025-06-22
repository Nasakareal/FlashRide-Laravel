<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CleanInactiveDrivers extends Command
{
    protected $signature = 'clean:inactive-drivers';

    protected $description = 'Marca como inactivos a conductores y limpia su ubicación si llevan 5 minutos sin actualizarse.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $inactivos = User::where('role', 'driver')
            ->where('is_online', true)
            ->where('updated_at', '<', now()->subMinutes(5))
            ->get();

        foreach ($inactivos as $user) {
            $user->is_online = false;
            $user->lat = null;
            $user->lng = null;
            $user->save();
        }

        $this->info("Se marcaron " . count($inactivos) . " conductores como inactivos y se limpió su ubicación.");
    }
}
