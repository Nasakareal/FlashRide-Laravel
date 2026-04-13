<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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

    public function scopeNotExpiredPending(Builder $query): Builder
    {
        return $query->where(function (Builder $nested) {
            $nested->where('status', '!=', 'pending')
                ->orWhere('created_at', '>=', static::pendingCutoff());
        });
    }

    public function hasExpiredPendingTimeout(): bool
    {
        return $this->status === 'pending' && $this->created_at !== null && $this->created_at->lt(static::pendingCutoff());
    }

    public function markAsCancelled(): void
    {
        $this->status = 'cancelled';
        $this->fase = 'cancelado';
    }

    public static function pendingCutoff(): Carbon
    {
        return now()->subMinutes((int) config('rides.pending_timeout_minutes', 10));
    }
}
