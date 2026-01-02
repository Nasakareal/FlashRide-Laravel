<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'created_by_id',
        'assigned_to_id',
        'context_type',
        'context_id',
        'subject',
        'status',
        'priority',
    ];

    protected $casts = [
        'created_by_id' => 'integer',
        'assigned_to_id' => 'integer',
        'context_id' => 'integer',
    ];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to_id');
    }

    public function messages()
    {
        return $this->hasMany(\App\Models\TicketMessage::class, 'ticket_id')
            ->orderBy('id', 'asc');
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'assigned', 'pending_user'], true);
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['resolved', 'closed'], true);
    }
}
