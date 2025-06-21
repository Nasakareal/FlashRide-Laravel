<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ride;
use App\Models\User;


class RideController extends Controller
{
     // Obtener todos los viajes del usuario (pasajero o conductor).
    public function index()
    {
        $userId = Auth::id();

        $rides = Ride::where('passenger_id', $userId)
                     ->orWhere('driver_id', $userId)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return response()->json($rides);
    }

     // Solicitar un nuevo viaje (pasajero).
    public function store(Request $request)
    {
        $request->validate([
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat'   => 'required|numeric',
            'end_lng'   => 'required|numeric',
        ]);

        $ride = Ride::create([
            'passenger_id'   => Auth::id(),
            'start_lat'      => $request->start_lat,
            'start_lng'      => $request->start_lng,
            'end_lat'        => $request->end_lat,
            'end_lng'        => $request->end_lng,
            'estimated_cost' => 80,           // futuro calcular dinámicamente
            'status'         => 'pending',
        ]);

        return response()->json($ride, 201);
    }

     // Ver detalle de un viaje.
    public function show($id)
    {
        $ride = Ride::find($id);

        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }

        return response()->json($ride);
    }

     // Listar viajes pendientes (solo conductores).
    public function pendingRides()
    {
        $user = Auth::user();
        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Solo los conductores pueden ver viajes pendientes.'], 403);
        }

        $rides = Ride::where('status', 'pending')->get();

        return response()->json([
            'message' => 'Viajes pendientes disponibles',
            'data'    => $rides,
        ]);
    }

     // Aceptar un viaje (solo conductor).
    public function accept($id)
    {
        $user = Auth::user();
        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Solo los conductores pueden aceptar viajes.'], 403);
        }

        $ride = Ride::find($id);
        if (! $ride || $ride->status !== 'pending') {
            return response()->json(['message' => 'Viaje no disponible para aceptar.'], 400);
        }

        $ride->driver_id = $user->id;
        $ride->status    = 'accepted';
        $ride->save();

        return response()->json([
            'message' => 'Viaje aceptado.',
            'data'    => $ride,
        ]);
    }

     // Completar un viaje (solo conductor asignado).
    public function complete($id)
    {
        $user = Auth::user();
        $ride = Ride::find($id);

        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado.'], 404);
        }

        if ($user->role !== 'driver' || $ride->driver_id !== $user->id) {
            return response()->json(['message' => 'No tienes permiso para completar este viaje.'], 403);
        }

        if (! in_array($ride->status, ['accepted', 'in_progress'])) {
            return response()->json(['message' => 'El viaje no está en curso para completarlo.'], 400);
        }

        $ride->status = 'completed';
        $ride->save();

        return response()->json([
            'message' => 'Viaje completado.',
            'data'    => $ride,
        ]);
    }

     // Actualizar ubicación del conductor en tiempo real.
    public function updateLocation(Request $request)
    {
        $request->validate([
            'ride_id'     => 'required|exists:rides,id',
            'driver_lat'  => 'required|numeric',
            'driver_lng'  => 'required|numeric',
        ]);

        $ride = Ride::findOrFail($request->ride_id);
        $user = Auth::user();

        if ($user->role !== 'driver' || $ride->driver_id !== $user->id) {
            return response()->json(['message' => 'No tienes permiso para actualizar esta ubicación.'], 403);
        }

        $ride->driver_lat = $request->driver_lat;
        $ride->driver_lng = $request->driver_lng;
        $ride->save();

        return response()->json([
            'message' => 'Ubicación del conductor actualizada.',
            'data'    => $ride,
        ]);
    }

     // Actualizar estado o datos del viaje (genérico).
    public function update(Request $request, $id)
    {
        $ride = Ride::findOrFail($id);
        $user = Auth::user();

        // Solo conductor puede gestionar cambios de estado libres
        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Solo los conductores pueden gestionar viajes.'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,in_progress,completed,cancelled',
        ]);

        // Si está aceptando por PUT y aún no tiene conductor, lo asigna
        if ($ride->driver_id === null && $request->status === 'accepted') {
            $ride->driver_id = $user->id;
        }

        $ride->status = $request->status;
        $ride->save();

        return response()->json([
            'message' => 'Viaje actualizado.',
            'data'    => $ride,
        ]);
    }

    public function nearbyDrivers()
    {
        try {
            return User::where('role', 'driver')
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->get(['id', 'name', 'lat', 'lng']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateGlobalLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $user = Auth::user();

        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Solo los conductores pueden actualizar ubicación.'], 403);
        }

        $user->lat = $request->lat;
        $user->lng = $request->lng;
        $user->save();

        return response()->json(['message' => 'Ubicación global actualizada']);
    }
}
