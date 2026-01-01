<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteVehicleAssignment extends Model
{
    protected $table = 'route_vehicle_assignments';

    protected $fillable = [
        'route_id',
        'vehicle_id',
        'started_at',
        'ended_at',
        'active',
        'notes',
    ];

    protected $casts = [
        'route_id'   => 'integer',
        'vehicle_id' => 'integer',
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'active'     => 'boolean',
    ];

    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class, 'vehicle_id');
    }

    public function route()
    {
        return $this->belongsTo(\App\Models\TransitRoute::class, 'route_id');
    }

    public function scopeActive($q)
    {
        return $q->where('active', 1)->whereNull('ended_at');
    }
}
