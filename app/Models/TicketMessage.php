<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    protected $table = 'ticket_messages';

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'ticket_id' => 'integer',
        'sender_id' => 'integer',
        'read_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id');
    }

    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'sender_id');
    }
}
