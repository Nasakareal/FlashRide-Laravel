@extends('layouts.app')
@section('title','Ticket')

@section('content')

@php
  $u = auth()->user();

  $isSupport = false;
  $isAdminLike = false;

  try { $isSupport = $u && $u->hasRole('support'); }
  catch (\Throwable $e) { $isSupport = ($u && (($u->role ?? null) === 'support')); }

  try { $isAdminLike = $u && $u->hasAnyRole(['admin','super_admin','superadmin']); }
  catch (\Throwable $e) { $isAdminLike = ($u && in_array(($u->role ?? null), ['admin','super_admin','superadmin'], true)); }

  $canClaim = $ticket
      && empty($ticket->assigned_to_id)
      && ($ticket->status ?? 'open') !== 'closed'
      && ($isSupport || $isAdminLike);

  $canReply = $ticket
      && ($ticket->status ?? 'open') !== 'closed'
      && ($isSupport || $isAdminLike);

  $canClose = $ticket
      && ($ticket->status ?? 'open') !== 'closed'
      && ($isSupport || $isAdminLike);

  $st = $ticket->status ?? 'open';

  $badge = match($st) {
    'open'         => 'bg-warning text-dark',
    'assigned'     => 'bg-primary',
    'pending_user' => 'bg-info text-dark',
    'closed'       => 'bg-secondary',
    default        => 'bg-dark'
  };

  $stLabel = match($st) {
    'open'         => 'abierto',
    'assigned'     => 'asignado',
    'pending_user' => 'esperando usuario',
    'closed'       => 'cerrado',
    default        => $st
  };

  $createdByName = optional($ticket->createdBy)->name ?? ('User #'.($ticket->created_by_id ?? '—'));
  $assignedToName = optional($ticket->assignedTo)->name ?? null;

  $ctx = $ticket->context_type ? strtoupper($ticket->context_type) : null;
  $ctxId = $ticket->context_id ? '#'.$ticket->context_id : null;

  $messages = $messages ?? ($ticket->messages ?? collect());
@endphp

<div class="d-flex align-items-start justify-content-between mb-3">
  <div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Volver
      </a>

      <div class="h5 mb-0 fw-black">Ticket #{{ $ticket->id }}</div>
      <span class="badge {{ $badge }} text-uppercase">{{ $stLabel }}</span>

      @if($ticket->priority)
        <span class="badge bg-dark text-uppercase">prioridad {{ $ticket->priority }}</span>
      @endif
    </div>

    <div class="mt-2">
      <div class="fw-semibold">{{ $ticket->subject ?: 'Sin asunto' }}</div>
      <div class="small" style="color:var(--muted);">
        Creado por: <b>{{ $createdByName }}</b>
        @if($ctx || $ctxId)
          · <span class="text-uppercase">{{ $ctx }}</span> {{ $ctxId }}
        @endif
        · {{ optional($ticket->created_at)->format('d/m/Y H:i') }}
      </div>
    </div>
  </div>

  <div class="d-flex gap-2">
    @if($assignedToName)
      <span class="badge bg-light text-dark d-flex align-items-center px-3" style="border:1px solid var(--border);">
        <i class="fa-solid fa-user-check me-2"></i> {{ $assignedToName }}
      </span>
    @else
      <span class="badge bg-light text-dark d-flex align-items-center px-3" style="border:1px solid var(--border);">
        <i class="fa-solid fa-user-xmark me-2"></i> Sin asignar
      </span>
    @endif

    @if($canClaim)
      <form action="{{ route('admin.tickets.claim', $ticket) }}" method="POST">
        @csrf
        <button class="btn btn-outline-success">
          <i class="fa-solid fa-hand-pointer me-2"></i> Tomar
        </button>
      </form>
    @endif

    @if($canClose)
      <form action="{{ route('admin.tickets.close', $ticket) }}"
            method="POST"
            onsubmit="return confirm('¿Cerrar este ticket?');">
        @csrf
        <button class="btn btn-outline-danger">
          <i class="fa-solid fa-circle-xmark me-2"></i> Cerrar
        </button>
      </form>
    @endif
  </div>
</div>

