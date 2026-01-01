@extends('layouts.app')
@section('title','Asignar ruta')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Asignar ruta</h1>
    <div class="small" style="color:var(--muted);">
      Vehículo {{ $vehicle->brand }} {{ $vehicle->model }} · {{ $vehicle->plate_number }}
    </div>
  </div>

  <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-outline-secondary px-4">
    <i class="fa-solid fa-arrow-left me-2"></i> Volver
  </a>
</div>

<div class="row g-4">

  {{-- INFO VEHÍCULO --}}
  <div class="col-12 col-lg-5">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <h6 class="fw-bold mb-3">Vehículo</h6>

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
      </div>
    </div>
  </div>

  {{-- FORM ASIGNAR RUTA --}}
  <div class="col-12 col-lg-7">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">

        <h6 class="fw-bold mb-3">Ruta activa</h6>

        @php
          $currentAssign = $vehicle->activeRouteAssignment;
          $currentRoute  = optional($currentAssign)->route;
        @endphp

        @if($currentRoute)
          <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Ruta #{{ $currentRoute->id }}</div>
              <div class="small text-muted">
                Asignada desde: {{ optional($currentAssign->started_at)->format('d/m/Y H:i') }}
              </div>
            </div>
          </div>
        @else
          <div class="alert alert-secondary">
            Sin ruta asignada actualmente.
          </div>
        @endif

        <form method="POST" action="{{ route('admin.vehicles.assign-route.store', $vehicle) }}" class="row g-3">
          @csrf

          {{-- SELECT RUTA --}}
          <div class="col-12">
            <label class="form-label small mb-1" style="color:var(--muted);">
              Seleccionar ruta
            </label>

            <select name="route_id" class="form-select @error('route_id') is-invalid @enderror" required>
              <option value="">Selecciona una ruta</option>
              @foreach($routes as $r)
                <option value="{{ $r->id }}" @selected(old('route_id') == $r->id)>
                  Ruta #{{ $r->id }}
                </option>
              @endforeach
            </select>

            @error('route_id')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <div class="small mt-1 text-muted">
              Esta asignación se guarda en <b>route_vehicle_assignments</b> y mantiene histórico.
            </div>
          </div>

          {{-- NOTAS --}}
          <div class="col-12">
            <label class="form-label small mb-1" style="color:var(--muted);">
              Observaciones (opcional)
            </label>

            <textarea
              name="notes"
              rows="3"
              class="form-control @error('notes') is-invalid @enderror"
              placeholder="Ej. Ruta temporal, ajuste por evento, etc.">{{ old('notes') }}</textarea>

            @error('notes')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          {{-- ACTIONS --}}
          <div class="col-12 d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-outline-secondary px-4">
              Cancelar
            </a>

            <button class="btn btn-brand px-4">
              <i class="fa-solid fa-route me-2"></i>
              {{ $currentRoute ? 'Cambiar ruta' : 'Asignar ruta' }}
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>

</div>

@endsection
