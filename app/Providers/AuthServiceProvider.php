<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Ticket::class => \App\Policies\TicketPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if (!$user) return null;

            try {
                if ($user->hasRole('super_admin') || $user->hasRole('superadmin')) {
                    return true;
                }
            } catch (\Throwable $e) {
                if (($user->role ?? null) === 'super_admin' || ($user->role ?? null) === 'superadmin') {
                    return true;
                }
            }

            return null;
        });
    }
}
