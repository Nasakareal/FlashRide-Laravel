<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Listar todos los vehículos.
     */
    public function index()
    {
        return response()->json(Vehicle::all());
    }

    /**
     * Registrar un nuevo vehículo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles,plate',
            'model' => 'required|string|max:100',
            'color' => 'nullable|string|max:50',
        ]);

        $vehicle = Vehicle::create($validated);

        return response()->json([
            'message' => 'Vehículo registrado correctamente.',
            'data' => $vehicle
        ], 201);
    }
}
