<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.activate',
            'users.export',

            // Vehicles
            'vehicles.view',
            'vehicles.create',
            'vehicles.edit',
            'vehicles.delete',
            'vehicles.assign_driver',

            // Drivers
            'drivers.view',
            'drivers.create',
            'drivers.edit',
            'drivers.delete',

            // Routes
            'routes.view',
            'routes.assign',
            'routes.change',

            // Stats
            'stats.view',
            'stats.export',

            // Settings
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin      = Role::where('name','admin')->first();
        $capturista = Role::where('name','capturista')->first();
        $analista   = Role::where('name','analista')->first();
        $soporte    = Role::where('name','soporte')->first();

        // Admin
        if ($admin) {
            $admin->syncPermissions([
                'users.view','users.create','users.edit','users.activate',

                'vehicles.view','vehicles.create','vehicles.edit','vehicles.delete','vehicles.assign_driver',
                'drivers.view','drivers.create','drivers.edit','drivers.delete',
                'routes.view','routes.assign','routes.change',

                'stats.view','stats.export',
            ]);
        }

        // Capturista
        if ($capturista) {
            $capturista->syncPermissions([
                'vehicles.view','vehicles.create','vehicles.edit','vehicles.assign_driver',
                'drivers.view','drivers.create','drivers.edit',
            ]);
        }

        // Analista
        if ($analista) {
            $analista->syncPermissions([
                'stats.view','stats.export',
                'vehicles.view','drivers.view','routes.view',
            ]);
        }

        // Soporte
        if ($soporte) {
            $soporte->syncPermissions([
                'users.view',
                'vehicles.view',
                'drivers.view',
                'routes.view',
            ]);
        }
    }
}
