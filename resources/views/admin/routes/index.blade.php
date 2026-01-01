@extends('layouts.app')
@section('title','Rutas')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Rutas</h1>
    <div class="small" style="color:var(--muted);">Catálogo de rutas de transporte y su estado</div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.routes.create') }}" class="btn btn-brand px-4">
      <i class="fa-solid fa-route me-2"></i> Nueva ruta
    </a>
  </div>
</div>

<div class="card-soft mb-3">
  <div class="p-3 p-lg-4">
    <form method="GET" action="{{ route('admin.routes.index') }}" class="row g-2 align-items-end">

      {{-- BUSCAR --}}
      <div class="col-12 col-lg-5">
        <label class="form-label small mb-1" style="color:var(--muted);">Buscar</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text"
                 name="q"
                 value="{{ $q ?? request('q') }}"
                 class="form-control"
                 placeholder="Nombre corto o nombre largo">
        </div>
      </div>

      {{-- TIPO --}}
      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">Tipo</label>
        <select name="vehicle_type" class="form-select">
          <option value="">Todos</option>
          @foreach(($vehicleTypes ?? []) as $t)
            <option value="{{ $t }}" @selected(($vehicle_type ?? request('vehicle_type')) === $t)>
              {{ $t }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- ESTADO --}}
      <div class="col-12 col-lg-2">
        <label class="form-label small mb-1" style="color:var(--muted);">Estado</label>
        @php $ia = (string)($is_active ?? request('is_active','')); @endphp
        <select name="is_active" class="form-select">
          <option value=""  @selected($ia==='')>Todas</option>
          <option value="1" @selected($ia==='1')>Activas</option>
          <option value="0" @selected($ia==='0')>Inactivas</option>
        </select>
      </div>

      {{-- ACTIONS --}}
      <div class="col-12 col-lg-2 d-flex gap-2">
        <button class="btn btn-outline-secondary w-100" type="submit">
          <i class="fa-solid fa-filter me-2"></i> Filtrar
        </button>

        <a class="btn btn-outline-secondary w-100" href="{{ route('admin.routes.index') }}">
          <i class="fa-solid fa-rotate-left me-2"></i> Limpiar
        </a>
      </div>

    </form>
  </div>
</div>

<div class="card-soft overflow-hidden">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="ps-4">ID</th>
          <th>Ruta</th>
          <th>Tipo</th>
          <th>Colores</th>
          <th>Estado</th>
          <th class="text-end">Unidades</th>
          <th class="text-end pe-4">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($routes as $r)
          @php
            $badge = $r->is_active ? 'bg-success' : 'bg-secondary';
            $color = $r->color ? ('#'.ltrim((string)$r->color,'#')) : null;
            $textColor = $r->text_color ? ('#'.ltrim((string)$r->text_color,'#')) : null;

            $vehiclesCount = $r->vehicles_count ?? null;
            $activeAssignCount = $r->active_vehicle_assignments_count ?? null;
          @endphp

          <tr>
            <td class="ps-4 fw-semibold">{{ $r->id }}</td>

            <td>
              <div class="fw-semibold">{{ $r->short_name }}</div>
              <div class="small text-muted">{{ $r->long_name }}</div>
            </td>

            <td>
              <span class="badge bg-dark text-uppercase">{{ $r->vehicle_type }}</span>
            </td>

            <td>
              @if($color || $textColor)
                <span class="badge bg-light text-dark border"
                      @if($color && $textColor)
                        style="background:{{ $color }} !important; color:{{ $textColor }} !important;"
                      @elseif($color)
                        style="background:{{ $color }} !important;"
                      @endif
                >
                  {{ $r->color ? strtoupper(ltrim($r->color,'#')) : '—' }}
                  @if($r->text_color)
                    · {{ strtoupper(ltrim($r->text_color,'#')) }}
                  @endif
                </span>
              @else
                <span class="text-muted small">—</span>
              @endif
            </td>

            <td>
              <span class="badge {{ $badge }}">
                {{ $r->is_active ? 'Activa' : 'Inactiva' }}
              </span>
            </td>

            <td class="text-end">
              <div class="fw-semibold">
                {{ is_null($vehiclesCount) ? '—' : (int)$vehiclesCount }}
              </div>
              <div class="small text-muted">
                Asignadas: {{ is_null($activeAssignCount) ? '—' : (int)$activeAssignCount }}
              </div>
            </td>

            <td class="text-end pe-4">
              <div class="btn-group btn-group-sm" role="group">

                <a href="{{ route('admin.routes.show', $r) }}"
                   class="btn btn-outline-info"
                   title="Ver">
                  <i class="fa-regular fa-eye"></i>
                </a>

                <a href="{{ route('admin.routes.edit', $r) }}"
                   class="btn btn-outline-success"
                   title="Editar">
                  <i class="fa-regular fa-pen-to-square"></i>
                </a>

                @if($r->is_active)
                  <form action="{{ route('admin.routes.deactivate', $r) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-warning" title="Desactivar">
                      <i class="fa-solid fa-ban"></i>
                    </button>
                  </form>
                @else
                  <form action="{{ route('admin.routes.activate', $r) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-primary" title="Activar">
                      <i class="fa-solid fa-circle-check"></i>
                    </button>
                  </form>
                @endif

                <form action="{{ route('admin.routes.destroy', $r) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Eliminar esta ruta?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-outline-danger" title="Eliminar">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>

              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">
              No hay rutas registradas.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($routes->hasPages())
  <div class="mt-4 d-flex justify-content-end">
    {{ $routes->links('vendor.pagination.bootstrap-4') }}
  </div>
@endif

@endsection
