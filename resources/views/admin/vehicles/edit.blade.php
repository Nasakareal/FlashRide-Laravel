@extends('layouts.app')
@section('title','Editar vehículo')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Editar vehículo</h1>
    <div class="small" style="color:var(--muted);">
      Actualiza la información de la unidad
    </div>
  </div>

  <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary px-4">
    <i class="fa-solid fa-arrow-left me-2"></i> Volver
  </a>
</div>

{{-- CARD FORM --}}
<div class="card-soft">
  <div class="p-3 p-lg-4">

    <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}" class="row g-3">
      @csrf
      @method('PUT')

      {{-- DUEÑO / USER --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Dueño (usuario)
        </label>

        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-user"></i>
          </span>

          <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
            <option value="">Selecciona un usuario</option>
            @foreach(($owners ?? []) as $o)
              <option value="{{ $o->id }}"
                @selected((string)old('user_id', $vehicle->user_id) === (string)$o->id)>
                #{{ $o->id }} — {{ $o->name }}
                @if(!empty($o->phone)) · {{ $o->phone }} @endif
                @if(!empty($o->email)) · {{ $o->email }} @endif
              </option>
            @endforeach
          </select>
        </div>

        @error('user_id')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div class="small mt-1 text-muted">
          Este usuario es el propietario de la unidad (no necesariamente el conductor activo).
        </div>
      </div>

      {{-- TIPO --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Tipo de vehículo
        </label>

        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-car-side"></i>
          </span>

          <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror" required>
            <option value="">Selecciona un tipo</option>
            @foreach(($vehicleTypes ?? ['combi','taxi','bus']) as $t)
              <option value="{{ $t }}" @selected(old('vehicle_type', $vehicle->vehicle_type) === $t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        @error('vehicle_type')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- RUTA (OPCIONAL) --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Ruta (opcional)
        </label>

        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-route"></i>
          </span>

          <select name="transit_route_id" class="form-select @error('transit_route_id') is-invalid @enderror">
            <option value="">Sin ruta</option>
            @foreach(($routes ?? []) as $r)
              <option value="{{ $r->id }}"
                @selected((string)old('transit_route_id', $vehicle->transit_route_id) === (string)$r->id)>
                Ruta #{{ $r->id }}
              </option>
            @endforeach
          </select>
        </div>

        @error('transit_route_id')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div class="small mt-1 text-muted">
          Si tu asignación real de ruta se maneja por <b>route_vehicle_assignments</b>, esto puede quedar vacío.
        </div>
      </div>

      {{-- PLACAS --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Placas
        </label>

        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-hashtag"></i>
          </span>
          <input
            name="plate_number"
            value="{{ old('plate_number', $vehicle->plate_number) }}"
            class="form-control text-uppercase @error('plate_number') is-invalid @enderror"
            placeholder="Ej. XAA-0001"
            required>
        </div>

        @error('plate_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="small mt-1 text-muted">
          Debe ser única.
        </div>
      </div>

      {{-- MARCA --}}
      <div class="col-12 col-lg-4">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Marca
        </label>

        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-industry"></i>
          </span>
          <input
            name="brand"
            value="{{ old('brand', $vehicle->brand) }}"
            class="form-control @error('brand') is-invalid @enderror"
            placeholder="Ej. Nissan"
            required>
        </div>

        @error('brand')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- MODELO --}}
      <div class="col-12 col-lg-4">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Modelo
        </label>

        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-car"></i>
          </span>
          <input
            name="model"
            value="{{ old('model', $vehicle->model) }}"
            class="form-control @error('model') is-invalid @enderror"
            placeholder="Ej. Urvan"
            required>
        </div>

        @error('model')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- COLOR --}}
      <div class="col-12 col-lg-4">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Color
        </label>

        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-palette"></i>
          </span>
          <input
            name="color"
            value="{{ old('color', $vehicle->color) }}"
            class="form-control @error('color') is-invalid @enderror"
            placeholder="Ej. Gris"
            required>
        </div>

        @error('color')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- ACTIONS --}}
      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>

        <button class="btn btn-brand px-4">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar cambios
        </button>
      </div>

    </form>

  </div>
</div>

@endsection

@push('scripts')
<script>
  (function () {
    const el = document.querySelector('input[name="plate_number"]');
    if (!el) return;
    el.addEventListener('input', function () {
      this.value = (this.value || '').toUpperCase();
    });
  })();
</script>
@endpush
