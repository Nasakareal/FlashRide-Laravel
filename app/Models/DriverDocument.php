<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    protected $table = 'driver_documents';

    protected $fillable = [
        'driver_id',
        'type',
        'file_path',
        'original_name',
        'mime',
        'size',
        'is_active',
        'uploaded_at',
        'verified_at',
        'notes',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
        'size'        => 'integer',
    ];

    public function driver()
    {
        return $this->belongsTo(\App\Models\Driver::class, 'driver_id');
    }
}
