<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDriversSeeder extends Seeder
{
    public function run(): void
    {
        $n = 40;
        for ($i=1; $i<=$n; $i++) {
            DB::table('users')->insert([
                'name' => "Demo Driver $i",
                'email' => "demo_driver_$i@flashride.com",
                'phone' => "443000".str_pad($i, 4, '0', STR_PAD_LEFT),
                'rating' => 5.0,
                'ever_pressed_panic' => 0,
                'role' => 'driver',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'lat' => 19.62 + lcg_value() * (19.80 - 19.62),
                'lng' => -101.30 + lcg_value() * (-101.05 + 101.30),
                'is_online' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
