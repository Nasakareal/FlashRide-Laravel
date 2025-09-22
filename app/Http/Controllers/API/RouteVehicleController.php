<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\RouteVehicleAssignment;

class RouteVehicleController extends Controller
{
    public function list($routeId)
    {
        $asigs = RouteVehicleAssignment::where('route_id', $routeId)
            ->where('active', 1)->whereNull('ended_at')
            ->with(['vehicle.activeDriverAssignment.driver:id,name,phone'])
            ->get();

        return response()->json($asigs, 200);
    }

    public function availableVehicles()
    {
        $vehicles = Vehicle::whereDoesntHave('activeRouteAssignment')
            ->with(['activeDriverAssignment.driver:id,name,phone'])
            ->get();

        return response()->json($vehicles, 200);
    }

    public function assign(Request $request, $routeId)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'notes'      => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($routeId, $data) {
            RouteVehicleAssignment::where('vehicle_id', $data['vehicle_id'])
                ->where('active', 1)->whereNull('ended_at')
                ->update(['active' => 0, 'ended_at' => now()]);

            $asig = RouteVehicleAssignment::create([
                'route_id'   => (int)$routeId,
                'vehicle_id' => (int)$data['vehicle_id'],
                'started_at' => now(),
                'active'     => 1,
                'notes'      => $data['notes'] ?? null,
            ]);

            return response()->json($asig->load('vehicle'), 201);
        });
    }

    public function unassign(Request $request, $routeId)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $asig = RouteVehicleAssignment::where('route_id', $routeId)
            ->where('vehicle_id', $data['vehicle_id'])
            ->where('active', 1)->whereNull('ended_at')
            ->first();

        if (!$asig) {
            return response()->json(['message' => 'El vehículo no tiene asignación activa en esta ruta.'], 422);
        }

        $asig->update(['active' => 0, 'ended_at' => now()]);

        return response()->json(['message' => 'Asignación de ruta cerrada.'], 200);
    }
}
