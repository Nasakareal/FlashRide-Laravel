<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

        'driver_lat',
        'driver_lng',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }
}
