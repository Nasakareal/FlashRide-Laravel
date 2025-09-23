<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DemoMoveCars extends Command
{
    protected $signature = 'demo:move-cars';
    protected $description = 'Mueve ligeramente a los conductores demo dentro del bbox de Morelia';

    public function handle(): int
    {
        $latMin = 19.62; $latMax = 19.80;
        $lngMin = -101.30; $lngMax = -101.05;
        $step = 0.004;

        DB::table('users')
            ->where('role', 'driver')
            ->where('email', 'like', 'demo_driver_%')
            ->where('is_online', 1)
            ->update([
                'lat' => DB::raw("LEAST($latMax, GREATEST($latMin, lat + (RAND()*$step - $step/2)))"),
                'lng' => DB::raw("LEAST($lngMax, GREATEST($lngMin, lng + (RAND()*$step - $step/2)))"),
                'updated_at' => now(),
            ]);

        $this->info('Carritos movidos');
        return Command::SUCCESS;
    }
}
