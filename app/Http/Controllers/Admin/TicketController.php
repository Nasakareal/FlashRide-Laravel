<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private function isRole($user, string $role): bool
    {
        try { return $user->hasRole($role); }
        catch (\Throwable $e) { return (($user->role ?? null) === $role); }
    }

    private function isAdminLike($user): bool
    {
        return $this->isRole($user, 'admin') || $this->isRole($user, 'super_admin') || $this->isRole($user, 'superadmin');
    }

    private function isSupport($user): bool
    {
        return $this->isRole($user, 'support');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $q = Ticket::query()
            ->with(['createdBy', 'assignedTo'])
            ->orderByDesc('id');

        if ($this->isAdminLike($user)) {
            // todos
        } elseif ($this->isSupport($user)) {
            $q->where(function ($w) use ($user) {
                $w->where('assigned_to_id', $user->id)
                  ->orWhereNull('assigned_to_id'); // en web, soporte ve también "sin asignar"
            });
        } else {
            abort(403);
        }

        if ($status = $request->query('status')) {
            $q->where('status', $status);
        }

        $tickets = $q->paginate(20);

        // Vista sugerida: resources/views/admin/tickets/index.blade.php
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['createdBy', 'assignedTo', 'messages.sender']);

        // Vista sugerida: resources/views/admin/tickets/show.blade.php
        return view('admin.tickets.show', compact('ticket'));
    }

    public function claim(Request $request, Ticket $ticket)
    {
        $this->authorize('claim', $ticket);

        $updated = Ticket::where('id', $ticket->id)
            ->whereNull('assigned_to_id')
            ->update([
                'assigned_to_id' => $request->user()->id,
                'status' => 'assigned',
            ]);

        if (!$updated) {
            return back()->with('error', 'Ya fue tomado por otra persona.');
        }

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $request->user()->id,
            'message' => 'Ticket tomado por soporte.',
        ]);

        return redirect()->route('admin.tickets.show', $ticket)->with('ok', 'Ticket tomado.');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('message', $ticket);

        $data = $request->validate([
            'message' => ['required', 'string', 'min:1'],
        ]);

        // soporte/admin contestó -> pending_user
        if (in_array($ticket->status, ['open', 'assigned', 'pending_user'], true)) {
            $ticket->status = 'pending_user';
            $ticket->save();
        }

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        return back()->with('ok', 'Mensaje enviado.');
    }

    public function close(Request $request, Ticket $ticket)
    {
        $this->authorize('close', $ticket);

        $ticket->status = 'closed';
        $ticket->save();

        if ($request->filled('message')) {
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'sender_id' => $request->user()->id,
                'message' => (string) $request->input('message'),
            ]);
        }

        return back()->with('ok', 'Ticket cerrado.');
    }
}
