<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\User;
use Carbon\Carbon;


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

        // 1. Calcular distancia (en km) con fórmula de Haversine
        $distanceKm = $this->calculateDistance(
            $request->start_lat, $request->start_lng,
            $request->end_lat, $request->end_lng
        );

        // 2. Base de costo
        $costPerKm = 10;

        // 3. Ver si es horario nocturno
        $hour = now()->hour;
        $isNight = $hour >= 22 || $hour < 6;

        // 4. Ver si hay pocos conductores online
        $onlineDrivers = User::where('role', 'driver')->where('is_online', true)->count();
        $isHighDemand = $onlineDrivers < 3;

        // 5. Calcular costo final
        $estimatedCost = $distanceKm * $costPerKm;

        if ($isNight) {
            $estimatedCost *= 1.25; // 🕙 25% más caro de noche
        }

        if ($isHighDemand) {
            $estimatedCost *= 1.10; // 📈 10% más si hay poca oferta
        }

        $estimatedCost = round($estimatedCost, 2); // Redondear a 2 decimales

        // 6. Crear el viaje
        $ride = Ride::create([
            'passenger_id'   => Auth::id(),
            'start_lat'      => $request->start_lat,
            'start_lng'      => $request->start_lng,
            'end_lat'        => $request->end_lat,
            'end_lng'        => $request->end_lng,
            'estimated_cost' => $estimatedCost,
            'status'         => 'pending',
            'fase'           => 'esperando',
        ]);

        return response()->json($ride, 201);
    }

    // Método privado para calcular costo Haversine
    public function estimateCost(Request $request)
    {
        $request->validate([
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat'   => 'required|numeric',
            'end_lng'   => 'required|numeric',
        ]);

        $distanceKm = $this->calculateDistance(
            $request->start_lat, $request->start_lng,
            $request->end_lat, $request->end_lng
        );

        $costPerKm = 10;
        $hour = now()->hour;
        $isNight = $hour >= 22 || $hour < 6;

        $onlineDrivers = User::where('role', 'driver')->where('is_online', true)->count();
        $isHighDemand = $onlineDrivers < 3;

        $estimatedCost = $distanceKm * $costPerKm;

        if ($isNight) {
            $estimatedCost *= 1.25;
        }

        if ($isHighDemand) {
            $estimatedCost *= 1.10;
        }

        return response()->json([
            'estimated_cost' => round($estimatedCost, 2),
            'distance_km'    => round($distanceKm, 2),
            'night'          => $isNight,
            'high_demand'    => $isHighDemand,
        ]);
    }

    // Método privado para calcular distancia con Haversine
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
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
        try {
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
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar ubicación',
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()[0]
            ], 500);
        }
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
            $conductores = User::where('role', 'driver')
                ->where('is_online', true)
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->get(['id', 'name', 'lat', 'lng', 'is_online']);

            return response()->json($conductores);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error interno',
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()[0]
            ], 500);
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

    public function updateFase(Request $request, $id)
    {
        $ride = Ride::find($id);
        $user = Auth::user();

        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }

        if ($user->role !== 'driver' || $ride->driver_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'fase' => 'required|in:esperando,recogiendo,viajando,completado',
        ]);

        $ride->fase = $request->fase;
        $ride->save();

        return response()->json([
            'message' => 'Fase del viaje actualizada.',
            'data' => $ride,
        ]);
    }
}
