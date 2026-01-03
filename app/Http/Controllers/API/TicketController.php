<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    private function isRole($user, string $role): bool
    {
        try {
            return $user->hasRole($role);
        } catch (\Throwable $e) {
            return (($user->role ?? null) === $role);
        }
    }

    private function isAdminLike($user): bool
    {
        return $this->isRole($user, 'admin')
            || $this->isRole($user, 'super_admin')
            || $this->isRole($user, 'superadmin');
    }

    private function isSupport($user): bool
    {
        return $this->isRole($user, 'support');
    }

    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $q = Ticket::query()
                ->with(['createdBy', 'assignedTo'])
                ->orderByDesc('id');

            if ($this->isAdminLike($user)) {
            } elseif ($this->isSupport($user)) {
                $includeUnassigned = (int) $request->query('unassigned', 0) === 1;

                $q->where(function ($w) use ($user, $includeUnassigned) {
                    $w->where('assigned_to_id', $user->id);
                    if ($includeUnassigned) {
                        $w->orWhereNull('assigned_to_id');
                    }
                });
            } else {
                $q->where('created_by_id', $user->id);
            }

            if ($status = $request->query('status')) {
                $q->where('status', $status);
            }

            return response()->json([
                'ok' => true,
                'data' => $q->paginate(20),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();
            $this->authorize('create', Ticket::class);

            $data = $request->validate([
                'subject'      => ['nullable', 'string', 'max:191'],
                'message'      => ['required', 'string', 'min:1'],
                'priority'     => ['nullable', 'in:low,normal,high'],
                'context_type' => ['nullable', 'string', 'max:50'],
                'context_id'   => ['nullable', 'integer'],
            ]);

            $out = DB::transaction(function () use ($user, $data) {
                $ticket = Ticket::create([
                    'created_by_id' => $user->id,
                    'assigned_to_id' => null,
                    'subject' => $data['subject'] ?? null,
                    'priority' => $data['priority'] ?? 'normal',
                    'status' => 'open',
                    'context_type' => $data['context_type'] ?? null,
                    'context_id' => $data['context_id'] ?? null,
                ]);

                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'sender_id' => $user->id,
                    'message' => $data['message'],
                ]);

                return $ticket->load(['createdBy', 'assignedTo', 'messages.sender']);
            });

            return response()->json([
                'ok' => true,
                'data' => $out,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function show(Request $request, Ticket $ticket)
    {
        try {
            $this->authorize('view', $ticket);

            $ticket->load([
                'createdBy',
                'assignedTo',
                'messages.sender',
            ]);

            return response()->json([
                'ok' => true,
                'data' => $ticket,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function claim(Request $request, Ticket $ticket)
    {
        try {
            $user = $request->user();
            $this->authorize('claim', $ticket);

            $out = DB::transaction(function () use ($user, $ticket) {
                $updated = Ticket::where('id', $ticket->id)
                    ->whereNull('assigned_to_id')
                    ->update([
                        'assigned_to_id' => $user->id,
                        'status' => 'assigned',
                    ]);

                if (!$updated) {
                    return null;
                }

                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'sender_id' => $user->id,
                    'message' => 'Ticket tomado por soporte.',
                ]);

                return Ticket::with(['createdBy', 'assignedTo', 'messages.sender'])->find($ticket->id);
            });

            if (!$out) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Este ticket ya fue tomado por otra persona.',
                ], 409);
            }

            return response()->json([
                'ok' => true,
                'data' => $out,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function message(Request $request, Ticket $ticket)
    {
        try {
            $user = $request->user();
            $this->authorize('message', $ticket);

            $data = $request->validate([
                'message' => ['required', 'string', 'min:1'],
            ]);

            $out = DB::transaction(function () use ($user, $ticket, $data) {
                if ($this->isSupport($user) || $this->isAdminLike($user)) {
                    if (in_array($ticket->status, ['open', 'assigned', 'pending_user'], true)) {
                        $ticket->status = 'pending_user';
                    }
                } else {
                    $ticket->status = $ticket->assigned_to_id ? 'assigned' : 'open';
                }

                $ticket->save();

                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'sender_id' => $user->id,
                    'message' => $data['message'],
                ]);

                return $ticket->load(['createdBy', 'assignedTo', 'messages.sender']);
            });

            return response()->json([
                'ok' => true,
                'data' => $out,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function close(Request $request, Ticket $ticket)
    {
        try {
            $user = $request->user();
            $this->authorize('close', $ticket);

            $data = $request->validate([
                'message' => ['nullable', 'string'],
            ]);

            $out = DB::transaction(function () use ($user, $ticket, $data) {
                $ticket->status = 'closed';
                $ticket->save();

                if (!empty($data['message'])) {
                    TicketMessage::create([
                        'ticket_id' => $ticket->id,
                        'sender_id' => $user->id,
                        'message' => $data['message'],
                    ]);
                }

                return $ticket->load(['createdBy', 'assignedTo', 'messages.sender']);
            });

            return response()->json([
                'ok' => true,
                'data' => $out,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}
