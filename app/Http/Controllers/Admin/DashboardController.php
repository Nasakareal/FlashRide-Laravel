<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarjetaTeleferico;
use App\Models\TransitRoute;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    private function getDashboardData(): array
    {
        $vehiclesCount = Vehicle::count();

        $rolesDb = Role::query()->pluck('name')->all();

        if (in_array('driver', $rolesDb, true)) {
            $driversCount = User::query()->role('driver')->count();
        } else {
            $driversCount = Schema::hasColumn('users', 'role')
                ? User::query()->where('role', 'driver')->count()
                : 0;
        }

        $routesCount = TransitRoute::count();
        $usersCount = User::count();
        $tarjetasTelefericoCount = TarjetaTeleferico::count();

        $tripsToday = Trip::whereDate('created_at', now()->toDateString())->count();

        $incidentsOpen = 0;
        $panicAlertsOpen = 0;
        $ticketsOpenCount = 0;

        return compact(
            'vehiclesCount',
            'driversCount',
            'routesCount',
            'usersCount',
            'tarjetasTelefericoCount',
            'tripsToday',
            'incidentsOpen',
            'panicAlertsOpen',
            'ticketsOpenCount'
        );
    }

    public function index()
    {
        return view('admin.dashboard', $this->getDashboardData());
    }

    public function publicDashboard()
    {
        return view('dashboard', $this->getDashboardData());
    }
}
