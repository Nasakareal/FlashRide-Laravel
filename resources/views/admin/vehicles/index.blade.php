@extends('layouts.app')
@section('title','Vehículos')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Vehículos</h1>
    <div class="small" style="color:var(--muted);">Administración de unidades, rutas y asignaciones</div>
  </div>

  <a href="{{ route('admin.vehicles.create') }}" class="btn btn-brand px-4">
    <i class="fa-solid fa-plus me-2"></i> Nuevo vehículo
  </a>
</div>

{{-- FILTERS --}}
<div class="card-soft mb-3">
  <div class="p-3 p-lg-4">
    <form method="GET" action="{{ route('admin.vehicles.index') }}" class="row g-2 align-items-end">

      <div class="col-12 col-lg-4">
        <label class="form-label small mb-1" style="color:var(--muted);">Buscar</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text"
                 name="q"
                 value="{{ $q ?? request('q') }}"
                 class="form-control"
                 placeholder="Placas, marca, modelo, color o dueño">
        </div>
      </div>

      <div class="col-12 col-lg-2">
        <label class="form-label small mb-1" style="color:var(--muted);">Tipo</label>
        <select name="vehicle_type" class="form-select">
          <option value="">Todos</option>
          @foreach(($vehicleTypes ?? []) as $t)
            <option value="{{ $t }}" @selected(($vehicle_type ?? request('vehicle_type')) === $t)>{{ $t }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">Ruta</label>
        @php $rid = (string)($route_id ?? request('route_id', '')); @endphp
        <select name="route_id" class="form-select">
          <option value="" @selected($rid==='')>Todas</option>
          <option value="null" @selected($rid==='null')>Sin ruta</option>
          @foreach(($routes ?? []) as $r)
            <option value="{{ $r->id }}" @selected($rid === (string)$r->id)>
              {{ $r->name ?? ('Ruta #'.$r->id) }}
              @if(!empty($r->code)) ({{ $r->code }}) @endif
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">Dueño</label>
        <select name="owner_id" class="form-select">
          <option value="">Todos</option>
          @foreach(($owners ?? []) as $o)
            <option value="{{ $o->id }}" @selected((string)($owner_id ?? request('owner_id')) === (string)$o->id)>
              {{ $o->name }} (#{{ $o->id }})
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-lg-4 d-flex gap-2 mt-2">
        <button class="btn btn-outline-secondary w-100" type="submit">
          <i class="fa-solid fa-filter me-2"></i> Filtrar
        </button>

        <a class="btn btn-outline-secondary w-100" href="{{ route('admin.vehicles.index') }}">
          <i class="fa-solid fa-rotate-left me-2"></i> Limpiar
        </a>
      </div>

    </form>
  </div>
</div>

{{-- TABLE --}}
<div class="card-soft overflow-hidden">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="ps-4">ID</th>
          <th>Vehículo</th>
          <th>Tipo</th>
          <th>Placas</th>
          <th>Ruta</th>
          <th>Dueño</th>
          <th>Conductor activo</th>
          <th class="text-end pe-4">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($vehicles as $v)
          @php
            $owner = $v->user ?? null;
            $route = $v->transitRoute ?? null;

            $activeAssign = $v->activeDriverAssignment ?? null;
            $activeDriver = optional($activeAssign)->driver;

            $locatedAt = $v->last_located_at ?? null;
          @endphp

          <tr>
            <td class="ps-4 fw-semibold">{{ $v->id }}</td>

            <td>
              <div class="fw-semibold">
                {{ $v->brand }} {{ $v->model }}
              </div>
              <div class="small" style="color:var(--muted);">
                {{ $v->color }}
                @if(!empty($locatedAt))
                  · Última ubicación: {{ \Carbon\Carbon::parse($locatedAt)->diffForHumans() }}
                @endif
              </div>
            </td>

            <td>
              <span class="badge bg-dark text-uppercase">{{ $v->vehicle_type }}</span>
            </td>

            <td class="fw-semibold">{{ $v->plate_number }}</td>

           @php
              $route = optional(optional($v->activeRouteAssignment)->route);

              if (!$route->id) {
                $route = optional($v->transitRoute);
              }
            @endphp


            <td>
              @if($route && $route->id)
                <div class="fw-semibold">
                  {{ $route->short_name ?? ('Ruta #'.$route->id) }}
                </div>

                @if(!empty($route->long_name))
                  <div class="small text-muted">{{ $route->long_name }}</div>
                @endif
              @else
                <span class="text-muted small">Sin ruta</span>
              @endif
            </td>


            <td>
              @if($owner)
                <div class="fw-semibold">{{ $owner->name }}</div>
                <div class="small text-muted">
                  {{ $owner->email }}
                  @if(!empty($owner->phone)) · {{ $owner->phone }} @endif
                </div>
              @else
                <span class="text-muted small">Sin dueño</span>
              @endif
            </td>

            <td>
              @if($activeDriver)
                <div class="fw-semibold">{{ $activeDriver->name }}</div>
                <div class="small text-muted">
                  #{{ $activeDriver->id }}
                  @if(!empty($activeDriver->phone)) · {{ $activeDriver->phone }} @endif
                </div>
              @else
                <span class="text-muted small">Sin asignación</span>
              @endif
            </td>

            <td class="text-end pe-4">
              <div class="btn-group btn-group-sm" role="group">

                <a href="{{ route('admin.vehicles.show', $v) }}"
                   class="btn btn-outline-info"
                   title="Ver">
                  <i class="fa-regular fa-eye"></i>
                </a>

                <a href="{{ route('admin.vehicles.edit', $v) }}"
                   class="btn btn-outline-success"
                   title="Editar">
                  <i class="fa-regular fa-pen-to-square"></i>
                </a>

                <form action="{{ route('admin.vehicles.destroy', $v) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Eliminar este vehículo?');">
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
            <td colspan="8" class="text-center py-4 text-muted">
              No hay vehículos registrados.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($vehicles->hasPages())
  <div class="mt-4 d-flex justify-content-end">
    {{ $vehicles->links('vendor.pagination.bootstrap-4') }}
  </div>
@endif

@endsection