<div class="row g-3 g-lg-4">
  <div class="col-12 col-lg-8">

    <div class="card-soft overflow-hidden">
      <div class="p-3 p-lg-4" style="border-bottom:1px solid var(--border);">
        <div class="d-flex align-items-center justify-content-between">
          <div class="fw-black" style="font-weight:900;">
            <i class="fa-regular fa-comments me-2" style="color:var(--brand)"></i> Conversación
          </div>
          <div class="small" style="color:var(--muted);">
            {{ $messages->count() }} mensaje(s)
          </div>
        </div>
      </div>

      <div id="chatBox"
           class="p-3 p-lg-4"
           style="height: 56vh; overflow:auto; background: #fbfbfc;">
        @forelse($messages as $m)
          @php
            $mine = ($m->sender_id ?? null) === ($u->id ?? null);
            $senderName = optional($m->sender)->name ?? ('User #'.($m->sender_id ?? '—'));
            $at = optional($m->created_at)->format('d/m/Y H:i');
          @endphp

          <div class="d-flex {{ $mine ? 'justify-content-end' : 'justify-content-start' }} mb-3">
            <div class="p-3 rounded-4"
                 style="max-width: 78%;
                        border:1px solid var(--border);
                        background: {{ $mine ? '#e8fff4' : '#ffffff' }};">
              <div class="small fw-semibold mb-1" style="color:{{ $mine ? '#0f5132' : 'var(--muted)' }};">
                {{ $mine ? 'Tú' : $senderName }}
              </div>

              <div style="white-space:pre-wrap;">{{ $m->message }}</div>

              <div class="small mt-2 text-end" style="color:var(--muted);">
                {{ $at }}
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-5 text-muted">
            Aún no hay mensajes en este ticket.
          </div>
        @endforelse
      </div>

      <div class="p-3 p-lg-4" style="border-top:1px solid var(--border); background:#fff;">
        @if($canReply)
          <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}">
            @csrf

            <div class="d-flex gap-2 align-items-end">
              <div class="flex-grow-1">
                <label class="form-label small mb-1" style="color:var(--muted);">Responder</label>
                <textarea name="message"
                          class="form-control"
                          rows="2"
                          maxlength="2000"
                          placeholder="Escribe tu mensaje…"
                          required>{{ old('message') }}</textarea>
                @error('message')
                  <div class="small text-danger mt-1">{{ $message }}</div>
                @enderror
              </div>

              <button class="btn btn-brand px-4" type="submit">
                <i class="fa-solid fa-paper-plane me-2"></i> Enviar
              </button>
            </div>

            <div class="small mt-2" style="color:var(--muted);">
              Tip: usa saltos de línea para detallar el caso.
            </div>
          </form>
        @else
          <div class="alert alert-light mb-0" style="border:1px solid var(--border);">
            Este ticket está cerrado. Ya no se pueden enviar mensajes.
          </div>
        @endif
      </div>

    </div>

  </div>

  <div class="col-12 col-lg-4">
    <div class="card-soft overflow-hidden">
      <div class="p-3 p-lg-4 fw-black" style="font-weight:900; border-bottom:1px solid var(--border);">
        <i class="fa-solid fa-circle-info me-2" style="color:var(--brand)"></i> Detalles
      </div>

      <div class="p-3 p-lg-4">
        <div class="small" style="color:var(--muted);">Creado por</div>
        <div class="fw-semibold">{{ $createdByName }}</div>
        @if(!empty(optional($ticket->createdBy)->email))
          <div class="small" style="color:var(--muted);">{{ optional($ticket->createdBy)->email }}</div>
        @endif

        <hr>

        <div class="small" style="color:var(--muted);">Asignado a</div>
        @if($assignedToName)
          <div class="fw-semibold">{{ $assignedToName }}</div>
          @if(!empty(optional($ticket->assignedTo)->email))
            <div class="small" style="color:var(--muted);">{{ optional($ticket->assignedTo)->email }}</div>
          @endif
        @else
          <div class="text-muted">Sin asignar</div>
        @endif

        <hr>

        <div class="small" style="color:var(--muted);">Contexto</div>
        <div class="fw-semibold">
          {{ $ctx ? $ctx : '—' }} {{ $ctxId ? $ctxId : '' }}
        </div>

        <hr>

        <div class="small" style="color:var(--muted);">Fechas</div>
        <div class="small">
          <div>Creado: <b>{{ optional($ticket->created_at)->format('d/m/Y H:i') }}</b></div>
          <div>Actualizado: <b>{{ optional($ticket->updated_at)->format('d/m/Y H:i') }}</b></div>
        </div>

        @if(!empty($ticket->closed_at))
          <hr>
          <div class="small" style="color:var(--muted);">Cerrado</div>
          <div class="small"><b>{{ optional($ticket->closed_at)->format('d/m/Y H:i') }}</b></div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    var el = document.getElementById('chatBox');
    if (el) el.scrollTop = el.scrollHeight;
  })();
</script>

@endsection
