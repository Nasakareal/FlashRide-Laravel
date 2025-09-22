<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index()
    {
        if (Schema::hasTable('driver_vehicle_assignments')) {
            $vehicles = Vehicle::with(['activeDriverAssignment.driver:id,name,phone'])->get();
        } else {
            $vehicles = Vehicle::all();
        }
        return response()->json($vehicles, 200);
    }

    public function store(Request $request)
    {
        $incomingPlate = $request->input('plate_number', $request->input('plate'));
        $request->merge(['plate_number' => $incomingPlate]);

        $data = $request->validate([
            'plate_number'  => 'required|string|max:191|unique:vehicles,plate_number',
            'brand'         => 'required|string|max:191',
            'model'         => 'required|string|max:191',
            'color'         => 'required|string|max:191',
            'owner_user_id' => 'nullable|integer|exists:users,id',
        ]);

        $vehicle = Vehicle::create([
            'user_id'          => $data['owner_user_id'] ?? auth()->id(),
            'vehicle_type'     => 'combi',
            'transit_route_id' => null,
            'last_lat'         => null,
            'last_lng'         => null,
            'last_bearing'     => null,
            'last_speed_kph'   => null,
            'last_located_at'  => null,
            'brand'            => $data['brand'],
            'model'            => $data['model'],
            'color'            => $data['color'],
            'plate_number'     => $data['plate_number'],
        ]);

        return response()->json(['message' => 'Vehículo registrado correctamente.', 'data' => $vehicle], 201);
    }
}
