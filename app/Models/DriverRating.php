<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverRating extends Model
{
    protected $fillable = [
        'driver_id',
        'user_id',
        'trip_id',
        'rating',
        'comment',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
