<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureDriverHasActiveVehicle
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $isDriver = false;

        try {
            $isDriver = $user->hasRole('driver');
        } catch (\Throwable $e) {
            $isDriver = (($user->role ?? null) === 'driver');
        }

        if (! $isDriver) {
            return $next($request);
        }

        $driver = $user->driverProfile;

        if (! $driver) {
            return response()->json([
                'ok' => false,
                'code' => 'DRIVER_PROFILE_MISSING',
                'message' => 'Tu perfil de conductor no está completo. Contacta a administración.',
            ], 403);
        }

        $active = $user->activeDriverVehicleAssignment();

        if (! $active || ! $active->vehicle) {
            return response()->json([
                'ok' => false,
                'code' => 'DRIVER_NO_VEHICLE',
                'message' => 'No tienes vehículo asignado. Contacta a administración.',
            ], 403);
        }

        $request->attributes->set('active_vehicle', $active->vehicle);
        $request->attributes->set('active_vehicle_assignment', $active);

        return $next($request);
    }
}
