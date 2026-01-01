<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';

    protected $fillable = [
        'user_id',
        'license_number',
        'license_expires_at',
        'curp',
        'rfc',
        'birthdate',
        'is_verified',
        'verified_at',
        'address',
        'notes',
        'rating_avg',
        'rating_count',
    ];

    protected $casts = [
        'license_expires_at' => 'date',
        'birthdate'          => 'date',
        'is_verified'        => 'boolean',
        'verified_at'        => 'datetime',
        'rating_avg'         => 'float',
        'rating_count'       => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
