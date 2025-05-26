<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    protected $fillable = [
        'user_id',
        'lat',
        'lng',
        'description',
    ];
}
