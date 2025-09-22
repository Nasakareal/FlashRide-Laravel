<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'transit_route_id',
        'last_lat',
        'last_lng',
        'last_bearing',
        'last_speed_kph',
        'last_located_at',
        'brand',
        'model',
        'color',
        'plate_number',
    ];

    protected $casts = [
        'last_lat'        => 'decimal:7',
        'last_lng'        => 'decimal:7',
        'last_bearing'    => 'integer',
        'last_speed_kph'  => 'integer',
        'last_located_at' => 'datetime',
    ];

    public function driverAssignments()
    {
        return $this->hasMany(\App\Models\DriverVehicleAssignment::class, 'vehicle_id');
    }

    public function activeDriverAssignment()
    {
        return $this->hasOne(\App\Models\DriverVehicleAssignment::class, 'vehicle_id')->where('active', 1)->whereNull('ended_at');
    }

    public function routeAssignments()
    {
        return $this->hasMany(\App\Models\RouteVehicleAssignment::class, 'vehicle_id');
    }

    public function activeRouteAssignment()
    {
        return $this->hasOne(\App\Models\RouteVehicleAssignment::class, 'vehicle_id')->where('active', 1)->whereNull('ended_at');
    }
}
