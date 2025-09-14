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
        'is_active' => 'integer',
        'stops_json' => 'array',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }
}
