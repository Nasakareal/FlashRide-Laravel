<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransitRoute;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function publicDashboard()
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
        $usersCount  = User::count();

        $tripsToday = Trip::whereDate('created_at', now()->toDateString())->count();

        $incidentsOpen   = 0;
        $panicAlertsOpen = 0;

        return view('dashboard', compact(
            'vehiclesCount',
            'driversCount',
            'routesCount',
            'usersCount',
            'tripsToday',
            'incidentsOpen',
            'panicAlertsOpen'
        ));
    }
}
