<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'is_banned',
        'rating',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
    ];

    // ───────── Relaciones ─────────

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'assignments')
            ->withTimestamps();
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function media()
    {
        return $this->hasMany(DriverMedia::class);
    }
}
