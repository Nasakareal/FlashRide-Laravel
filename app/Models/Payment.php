<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'ride_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
    ];
}
