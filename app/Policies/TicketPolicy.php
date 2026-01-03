<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    protected function isRole(User $user, string $role): bool
    {
        try {
            return $user->hasRole($role);
        } catch (\Throwable $e) {
            return (($user->role ?? null) === $role);
        }
    }

    protected function isAnyRole(User $user, array $roles): bool
    {
        foreach ($roles as $r) {
            if ($this->isRole($user, $r)) return true;
        }
        return false;
    }

    public function before(User $user, $ability)
    {
        if ($this->isAnyRole($user, ['admin', 'super_admin', 'superadmin'])) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user)
    {
        return $user !== null;
    }

    public function view(User $user, Ticket $ticket)
    {
        if ($this->isRole($user, 'support')) {
            return (int) $ticket->assigned_to_id === (int) $user->id;
        }

        return (int) $ticket->created_by_id === (int) $user->id;
    }

    public function create(User $user)
    {
        return $user !== null;
    }

    public function update(User $user, Ticket $ticket)
    {
        if ($this->isRole($user, 'support')) {
            return (int) $ticket->assigned_to_id === (int) $user->id;
        }

        return (int) $ticket->created_by_id === (int) $user->id;
    }

    public function close(User $user, Ticket $ticket)
    {
        if ($this->isRole($user, 'support')) {
            return (int) $ticket->assigned_to_id === (int) $user->id;
        }

        return (int) $ticket->created_by_id === (int) $user->id;
    }

    public function claim(User $user, Ticket $ticket)
    {
        if ($this->isRole($user, 'support')) {
            return $ticket->assigned_to_id === null;
        }

        return false;
    }

    public function message(User $user, Ticket $ticket)
    {
        if ($this->isRole($user, 'support')) {
            return (int) $ticket->assigned_to_id === (int) $user->id;
        }

        return (int) $ticket->created_by_id === (int) $user->id;
    }

    public function delete(User $user, Ticket $ticket)
    {
        return false;
    }
}
