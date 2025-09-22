<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteVehicleAssignment extends Model
{
    protected $table = 'route_vehicle_assignments';

    protected $fillable = [
        'route_id', 'vehicle_id', 'started_at', 'ended_at', 'active', 'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'active'     => 'boolean',
    ];

    public function vehicle() { return $this->belongsTo(Vehicle::class, 'vehicle_id'); }
    public function route()   { return $this->belongsTo(\App\Models\TransitRoute::class, 'route_id'); }
}
