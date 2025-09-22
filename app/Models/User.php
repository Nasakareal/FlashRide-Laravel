<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable/*, HasRoles*/;

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
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function vehicleAssignments()
    {
        return $this->hasMany(\App\Models\DriverVehicleAssignment::class, 'driver_id');
    }

    public function activeVehicleAssignment()
    {
        return $this->hasOne(\App\Models\DriverVehicleAssignment::class, 'driver_id')->where('active', 1)->whereNull('ended_at');
    }


}
