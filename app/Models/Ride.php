<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'passenger_id',
        'driver_id',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'estimated_cost',
        'status',
        'fase',
    ];
}
