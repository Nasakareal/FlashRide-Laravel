@extends('layouts.app')
@section('title','Editar ruta')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Editar ruta</h1>
    <div class="small" style="color:var(--muted);">
      Actualizar datos de la ruta <b>{{ $route->short_name }}</b>
    </div>
  </div>

  <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary px-4">
    <i class="fa-solid fa-arrow-left me-2"></i> Volver
  </a>
</div>

{{-- CARD FORM --}}
<div class="card-soft">
  <div class="p-3 p-lg-4">

    <form method="POST" action="{{ route('admin.routes.update', $route) }}" class="row g-3">
      @csrf
      @method('PUT')

      {{-- SHORT NAME --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Nombre corto
        </label>
        <input
          name="short_name"
          value="{{ old('short_name', $route->short_name) }}"
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
          value="{{ old('long_name', $route->long_name) }}"
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
            <option value="{{ $t }}" @selected(old('vehicle_type', $route->vehicle_type) === $t)>
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
          <option value="1" @selected((string)old('is_active', (int)$route->is_active) === '1')>Activa</option>
          <option value="0" @selected((string)old('is_active', (int)$route->is_active) === '0')>Inactiva</option>
        </select>
      </div>

      @php
        $routeColor = strtoupper(ltrim((string)old('color', $route->color ?? '0080FF'), '#'));
        if (!preg_match('/^[0-9A-F]{6}$/', $routeColor)) $routeColor = '0080FF';

        $textColor = strtoupper(ltrim((string)old('text_color', $route->text_color ?? 'FFFFFF'), '#'));
        if (!preg_match('/^[0-9A-F]{6}$/', $textColor)) $textColor = 'FFFFFF';

        $stopsVal = old('stops_json');
        if ($stopsVal === null) {
          if (is_array($route->stops_json)) {
            $stopsVal = json_encode($route->stops_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
          } else {
            $stopsVal = (string)($route->stops_json ?? '');
          }
        }
      @endphp

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
            value="#{{ $routeColor }}"
            title="Selecciona color de la ruta">

          <input
            type="text"
            name="color"
            id="color_hex"
            value="{{ $routeColor }}"
            class="form-control @error('color') is-invalid @enderror"
            placeholder="HEX sin #">
        </div>

        @error('color')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- TEXT COLOR --}}
      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Color de texto
        </label>

        <div class="d-flex align-items-center gap-2">
          <input
            type="color"
            id="text_color_picker"
            class="form-control form-control-color"
            value="#{{ $textColor }}"
            title="Selecciona color de texto">

          <input
            type="text"
            name="text_color"
            id="text_color_hex"
            value="{{ $textColor }}"
            class="form-control @error('text_color') is-invalid @enderror"
            placeholder="HEX sin #">
        </div>

        @error('text_color')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- PREVIEW --}}
      <div class="col-12 col-lg-12">
        <div class="p-3 rounded-3 border"
             style="background:#{{ $routeColor }}; color:#{{ $textColor }};">
          <div class="fw-bold">Preview</div>
          <div class="small">{{ $route->short_name }} — {{ $route->long_name }}</div>
        </div>
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
          placeholder="Encoded polyline de Google Maps">{{ old('polyline', $route->polyline) }}</textarea>

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
          rows="6"
          class="form-control @error('stops_json') is-invalid @enderror"
          placeholder='[{"lat":19.70,"lng":-101.19,"name":"Parada 1"}]'>{{ $stopsVal }}</textarea>

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
    const bind = (pickerId, hexId, previewSelector) => {
      const picker = document.getElementById(pickerId);
      const hex = document.getElementById(hexId);
      const preview = previewSelector ? document.querySelector(previewSelector) : null;
      if (!picker || !hex) return;

      const normalize = (v) => (v || '').replace('#','').toUpperCase();

      const syncPreview = () => {
        if (!preview) return;
        const c = normalize(document.getElementById('color_hex')?.value || '');
        const t = normalize(document.getElementById('text_color_hex')?.value || '');
        if (/^[0-9A-F]{6}$/.test(c)) preview.style.background = '#' + c;
        if (/^[0-9A-F]{6}$/.test(t)) preview.style.color = '#' + t;
      };

      picker.addEventListener('input', () => {
        hex.value = normalize(picker.value);
        syncPreview();
      });

      hex.addEventListener('input', () => {
        const v = normalize(hex.value);
        if (/^[0-9A-F]{6}$/.test(v)) {
          picker.value = '#' + v;
          syncPreview();
        }
      });

      syncPreview();
    };

    bind('color_picker','color_hex','.p-3.rounded-3.border');
    bind('text_color_picker','text_color_hex','.p-3.rounded-3.border');
  })();
</script>
@endpush
