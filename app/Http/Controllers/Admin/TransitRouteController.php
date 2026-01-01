<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransitRoute;
use App\Models\Vehicle;
use App\Models\RouteVehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class TransitRouteController extends Controller
{
    public function index(Request $request)
    {
        $q            = trim((string) $request->input('q'));
        $vehicle_type = trim((string) $request->input('vehicle_type'));
        $is_active    = trim((string) $request->input('is_active'));

        $routes = TransitRoute::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('short_name', 'like', "%{$q}%")
                      ->orWhere('long_name', 'like', "%{$q}%");
                });
            })
            ->when($vehicle_type, function ($query) use ($vehicle_type) {
                $query->where('vehicle_type', $vehicle_type);
            })
            ->when($is_active !== '', function ($query) use ($is_active) {
                $query->where('is_active', (int) $is_active);
            })
            ->withCount([
                'vehicles',
                'activeVehicleAssignments',
            ])
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $vehicleTypes = TransitRoute::query()
            ->select('vehicle_type')
            ->distinct()
            ->orderBy('vehicle_type')
            ->pluck('vehicle_type')
            ->filter()
            ->values()
            ->all();

        if (empty($vehicleTypes)) {
            $vehicleTypes = ['combi','taxi','bus'];
        }

        return view('admin.routes.index', compact(
            'routes',
            'vehicleTypes',
            'q',
            'vehicle_type',
            'is_active'
        ));
    }

    public function create()
    {
        $vehicleTypes = TransitRoute::query()
            ->select('vehicle_type')
            ->distinct()
            ->orderBy('vehicle_type')
            ->pluck('vehicle_type')
            ->filter()
            ->values()
            ->all();

        if (empty($vehicleTypes)) {
            $vehicleTypes = ['combi','taxi','bus'];
        }

        return view('admin.routes.create', compact('vehicleTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'short_name'   => ['required','string','max:191'],
            'long_name'    => ['required','string','max:191'],
            'vehicle_type' => ['required','string','max:191'],
            'color'        => ['nullable','string','max:191'],
            'text_color'   => ['nullable','string','max:191'],
            'polyline'     => ['nullable','string'],
            'stops_json'   => ['nullable'],
            'is_active'    => ['nullable','boolean'],
        ]);

        $stops = $data['stops_json'] ?? null;
        if (is_array($stops)) {
            $stops = json_encode($stops, JSON_UNESCAPED_UNICODE);
        }

        $route = new TransitRoute();
        $route->short_name   = $data['short_name'];
        $route->long_name    = $data['long_name'];
        $route->vehicle_type = $data['vehicle_type'];
        $route->color        = $data['color'] ?? null;
        $route->text_color   = $data['text_color'] ?? null;
        $route->polyline     = $data['polyline'] ?? null;
        $route->stops_json   = $stops;
        $route->is_active    = (bool) ($data['is_active'] ?? true);
        $route->save();

        return redirect()
            ->route('admin.routes.index')
            ->with('status', "Ruta {$route->short_name} creada correctamente.");
    }

    public function show(TransitRoute $route)
    {
        $route->loadCount(['vehicles','activeVehicleAssignments']);

        $directVehicles = Vehicle::query()
            ->where('transit_route_id', $route->id)
            ->with(['user:id,name,email,phone'])
            ->orderBy('id','desc')
            ->limit(50)
            ->get();

        $assignedVehicles = RouteVehicleAssignment::query()
            ->where('route_id', $route->id)
            ->where('active', 1)
            ->whereNull('ended_at')
            ->with(['vehicle.user:id,name,email,phone'])
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return view('admin.routes.show', compact('route','directVehicles','assignedVehicles'));
    }

    public function edit(TransitRoute $route)
    {
        $vehicleTypes = TransitRoute::query()
            ->select('vehicle_type')
            ->distinct()
            ->orderBy('vehicle_type')
            ->pluck('vehicle_type')
            ->filter()
            ->values()
            ->all();

        if (empty($vehicleTypes)) {
            $vehicleTypes = ['combi','taxi','bus'];
        }

        return view('admin.routes.edit', compact('route','vehicleTypes'));
    }

    public function update(Request $request, TransitRoute $route)
    {
        $data = $request->validate([
            'short_name'   => ['required','string','max:191'],
            'long_name'    => ['required','string','max:191'],
            'vehicle_type' => ['required','string','max:191'],
            'color'        => ['nullable','string','max:191'],
            'text_color'   => ['nullable','string','max:191'],
            'polyline'     => ['nullable','string'],
            'stops_json'   => ['nullable'],
            'is_active'    => ['nullable','boolean'],
        ]);

        $stops = $data['stops_json'] ?? null;
        if (is_array($stops)) {
            $stops = json_encode($stops, JSON_UNESCAPED_UNICODE);
        }

        $route->short_name   = $data['short_name'];
        $route->long_name    = $data['long_name'];
        $route->vehicle_type = $data['vehicle_type'];
        $route->color        = $data['color'] ?? null;
        $route->text_color   = $data['text_color'] ?? null;
        $route->polyline     = $data['polyline'] ?? null;
        $route->stops_json   = $stops;
        $route->is_active    = (bool) ($data['is_active'] ?? $route->is_active);
        $route->save();

        return redirect()
            ->route('admin.routes.edit', $route)
            ->with('status', "Ruta {$route->short_name} actualizada.");
    }

    public function destroy(TransitRoute $route)
    {
        $name = $route->short_name;
        $route->delete();

        return redirect()
            ->route('admin.routes.index')
            ->with('status', "Ruta {$name} eliminada.");
    }

    public function activate(TransitRoute $route)
    {
        $route->is_active = 1;
        $route->save();

        return back()->with('status', "Ruta {$route->short_name} activada.");
    }

    public function deactivate(TransitRoute $route)
    {
        $route->is_active = 0;
        $route->save();

        return back()->with('status', "Ruta {$route->short_name} desactivada.");
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['delete','activate','deactivate'])],
            'ids'    => ['required','array'],
            'ids.*'  => ['integer'],
        ]);

        $ids = array_values(array_unique($data['ids']));
        $query = TransitRoute::query()->whereIn('id', $ids);

        if ($data['action'] === 'delete') {
            $rows = $query->get();
            foreach ($rows as $r) {
                $r->delete();
            }
            return back()->with('status', 'Rutas eliminadas.');
        }

        if ($data['action'] === 'activate') {
            $query->update(['is_active' => 1]);
            return back()->with('status', 'Rutas activadas.');
        }

        if ($data['action'] === 'deactivate') {
            $query->update(['is_active' => 0]);
            return back()->with('status', 'Rutas desactivadas.');
        }

        return back();
    }

    public function exportCsv(Request $request)
    {
        $q            = trim((string) $request->input('q'));
        $vehicle_type = trim((string) $request->input('vehicle_type'));
        $is_active    = trim((string) $request->input('is_active'));

        $query = TransitRoute::query()
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('short_name','like',"%{$q}%")
                      ->orWhere('long_name','like',"%{$q}%");
                });
            })
            ->when($vehicle_type, function ($qq) use ($vehicle_type) {
                $qq->where('vehicle_type', $vehicle_type);
            })
            ->when($is_active !== '', function ($qq) use ($is_active) {
                $qq->where('is_active', (int) $is_active);
            })
            ->latest('id');

        $filename = 'routes_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'id','short_name','long_name','vehicle_type','color','text_color','is_active','created_at'
            ]);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->short_name,
                        $r->long_name,
                        $r->vehicle_type,
                        $r->color,
                        $r->text_color,
                        (int) $r->is_active,
                        optional($r->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
