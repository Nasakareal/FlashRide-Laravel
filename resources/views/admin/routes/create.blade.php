@extends('layouts.app')
@section('title','Nueva ruta')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Nueva ruta</h1>
    <div class="small" style="color:var(--muted);">
      Registrar una ruta de transporte
    </div>
  </div>

  <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary px-4">
    <i class="fa-solid fa-arrow-left me-2"></i> Volver
  </a>
</div>

{{-- CARD FORM --}}
<div class="card-soft">
  <div class="p-3 p-lg-4">

    <form method="POST" action="{{ route('admin.routes.store') }}" class="row g-3">
      @csrf

      {{-- SHORT NAME --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Nombre corto
        </label>
        <input
          name="short_name"
          value="{{ old('short_name') }}"
          class="form-control @error('short_name') is-invalid @enderror"
          placeholder="Ej. GRIS 1"
          required>

        @error('short_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- LONG NAME --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Nombre largo
        </label>
        <input
          name="long_name"
          value="{{ old('long_name') }}"
          class="form-control @error('long_name') is-invalid @enderror"
          placeholder="Ej. Ruta Gris 1 Tecnológico"
          required>

        @error('long_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- VEHICLE TYPE --}}
      <div class="col-12 col-lg-4">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Tipo de vehículo
        </label>
        <select
          name="vehicle_type"
          class="form-select @error('vehicle_type') is-invalid @enderror"
          required>
          <option value="">Selecciona un tipo</option>
          @foreach(($vehicleTypes ?? ['combi','taxi','bus']) as $t)
            <option value="{{ $t }}" @selected(old('vehicle_type')===$t)>
              {{ strtoupper($t) }}
            </option>
          @endforeach
        </select>

        @error('vehicle_type')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- ACTIVE --}}
      <div class="col-12 col-lg-2">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Estado
        </label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active','1')=='1')>Activa</option>
          <option value="0" @selected(old('is_active')=='0')>Inactiva</option>
        </select>
      </div>

      {{-- COLOR --}}
      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Color de ruta
        </label>
        <div class="d-flex align-items-center gap-2">
          <input
            type="color"
            id="color_picker"
            class="form-control form-control-color"
            value="#{{ old('color','0080FF') }}"
            title="Selecciona color de la ruta">

          <input
            type="text"
            name="color"
            id="color_hex"
            value="{{ old('color','0080FF') }}"
            class="form-control"
            placeholder="HEX sin #">
</div>


        @error('color')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- TEXT COLOR --}}
      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Color de texto
        </label>
        <div class="input-group">
          <span class="input-group-text">#</span>
          <input
            name="text_color"
            value="{{ old('text_color') }}"
            class="form-control @error('text_color') is-invalid @enderror"
            placeholder="FFFFFF">
        </div>

        @error('text_color')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- POLYLINE --}}
      <div class="col-12">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Polyline (opcional)
        </label>
        <textarea
          name="polyline"
          rows="3"
          class="form-control @error('polyline') is-invalid @enderror"
          placeholder="Encoded polyline de Google Maps">{{ old('polyline') }}</textarea>

        @error('polyline')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div class="small mt-1 text-muted">
          Puedes dejarlo vacío y cargar la ruta después.
        </div>
      </div>

      {{-- STOPS JSON --}}
      <div class="col-12">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Paradas (JSON)
        </label>
        <textarea
          name="stops_json"
          rows="4"
          class="form-control @error('stops_json') is-invalid @enderror"
          placeholder='[{"lat":19.70,"lng":-101.19,"name":"Parada 1"}]'>{{ old('stops_json') }}</textarea>

        @error('stops_json')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div class="small mt-1 text-muted">
          Debe ser un JSON válido. Puede quedar vacío.
        </div>
      </div>

      {{-- ACTIONS --}}
      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>

        <button class="btn btn-brand px-4">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar ruta
        </button>
      </div>

    </form>

  </div>
</div>

@endsection
