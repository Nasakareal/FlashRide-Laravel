@extends('layouts.app')
@section('title','Detalle de vehículo')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Vehículo #{{ $vehicle->id }}</h1>
    <div class="small" style="color:var(--muted);">
      Información general y asignaciones activas
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-outline-success px-4">
      <i class="fa-solid fa-pen me-2"></i> Editar
    </a>

    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary px-4">
      <i class="fa-solid fa-arrow-left me-2"></i> Volver
    </a>
  </div>
</div>

<div class="row g-4">

  {{-- VEHICLE INFO --}}
  <div class="col-12 col-lg-6">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <h6 class="fw-bold mb-3">Datos del vehículo</h6>

        <div class="mb-2">
          <span class="text-muted small">Tipo</span>
          <div class="fw-semibold text-uppercase">{{ $vehicle->vehicle_type }}</div>
        </div>

        <div class="mb-2">
          <span class="text-muted small">Marca / Modelo</span>
          <div class="fw-semibold">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
        </div>

        <div class="mb-2">
          <span class="text-muted small">Color</span>
          <div class="fw-semibold">{{ $vehicle->color }}</div>
        </div>

        <div class="mb-2">
          <span class="text-muted small">Placas</span>
          <div class="fw-semibold">{{ $vehicle->plate_number }}</div>
        </div>

        @if($vehicle->last_located_at)
          <div class="mt-3 small text-muted">
            Última ubicación: {{ $vehicle->last_located_at->diffForHumans() }}
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- OWNER --}}
  <div class="col-12 col-lg-6">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <h6 class="fw-bold mb-3">Dueño del vehículo</h6>

        @if($vehicle->user)
          <div class="fw-semibold">{{ $vehicle->user->name }}</div>
          <div class="text-muted small">{{ $vehicle->user->email }}</div>
          @if($vehicle->user->phone)
            <div class="text-muted small">{{ $vehicle->user->phone }}</div>
          @endif
        @else
          <span class="text-muted">Sin dueño asignado</span>
        @endif
      </div>
    </div>
  </div>

  {{-- DRIVER ASSIGNMENT --}}
  <div class="col-12 col-lg-6">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold mb-0">Conductor activo</h6>

          <a href="{{ route('admin.vehicles.assign-driver', $vehicle) }}"
             class="btn btn-sm btn-outline-primary">
            <i class="fa-solid fa-user-check me-1"></i>
            {{ $vehicle->activeDriverAssignment ? 'Cambiar' : 'Asignar' }}
          </a>
        </div>

        @php
          $assign = $vehicle->activeDriverAssignment;
          $driver = optional($assign)->driver;
        @endphp

        @if($driver)
          <div class="fw-semibold">{{ $driver->name }}</div>
          <div class="small text-muted">{{ $driver->email }}</div>
          @if($driver->phone)
            <div class="small text-muted">{{ $driver->phone }}</div>
          @endif

          <div class="small text-muted mt-2">
            Asignado desde: {{ optional($assign->started_at)->format('d/m/Y H:i') }}
          </div>
        @else
          <span class="text-muted">Sin conductor asignado</span>
        @endif
      </div>
    </div>
  </div>

  {{-- ROUTE ASSIGNMENT --}}
  <div class="col-12 col-lg-6">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold mb-0">Ruta activa</h6>

          <a href="{{ route('admin.vehicles.assign-route', $vehicle) }}"
             class="btn btn-sm btn-outline-primary">
            <i class="fa-solid fa-route me-1"></i>
            {{ $vehicle->activeRouteAssignment ? 'Cambiar' : 'Asignar' }}
          </a>
        </div>

        @php
          $routeAssign = $vehicle->activeRouteAssignment;
          $route = optional($routeAssign)->route;

          if (!$route || !$route->id) {
            $route = $vehicle->transitRoute;
          }
        @endphp

        @if($route && $route->id)
          <div class="fw-semibold">
            {{ $route->short_name ?? ('Ruta #'.$route->id) }}
          </div>

          @if($routeAssign)
            <div class="small text-muted">
              Asignada desde: {{ optional($routeAssign->started_at)->format('d/m/Y H:i') }}
            </div>
          @endif
        @else
          <span class="text-muted">Sin ruta asignada</span>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
