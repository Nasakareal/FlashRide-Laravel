<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransitRoute;
use Illuminate\Support\Facades\DB;

class TransitController extends Controller
{
    // GET /api/transit/routes (público)
    public function routes()
    {
        return TransitRoute::active()
            ->select('id','short_name','long_name','vehicle_type','color','text_color')
            ->orderBy('short_name')
            ->get();
    }

    // GET /api/transit/routes/{id} (público)
    public function routeShow($id)
    {
        $r = TransitRoute::find($id);
        if (!$r || !$r->is_active) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return [
            'id'           => $r->id,
            'short_name'   => $r->short_name,
            'long_name'    => $r->long_name,
            'vehicle_type' => $r->vehicle_type,
            'color'        => $r->color,
            'text_color'   => $r->text_color,
            'polyline'     => $r->polyline,
            'stops'        => $r->stops_json ?? [],
        ];
    }

    // GET /api/transit/vehicles?transit_route_id=ID (público)
    public function vehicles(Request $req)
    {
        return DB::table('vehicles')
            ->when($req->transit_route_id, fn($q) => $q->where('transit_route_id', $req->transit_route_id))
            ->select('id','vehicle_type','transit_route_id','last_lat','last_lng','last_bearing','last_speed_kph','last_located_at')
            ->whereNotNull('last_lat')->whereNotNull('last_lng')
            ->get();
    }

    // POST /api/transit/vehicles/{id}/ping (auth)
    public function ping(Request $req, $id)
    {
        $data = $req->validate([
            'lat'              => 'required|numeric',
            'lng'              => 'required|numeric',
            'bearing'          => 'nullable|integer',
            'speed_kph'        => 'nullable|integer',
            'located_at'       => 'nullable|date',
            'transit_route_id' => 'nullable|integer'
        ]);

        DB::table('vehicles')->where('id', $id)->update([
            'transit_route_id' => $data['transit_route_id'] ?? DB::raw('transit_route_id'),
            'last_lat'         => $data['lat'],
            'last_lng'         => $data['lng'],
            'last_bearing'     => $data['bearing'] ?? null,
            'last_speed_kph'   => $data['speed_kph'] ?? null,
            'last_located_at'  => $data['located_at'] ?? now(),
        ]);

        return ['ok' => true];
    }
}
