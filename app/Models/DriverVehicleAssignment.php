<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVehicleAssignment extends Model
{
    use HasFactory;

    protected $table = 'driver_vehicle_assignments';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'started_at',
        'ended_at',
        'active',
        'notes',
    ];

    protected $casts = [
        'driver_id'  => 'integer',
        'vehicle_id' => 'integer',
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'active'     => 'boolean',
    ];

    // Relaciones
    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class, 'vehicle_id');
    }

    public function scopeActive($q)
    {
        return $q->where('active', 1)->whereNull('ended_at');
    }
}
