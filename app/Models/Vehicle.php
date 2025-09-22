<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

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
