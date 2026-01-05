<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\TransitRoute;
use App\Models\DriverVehicleAssignment;
use App\Models\RouteVehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Driver;


class VehicleController extends Controller
{
    private function vehicleTypes(): array
    {
        return [
            'combi',
            'sedan',
            'camion',
        ];
    }

    public function index(Request $request)
    {
        $q            = trim((string) $request->input('q'));
        $vehicle_type = trim((string) $request->input('vehicle_type'));
        $route_id     = trim((string) $request->input('route_id'));
        $owner_id     = trim((string) $request->input('owner_id'));

        $vehicles = Vehicle::query()
            ->with([
                'user:id,name,email,phone',
                'transitRoute:id,short_name,long_name',
                'activeDriverAssignment',
                'activeDriverAssignment.driver:id,name,email,phone',
                'activeRouteAssignment',
                'activeRouteAssignment.route:id,short_name,long_name',
            ])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('brand', 'like', "%{$q}%")
                        ->orWhere('model', 'like', "%{$q}%")
                        ->orWhere('color', 'like', "%{$q}%")
                        ->orWhere('plate_number', 'like', "%{$q}%");
                })
                ->orWhereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($vehicle_type, function ($query) use ($vehicle_type) {
                $query->where('vehicle_type', $vehicle_type);
            })
            ->when($route_id !== '', function ($query) use ($route_id) {
                if ($route_id === 'null') {
                    $query->whereNull('transit_route_id');
                } elseif (is_numeric($route_id)) {
                    $query->where('transit_route_id', (int) $route_id);
                }
            })
            ->when($owner_id !== '', function ($query) use ($owner_id) {
                if ($owner_id === 'null') {
                    $query->whereNull('user_id');
                } elseif (is_numeric($owner_id)) {
                    $query->where('user_id', (int) $owner_id);
                }
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $vehicleTypes = $this->vehicleTypes();

        $routes = class_exists(TransitRoute::class)
            ? TransitRoute::query()->orderBy('id')->get()
            : collect();

        $owners = User::query()
            ->select('id', 'name', 'email', 'phone')
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('admin.vehicles.index', compact(
            'vehicles',
            'vehicleTypes',
            'routes',
            'owners',
            'q',
            'vehicle_type',
            'route_id',
            'owner_id'
        ));
    }

    public function create()
    {
        $owners = User::query()
            ->select('id', 'name', 'email', 'phone')
            ->orderBy('name')
            ->get();

        $routes = class_exists(TransitRoute::class)
            ? TransitRoute::query()->orderBy('id')->get()
            : collect();

        $vehicleTypes = $this->vehicleTypes();

        return view('admin.vehicles.create', compact('owners', 'routes', 'vehicleTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'          => ['nullable', 'integer', Rule::exists('users', 'id')],
            'vehicle_type'     => ['required', 'string', 'max:191', Rule::in($this->vehicleTypes())],
            'transit_route_id' => ['nullable', 'integer', Rule::exists('transit_routes', 'id')],
            'brand'            => ['required', 'string', 'max:191'],
            'model'            => ['required', 'string', 'max:191'],
            'color'            => ['required', 'string', 'max:191'],
            'plate_number'     => ['required', 'string', 'max:191', Rule::unique('vehicles', 'plate_number')],
        ]);

        $vehicle = new Vehicle();
        $vehicle->user_id = isset($data['user_id']) && $data['user_id'] !== '' ? (int) $data['user_id'] : null;
        $vehicle->vehicle_type = $data['vehicle_type'];
        $vehicle->transit_route_id = $data['transit_route_id'] ?? null;
        $vehicle->brand = $data['brand'];
        $vehicle->model = $data['model'];
        $vehicle->color = $data['color'];
        $vehicle->plate_number = $data['plate_number'];
        $vehicle->save();

        return redirect()
            ->route('admin.vehicles.index')
            ->with('status', "Vehículo {$vehicle->plate_number} creado correctamente.");
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load([
            'user:id,name,email,phone',
            'transitRoute:id,short_name,long_name',
            'activeDriverAssignment',
            'activeDriverAssignment.driver:id,name,email,phone',
            'activeRouteAssignment',
            'activeRouteAssignment.route:id,short_name,long_name',
        ]);

        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $vehicle->load(['user', 'transitRoute']);

        $owners = User::query()
            ->select('id', 'name', 'email', 'phone')
            ->orderBy('name')
            ->get();

        $routes = class_exists(TransitRoute::class)
            ? TransitRoute::query()->orderBy('id')->get()
            : collect();

        $vehicleTypes = $this->vehicleTypes();

        return view('admin.vehicles.edit', compact('vehicle', 'owners', 'routes', 'vehicleTypes'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'user_id'          => ['nullable', 'integer', Rule::exists('users', 'id')],
            'vehicle_type'     => ['required', 'string', 'max:191', Rule::in($this->vehicleTypes())],
            'transit_route_id' => ['nullable', 'integer', Rule::exists('transit_routes', 'id')],
            'brand'            => ['required', 'string', 'max:191'],
            'model'            => ['required', 'string', 'max:191'],
            'color'            => ['required', 'string', 'max:191'],
            'plate_number'     => ['required', 'string', 'max:191', Rule::unique('vehicles', 'plate_number')->ignore($vehicle->id)],
        ]);

        $vehicle->user_id = isset($data['user_id']) && $data['user_id'] !== '' ? (int) $data['user_id'] : null;
        $vehicle->vehicle_type = $data['vehicle_type'];
        $vehicle->transit_route_id = $data['transit_route_id'] ?? null;
        $vehicle->brand = $data['brand'];
        $vehicle->model = $data['model'];
        $vehicle->color = $data['color'];
        $vehicle->plate_number = $data['plate_number'];
        $vehicle->save();

        return redirect()
            ->route('admin.vehicles.edit', $vehicle)
            ->with('status', "Vehículo {$vehicle->plate_number} actualizado.");
    }

    public function destroy(Vehicle $vehicle)
    {
        $plate = $vehicle->plate_number;

        if (method_exists($vehicle, 'driverAssignments')) {
            $vehicle->driverAssignments()->delete();
        }

        if (method_exists($vehicle, 'routeAssignments')) {
            $vehicle->routeAssignments()->delete();
        }

        $vehicle->delete();

        return redirect()
            ->route('admin.vehicles.index')
            ->with('status', "Vehículo {$plate} eliminado.");
    }

    public function assignDriverForm(Vehicle $vehicle)
    {
        $vehicle->load([
            'user:id,name,email,phone',
            'activeDriverAssignment',
            'activeDriverAssignment.driver',
        ]);

        $drivers = Driver::query()
            ->with(['user:id,name,email,phone'])
            ->orderBy('id')
            ->get();

        return view('admin.vehicles.assign-driver', compact('vehicle', 'drivers'));
    }

    public function assignDriverStore(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'driver_id' => ['nullable', 'integer', Rule::exists('drivers', 'id')],
            'notes'     => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($vehicle, $data) {

            DriverVehicleAssignment::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('active', 1)
                ->whereNull('ended_at')
                ->update([
                    'active'     => 0,
                    'ended_at'   => now(),
                    'updated_at' => now(),
                ]);

            if (!empty($data['driver_id'])) {
                DriverVehicleAssignment::create([
                    'driver_id'   => (int) $data['driver_id'],
                    'vehicle_id'  => (int) $vehicle->id,
                    'started_at'  => now(),
                    'ended_at'    => null,
                    'active'      => 1,
                    'notes'       => $data['notes'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.vehicles.show', $vehicle)
            ->with('status', !empty($data['driver_id']) ? 'Conductor asignado correctamente.' : 'Conductor removido correctamente.');
    }

    public function assignRouteForm(Vehicle $vehicle)
    {
        $vehicle->load([
            'activeRouteAssignment',
            'activeRouteAssignment.route',
        ]);

        $routes = TransitRoute::query()->orderBy('id')->get();

        return view('admin.vehicles.assign-route', compact('vehicle', 'routes'));
    }

    public function assignRouteStore(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'route_id' => ['required', 'integer', Rule::exists('transit_routes', 'id')],
            'notes'    => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($vehicle, $data) {
            RouteVehicleAssignment::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('active', 1)
                ->whereNull('ended_at')
                ->update([
                    'active'     => 0,
                    'ended_at'   => now(),
                    'updated_at' => now(),
                ]);

            RouteVehicleAssignment::create([
                'route_id'    => (int) $data['route_id'],
                'vehicle_id'  => (int) $vehicle->id,
                'started_at'  => now(),
                'ended_at'    => null,
                'active'      => 1,
                'notes'       => $data['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.vehicles.show', $vehicle)
            ->with('status', 'Ruta asignada correctamente.');
    }
}
