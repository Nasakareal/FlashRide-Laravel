@extends('layouts.app')
@section('title','Tickets')

@section('content')

@php
  $status = $status ?? request('status');
  $unassigned = (int)($unassigned ?? request('unassigned', 0));
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Tickets</h1>
    <div class="small" style="color:var(--muted);">Soporte y seguimiento de conversaciones</div>
  </div>

  {{-- Si después quieres permitir crear ticket desde web admin, aquí va el botón --}}
  {{-- <a href="{{ route('admin.tickets.create') }}" class="btn btn-brand px-4">
    <i class="fa-solid fa-plus me-2"></i> Nuevo ticket
  </a> --}}
</div>

<div class="card-soft mb-3">
  <div class="p-3 p-lg-4">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-2 align-items-end">

      <div class="col-12 col-lg-5">
        <label class="form-label small mb-1" style="color:var(--muted);">Buscar</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text"
                 name="q"
                 value="{{ $q ?? request('q') }}"
                 class="form-control"
                 placeholder="Asunto, usuario, ID, etc.">
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">Estatus</label>
        <select name="status" class="form-select">
          <option value="">Todos</option>
          <option value="open"         @selected(($status ?? '') === 'open')>Abiertos</option>
          <option value="assigned"     @selected(($status ?? '') === 'assigned')>Asignados</option>
          <option value="pending_user" @selected(($status ?? '') === 'pending_user')>Esperando usuario</option>
          <option value="closed"       @selected(($status ?? '') === 'closed')>Cerrados</option>
        </select>
      </div>

      <div class="col-12 col-lg-4 d-flex gap-2">
        <div class="form-check mt-2 mt-lg-0 w-100 d-flex align-items-center justify-content-start px-2"
             style="border:1px solid var(--border); border-radius:.65rem; background:#fff;">
          <input class="form-check-input me-2" type="checkbox" value="1" id="unassigned"
                 name="unassigned" @checked($unassigned === 1)>
          <label class="form-check-label small" for="unassigned" style="color:var(--muted);">
            Mostrar sin asignar
          </label>
        </div>

        <button class="btn btn-outline-secondary w-100" type="submit">
          <i class="fa-solid fa-filter me-2"></i> Filtrar
        </button>

        <a class="btn btn-outline-secondary w-100" href="{{ route('admin.tickets.index') }}">
          <i class="fa-solid fa-rotate-left me-2"></i> Limpiar
        </a>
      </div>

    </form>

    {{-- Tabs rápidos --}}
    <div class="mt-3 d-flex flex-wrap gap-2">
      <a class="btn btn-sm {{ request('status')==='' && !request('unassigned') ? 'btn-brand' : 'btn-outline-secondary' }}"
         href="{{ route('admin.tickets.index') }}">
        Todos
      </a>

      <a class="btn btn-sm {{ request('status')==='open' ? 'btn-brand' : 'btn-outline-secondary' }}"
         href="{{ route('admin.tickets.index', ['status' => 'open', 'unassigned' => $unassigned]) }}">
        Abiertos
      </a>

      <a class="btn btn-sm {{ request('status')==='assigned' ? 'btn-brand' : 'btn-outline-secondary' }}"
         href="{{ route('admin.tickets.index', ['status' => 'assigned', 'unassigned' => $unassigned]) }}">
        Asignados
      </a>

      <a class="btn btn-sm {{ request('status')==='pending_user' ? 'btn-brand' : 'btn-outline-secondary' }}"
         href="{{ route('admin.tickets.index', ['status' => 'pending_user', 'unassigned' => $unassigned]) }}">
        Esperando usuario
      </a>

      <a class="btn btn-sm {{ (int)request('unassigned',0)===1 ? 'btn-brand' : 'btn-outline-secondary' }}"
         href="{{ route('admin.tickets.index', ['unassigned' => 1]) }}">
        Sin asignar
      </a>

      <a class="btn btn-sm {{ request('status')==='closed' ? 'btn-brand' : 'btn-outline-secondary' }}"
         href="{{ route('admin.tickets.index', ['status' => 'closed', 'unassigned' => $unassigned]) }}">
        Cerrados
      </a>
    </div>

  </div>
</div>

<div class="card-soft overflow-hidden">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="ps-4">ID</th>
          <th>Asunto / Contexto</th>
          <th>Creado por</th>
          <th>Asignado a</th>
          <th>Estatus</th>
          <th class="text-end pe-4">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($tickets as $t)
          @php
            $st = $t->status ?? 'open';

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

            $createdByName = optional($t->createdBy)->name ?? ('User #'.($t->created_by_id ?? '—'));
            $assignedToName = optional($t->assignedTo)->name ?? null;

            $subject = $t->subject ?: 'Sin asunto';

            $ctx = $t->context_type ? strtoupper($t->context_type) : null;
            $ctxId = $t->context_id ? '#'.$t->context_id : null;
          @endphp

          <tr>
            <td class="ps-4 fw-semibold">{{ $t->id }}</td>

            <td>
              <div class="fw-semibold">{{ $subject }}</div>
              <div class="small" style="color:var(--muted);">
                @if($ctx || $ctxId)
                  <i class="fa-solid fa-tag me-1"></i> {{ $ctx ?? 'CONTEXT' }} {{ $ctxId ?? '' }}
                @else
                  <i class="fa-regular fa-comment-dots me-1"></i> Conversación de soporte
                @endif
              </div>
            </td>

            <td>
              <div class="fw-semibold">{{ $createdByName }}</div>
              @if(!empty(optional($t->createdBy)->email))
                <div class="small" style="color:var(--muted);">{{ optional($t->createdBy)->email }}</div>
              @endif
            </td>

            <td>
              @if($assignedToName)
                <span class="badge bg-dark text-uppercase">{{ $assignedToName }}</span>
              @else
                <span class="badge bg-light text-dark">sin asignar</span>
              @endif
            </td>

            <td>
              <span class="badge {{ $badge }} text-uppercase">{{ $stLabel }}</span>
              @if(!empty($t->priority))
                <div class="small mt-1" style="color:var(--muted);">
                  Prioridad: <span class="text-uppercase">{{ $t->priority }}</span>
                </div>
              @endif
            </td>

            <td class="text-end pe-4">
              <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('admin.tickets.show', $t) }}"
                   class="btn btn-outline-info"
                   title="Ver conversación">
                  <i class="fa-regular fa-eye"></i>
                </a>

                {{-- Tomar ticket (solo si está sin asignar) --}}
                @if(empty($t->assigned_to_id) && $st !== 'closed')
                  <form action="{{ route('admin.tickets.claim', $t) }}"
                        method="POST"
                        class="d-inline">
                    @csrf
                    <button class="btn btn-outline-success" title="Tomar ticket">
                      <i class="fa-solid fa-hand-pointer"></i>
                    </button>
                  </form>
                @endif

                {{-- Cerrar ticket --}}
                @if($st !== 'closed')
                  <form action="{{ route('admin.tickets.close', $t) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('¿Cerrar este ticket?');">
                    @csrf
                    <button class="btn btn-outline-danger" title="Cerrar">
                      <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-4 text-muted">
              No hay tickets.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($tickets->hasPages())
  <div class="mt-4 d-flex justify-content-end">
    {{ $tickets->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
  </div>
@endif

@endsection
