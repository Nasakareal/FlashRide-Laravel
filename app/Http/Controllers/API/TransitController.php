<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransitRoute;
use Illuminate\Support\Facades\DB;

class TransitController extends Controller
{
    public function routes()
    {
        return TransitRoute::active()
            ->select('id','short_name','long_name','vehicle_type','color','text_color')
            ->orderBy('short_name')
            ->get();
    }

    public function routeShow($id)
    {
        $r = TransitRoute::find($id);
        if (!$r || !$r->is_active) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $stops = $r->stops_json;
        if (is_string($stops) && $stops !== '') {
            $decoded = json_decode($stops, true);
            $stops = is_array($decoded) ? $decoded : [];
        } elseif (!is_array($stops)) {
            $stops = [];
        }

        return [
            'id'           => $r->id,
            'short_name'   => $r->short_name,
            'long_name'    => $r->long_name,
            'vehicle_type' => $r->vehicle_type,
            'color'        => $r->color,
            'text_color'   => $r->text_color,
            'polyline'     => $r->polyline,
            'stops'        => $stops,
        ];
    }

    public function vehicles(Request $req)
    {
        return DB::table('vehicles as v')
            ->join('users as u', 'u.id', '=', 'v.user_id')
            ->where('u.role', 'driver')
            ->where('u.is_online', 1)
            ->where('v.vehicle_type', '<>', 'combi')
            ->whereNotNull('v.last_lat')
            ->whereNotNull('v.last_lng')
            ->where('v.last_located_at', '>=', now()->subMinutes(5))
            ->orderByDesc('v.last_located_at')
            ->limit(200)
            ->get([
                'v.id',
                'v.vehicle_type',
                'v.transit_route_id',
                'v.last_lat',
                'v.last_lng',
                'v.last_bearing',
                'v.last_speed_kph',
                'v.last_located_at',
            ]);
    }

    public function routeVehicles($id)
    {
        return DB::table('vehicles as v')
            ->join('users as u', 'u.id', '=', 'v.user_id')
            ->where('u.role', 'driver')
            ->where('u.is_online', 1)
            ->where('v.vehicle_type', 'combi')
            ->where('v.transit_route_id', $id)
            ->whereNotNull('v.last_lat')
            ->whereNotNull('v.last_lng')
            ->where('v.last_located_at', '>=', now()->subMinutes(5))
            ->orderByDesc('v.last_located_at')
            ->limit(200)
            ->get([
                'v.id',
                'v.last_lat',
                'v.last_lng',
                'v.last_bearing',
                'v.last_speed_kph',
                'v.last_located_at',
            ]);
    }

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

        $owner = DB::table('vehicles')->where('id', $id)->value('user_id');
        if (!$owner || (int)$owner !== (int)$req->user()->id) {
            return response()->json(['ok' => false, 'error' => 'forbidden'], 403);
        }

        DB::table('vehicles')->where('id', $id)->update([
            'transit_route_id' => array_key_exists('transit_route_id', $data) ? $data['transit_route_id'] : DB::raw('transit_route_id'),
            'last_lat'         => $data['lat'],
            'last_lng'         => $data['lng'],
            'last_bearing'     => $data['bearing'] ?? null,
            'last_speed_kph'   => $data['speed_kph'] ?? null,
            'last_located_at'  => $data['located_at'] ?? now(),
            'updated_at'       => now(),
        ]);

        DB::table('users')->where('id', $owner)->update([
            'is_online'  => 1,
            'lat'        => $data['lat'],
            'lng'        => $data['lng'],
            'updated_at' => now(),
        ]);

        return ['ok' => true];
    }
}
