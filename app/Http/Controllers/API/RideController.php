<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\User;

class RideController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $rides = Ride::where('passenger_id', $userId)
            ->orWhere('driver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($rides);
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat'   => 'required|numeric',
            'end_lng'   => 'required|numeric',
        ]);

        // 1) Distancia (km) Haversine
        $distanceKm = $this->calculateDistance(
            $request->start_lat,
            $request->start_lng,
            $request->end_lat,
            $request->end_lng
        );

        // 2) Base costo
        $costPerKm = 10;

        // 3) Nocturno
        $hour = now()->hour;
        $isNight = $hour >= 22 || $hour < 6;

        // 4) Alta demanda (pocos drivers online)
        $onlineDrivers = User::where('role', 'driver')->where('is_online', true)->count();
        $isHighDemand = $onlineDrivers < 3;

        // 5) Costo final
        $estimatedCost = $distanceKm * $costPerKm;
        if ($isNight)     $estimatedCost *= 1.25;
        if ($isHighDemand) $estimatedCost *= 1.10;

        $estimatedCost = round($estimatedCost, 2);

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

    public function estimateCost(Request $request)
    {
        $request->validate([
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat'   => 'required|numeric',
            'end_lng'   => 'required|numeric',
        ]);

        $distanceKm = $this->calculateDistance(
            $request->start_lat,
            $request->start_lng,
            $request->end_lat,
            $request->end_lng
        );

        $costPerKm = 10;
        $hour = now()->hour;
        $isNight = $hour >= 22 || $hour < 6;

        $onlineDrivers = User::where('role', 'driver')->where('is_online', true)->count();
        $isHighDemand = $onlineDrivers < 3;

        $estimatedCost = $distanceKm * $costPerKm;
        if ($isNight)     $estimatedCost *= 1.25;
        if ($isHighDemand) $estimatedCost *= 1.10;

        return response()->json([
            'estimated_cost' => round($estimatedCost, 2),
            'distance_km'    => round($distanceKm, 2),
            'night'          => $isNight,
            'high_demand'    => $isHighDemand,
        ]);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function show($id)
    {
        $user = Auth::user();

        $ride = Ride::query()->with(['driver', 'passenger'])->find($id);

        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }

        $isPassenger = (int)$ride->passenger_id === (int)$user->id;
        $isDriver    = $ride->driver_id && (int)$ride->driver_id === (int)$user->id;
        $isAdmin     = ($user->role ?? null) === 'admin';

        if (! $isPassenger && ! $isDriver && ! $isAdmin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $payload = $ride->toArray();

        $driver = null;
        if ($ride->driver_id) {
            $driver = User::query()
                ->where('id', $ride->driver_id)
                ->first(['id', 'name', 'phone', 'rating', 'lat', 'lng', 'is_online', 'updated_at']);
        }

        $driverLat = $ride->driver_lat;
        $driverLng = $ride->driver_lng;

        if ((empty($driverLat) || empty($driverLng)) && $driver) {
            $driverLat = $driver->lat;
            $driverLng = $driver->lng;
        }

        $payload['driver'] = $driver ? $driver->toArray() : null;

        $payload['driver_lat'] = $driverLat;
        $payload['driver_lng'] = $driverLng;

        $payload['driver_location'] = ($driverLat !== null && $driverLng !== null)
            ? ['lat' => (float)$driverLat, 'lng' => (float)$driverLng]
            : null;

        return response()->json($payload);
    }

    public function driverLocation($id)
    {
        $user = Auth::user();

        $ride = Ride::find($id);
        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }

        $isPassenger = (int)$ride->passenger_id === (int)$user->id;
        $isDriver    = $ride->driver_id && (int)$ride->driver_id === (int)$user->id;
        $isAdmin     = ($user->role ?? null) === 'admin';

        if (! $isPassenger && ! $isDriver && ! $isAdmin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        if (! $ride->driver_id) {
            return response()->json(['message' => 'Aún sin conductor asignado'], 409);
        }

        $lat = $ride->driver_lat;
        $lng = $ride->driver_lng;

        if (($lat === null || $lng === null)) {
            $driver = User::where('id', $ride->driver_id)->first(['lat', 'lng']);
            $lat = $driver?->lat;
            $lng = $driver?->lng;
        }

        if ($lat === null || $lng === null) {
            return response()->json(['message' => 'Ubicación no disponible'], 404);
        }

        return response()->json([
            'lat' => (float)$lat,
            'lng' => (float)$lng,
        ]);
    }

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

    public function accept(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Solo los conductores pueden aceptar viajes.'], 403);
        }

        $activeVehicle = $request->attributes->get('active_vehicle');
        $activeAssign  = $request->attributes->get('active_vehicle_assignment');

        if (! $activeVehicle || ! $activeAssign) {
            return response()->json([
                'ok' => false,
                'code' => 'DRIVER_NO_VEHICLE',
                'message' => 'No tienes vehículo asignado. Contacta a administración.',
            ], 403);
        }

        return DB::transaction(function () use ($id, $user) {
            $ride = Ride::where('id', $id)->lockForUpdate()->first();

            if (! $ride) {
                return response()->json(['message' => 'Viaje no encontrado.'], 404);
            }

            if ($ride->status !== 'pending' || $ride->driver_id) {
                return response()->json(['message' => 'Ya no disponible'], 409);
            }

            $ride->driver_id = $user->id;
            $ride->status    = 'accepted';
            $ride->fase      = 'recogiendo';

            if ($user->lat !== null && $user->lng !== null) {
                $ride->driver_lat = $user->lat;
                $ride->driver_lng = $user->lng;
            }

            $ride->save();

            return response()->json([
                'message' => 'Viaje aceptado.',
                'data'    => $ride->fresh()
            ], 200);
        });
    }

    public function complete($id)
    {
        $user = Auth::user();
        $ride = Ride::find($id);

        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado.'], 404);
        }

        if ($user->role !== 'driver' || (int)$ride->driver_id !== (int)$user->id) {
            return response()->json(['message' => 'No tienes permiso para completar este viaje.'], 403);
        }

        if (! in_array($ride->status, ['accepted', 'in_progress'], true)) {
            return response()->json(['message' => 'El viaje no está en curso para completarlo.'], 400);
        }

        $ride->status = 'completed';
        $ride->fase   = 'completado';
        $ride->save();

        return response()->json([
            'message' => 'Viaje completado.',
            'data'    => $ride,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'ride_id'     => 'required|exists:rides,id',
            'driver_lat'  => 'required|numeric',
            'driver_lng'  => 'required|numeric',
        ]);

        $ride = Ride::findOrFail($request->ride_id);
        $user = Auth::user();

        if ($user->role !== 'driver' || (int)$ride->driver_id !== (int)$user->id) {
            return response()->json(['message' => 'No tienes permiso para actualizar esta ubicación.'], 403);
        }

        $ride->driver_lat = $request->driver_lat;
        $ride->driver_lng = $request->driver_lng;
        $ride->save();

        DB::table('users')->where('id', $user->id)->update([
            'lat'        => $request->driver_lat,
            'lng'        => $request->driver_lng,
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Ubicación del conductor actualizada.',
            'data'    => $ride,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ride = Ride::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Solo los conductores pueden gestionar viajes.'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,in_progress,completed,cancelled',
        ]);

        if ($ride->driver_id === null && $request->status === 'accepted') {
            $ride->driver_id = $user->id;
        }

        $ride->status = $request->status;

        switch ($ride->status) {
            case 'pending':
                $ride->fase = 'esperando';
                break;
            case 'accepted':
                $ride->fase = 'recogiendo';
                break;
            case 'in_progress':
                $ride->fase = 'viajando';
                break;
            case 'completed':
                $ride->fase = 'completado';
                break;
            case 'cancelled':
            default:
                break;
        }

        $ride->save();

        return response()->json(['message' => 'Viaje actualizado.', 'data' => $ride]);
    }

    public function nearbyDrivers()
    {
        $conductores = User::where('role', 'driver')
            ->where('is_online', true)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['id', 'name', 'lat', 'lng', 'is_online']);

        return response()->json($conductores);
    }

    public function updateGlobalLocation(Request $request)
    {
        $data = $request->validate([
            'lat'              => 'required|numeric',
            'lng'              => 'required|numeric',
            'bearing'          => 'nullable|integer',
            'speed_kph'        => 'nullable|integer',
            'transit_route_id' => 'nullable|integer',
            'located_at'       => 'nullable|date',
        ]);

        $user = Auth::user();

        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Solo los conductores pueden actualizar ubicación.'], 403);
        }

        DB::transaction(function () use ($user, $data) {

            DB::table('users')->where('id', $user->id)->update([
                'lat'        => $data['lat'],
                'lng'        => $data['lng'],
                'is_online'  => 1,
                'updated_at' => now(),
            ]);

            $vehUpdate = [
                'last_lat'        => $data['lat'],
                'last_lng'        => $data['lng'],
                'last_bearing'    => $data['bearing'] ?? null,
                'last_speed_kph'  => $data['speed_kph'] ?? null,
                'last_located_at' => $data['located_at'] ?? now(),
                'updated_at'      => now(),
            ];

            if (array_key_exists('transit_route_id', $data)) {
                $vehUpdate['transit_route_id'] = $data['transit_route_id'];
            }

            DB::table('vehicles')
                ->where('user_id', $user->id)
                ->update($vehUpdate);
        });

        return response()->json(['ok' => true]);
    }

    public function updateFase(Request $request, $id)
    {
        $ride = Ride::find($id);
        $user = Auth::user();

        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }
        if ($user->role !== 'driver' || (int)$ride->driver_id !== (int)$user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'fase' => 'required|in:esperando,recogiendo,viajando,completado',
        ]);

        $ride->fase = $request->fase;

        switch ($ride->fase) {
            case 'esperando':
                $ride->status = 'pending';
                break;
            case 'recogiendo':
                $ride->status = 'accepted';
                break;
            case 'viajando':
                $ride->status = 'in_progress';
                break;
            case 'completado':
                $ride->status = 'completed';
                break;
            default:
                break;
        }

        $ride->save();

        return response()->json(['message' => 'Fase del viaje actualizada.', 'data' => $ride]);
    }

    public function active()
    {
        $user = Auth::user();

        $ride = Ride::where(function ($q) use ($user) {
                $q->where('passenger_id', $user->id)
                  ->orWhere('driver_id',  $user->id);
            })
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotIn('fase',   ['completado'])
            ->latest()
            ->first();

        if (! $ride) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }

        return response()->json($ride);
    }

    public function cancel($id)
    {
        try {
            $user = Auth::user();

            return DB::transaction(function () use ($id, $user) {

                $ride = Ride::where('id', $id)->lockForUpdate()->first();

                if (! $ride) {
                    return response()->json(['message' => 'Viaje no encontrado.'], 404);
                }

                $isPassenger = ((int)$ride->passenger_id === (int)$user->id);
                $isDriver    = ($ride->driver_id !== null && (int)$ride->driver_id === (int)$user->id);
                $isAdmin     = ($user->role ?? null) === 'admin';

                if (! $isPassenger && ! $isDriver && ! $isAdmin) {
                    return response()->json(['message' => 'No autorizado.'], 403);
                }

                if (in_array($ride->status, ['completed', 'cancelled'], true) ||
                    in_array($ride->fase, ['completado', 'cancelado'], true)
                ) {
                    return response()->json([
                        'message' => 'El viaje ya está finalizado.',
                        'data'    => $ride
                    ], 409);
                }

                if ($ride->fase === 'viajando' || $ride->status === 'in_progress') {
                    return response()->json([
                        'message' => 'No se puede cancelar un viaje en progreso.',
                        'data'    => $ride
                    ], 409);
                }

                $ride->status = 'cancelled';
                $ride->fase   = 'cancelado';
                $ride->save();

                return response()->json([
                    'message' => 'Viaje cancelado.',
                    'data'    => $ride->fresh(),
                ], 200);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al cancelar viaje',
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }
}
