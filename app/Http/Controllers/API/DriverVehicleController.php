<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\DriverVehicleAssignment;
use Illuminate\Validation\ValidationException;

class DriverVehicleController extends Controller
{
    public function available(Request $request)
    {
        $q = Vehicle::query()
            ->whereDoesntHave('activeDriverAssignment');

        if ($request->filled('type')) {
            $q->where('type', $request->input('type'));
        }
        if ($request->filled('model')) {
            $q->where('model', 'LIKE', '%'.$request->input('model').'%');
        }
        if ($request->filled('color')) {
            $q->where('color', 'LIKE', '%'.$request->input('color').'%');
        }

        $vehicles = $q->orderBy('id', 'desc')->get();

        return response()->json($vehicles, 200);
    }

    public function current($driverId)
    {
        $driver = User::where('role', 'driver')->findOrFail($driverId);

        $asig = $driver->activeVehicleAssignment()
            ->with(['vehicle'])
            ->first();

        return response()->json($asig, 200);
    }

    public function assign(Request $request, $driverId)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'notes'      => 'nullable|string|max:255',
        ]);

        $driver  = User::where('role', 'driver')->findOrFail($driverId);
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);


        return DB::transaction(function () use ($driver, $vehicle, $data) {

            DriverVehicleAssignment::where('driver_id', $driver->id)
                ->where('active', 1)
                ->whereNull('ended_at')
                ->update(['active' => 0, 'ended_at' => now()]);

            DriverVehicleAssignment::where('vehicle_id', $vehicle->id)
                ->where('active', 1)
                ->whereNull('ended_at')
                ->update(['active' => 0, 'ended_at' => now()]);

            $asig = DriverVehicleAssignment::create([
                'driver_id'  => $driver->id,
                'vehicle_id' => $vehicle->id,
                'started_at' => now(),
                'ended_at'   => null,
                'active'     => 1,
                'notes'      => $data['notes'] ?? null,
            ]);

            return response()->json(
                $asig->load(['driver:id,name,phone','vehicle']),
                201
            );
        });
    }

    public function unassign($driverId)
    {
        $driver = User::where('role', 'driver')->findOrFail($driverId);

        $asig = $driver->activeVehicleAssignment()->first();

        if (!$asig) {
            throw ValidationException::withMessages([
                'assignment' => 'El conductor no tiene asignación activa.',
            ]);
        }

        $asig->update([
            'active'   => 0,
            'ended_at' => now(),
        ]);

        return response()->json(['message' => 'Asignación cerrada.'], 200);
    }
}
