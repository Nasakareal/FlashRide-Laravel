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
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function vehicleAssignments()
    {
        return $this->hasMany(DriverVehicleAssignment::class, 'driver_id');
    }

    public function activeVehicleAssignment()
    {
        return $this->hasOne(DriverVehicleAssignment::class, 'driver_id')
            ->where('active', 1)
            ->whereNull('ended_at');
    }

    public function passengerTrips()
    {
        return $this->hasMany(Trip::class, 'user_id');
    }

    public function driverTrips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    // --- Scopes útiles (opcionales) ---
    public function scopeDrivers($q)   { return $q->role('driver'); }
    public function scopeAdmins($q)    { return $q->role('admin'); }
    public function scopePassengers($q){ return $q->role('passenger'); }
}
