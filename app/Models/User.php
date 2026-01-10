<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'lat',
        'lng',
        'heading',
        'is_online',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_online'         => 'boolean',
        'lat'               => 'float',
        'lng'               => 'float',
        'heading'           => 'float',
    ];

    public function driverProfile()
    {
        return $this->hasOne(\App\Models\Driver::class, 'user_id');
    }

    public function passengerTrips()
    {
        return $this->hasMany(\App\Models\Trip::class, 'user_id');
    }

    public function driverTrips()
    {
        return $this->hasMany(\App\Models\Trip::class, 'driver_id');
    }

    public function trips()
    {
        return $this->hasMany(\App\Models\Trip::class, 'driver_id');
    }

    public function activeDriverVehicleAssignment()
    {
        $driver = $this->driverProfile;

        if (!$driver) {
            return null;
        }

        return $driver->activeVehicleAssignment()
            ->with('vehicle')
            ->first();
    }

    public function activeVehicle()
    {
        $assign = $this->activeDriverVehicleAssignment();
        return $assign ? $assign->vehicle : null;
    }

    public function hasActiveVehicle(): bool
    {
        return (bool) $this->activeVehicle();
    }

    public function scopeDrivers($q)
    {
        try {
            return $q->role('driver');
        } catch (\Throwable $e) {
            return $q->where('role', 'driver');
        }
    }

    public function scopeAdmins($q)
    {
        try {
            return $q->role('admin');
        } catch (\Throwable $e) {
            return $q->where('role', 'admin');
        }
    }

    public function scopePassengers($q)
    {
        try {
            return $q->role('passenger');
        } catch (\Throwable $e) {
            return $q->where('role', 'passenger');
        }
    }
}
