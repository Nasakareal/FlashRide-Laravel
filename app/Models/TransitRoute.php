<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransitRoute extends Model
{
    protected $table = 'transit_routes';

    protected $fillable = [
        'short_name',
        'long_name',
        'vehicle_type',
        'color',
        'text_color',
        'polyline',
        'stops_json',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'stops_json' => 'array',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'transit_route_id');
    }

    public function vehicleAssignments()
    {
        return $this->hasMany(RouteVehicleAssignment::class, 'route_id');
    }

    public function activeVehicleAssignments()
    {
        return $this->hasMany(RouteVehicleAssignment::class, 'route_id')
            ->where('active', 1)->whereNull('ended_at');
    }
}
