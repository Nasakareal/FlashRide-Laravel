{{-- resources/views/admin/tarjetas_teleferico/_form.blade.php --}}

@php
  $tarjetaTeleferico = $tarjetaTeleferico ?? null;
  $estatusValue = old('estatus', $tarjetaTeleferico ? $tarjetaTeleferico->estatus : '');
@endphp

<div class="row g-3">

  <div class="col-12">
    <div class="fw-bold mb-1">Datos del titular</div>
    <div class="small text-muted">Información de la persona</div>
    <hr class="mt-2 mb-0">
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label small text-muted">Nombres</label>
    <input name="nombres"
           value="{{ old('nombres', $tarjetaTeleferico ? $tarjetaTeleferico->nombres : '') }}"
           class="form-control @error('nombres') is-invalid @enderror"
           required>
    @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label small text-muted">Apellidos</label>
    <input name="apellidos"
           value="{{ old('apellidos', $tarjetaTeleferico ? $tarjetaTeleferico->apellidos : '') }}"
           class="form-control @error('apellidos') is-invalid @enderror"
           required>
    @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label small text-muted">CURP</label>
    <input name="curp"
           maxlength="18"
           value="{{ old('curp', $tarjetaTeleferico ? $tarjetaTeleferico->curp : '') }}"
           class="form-control @error('curp') is-invalid @enderror"
           required>
    @error('curp') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label small text-muted">Celular</label>
    <input name="celular"
           value="{{ old('celular', $tarjetaTeleferico ? $tarjetaTeleferico->celular : '') }}"
           class="form-control @error('celular') is-invalid @enderror">
    @error('celular') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12 mt-2">
    <div class="fw-bold mb-1">Tarjeta</div>
    <div class="small text-muted">Datos de control</div>
    <hr class="mt-2 mb-0">
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label small text-muted">Folio de tarjeta</label>
    <input name="folio_tarjeta"
           value="{{ old('folio_tarjeta', $tarjetaTeleferico ? $tarjetaTeleferico->folio_tarjeta : '') }}"
           class="form-control @error('folio_tarjeta') is-invalid @enderror"
           required>
    @error('folio_tarjeta') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label small text-muted">Estatus</label>
    <select name="estatus"
            class="form-select @error('estatus') is-invalid @enderror"
            required>
      <option value="">Seleccionar</option>
      <option value="ACTIVA" {{ $estatusValue == 'ACTIVA' ? 'selected' : '' }}>Activa</option>
      <option value="INACTIVA" {{ $estatusValue == 'INACTIVA' ? 'selected' : '' }}>Inactiva</option>
      <option value="CANCELADA" {{ $estatusValue == 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
      <option value="REPOSICION" {{ $estatusValue == 'REPOSICION' ? 'selected' : '' }}>Reposición</option>
    </select>
    @error('estatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label small text-muted">Fecha de entrega</label>
    <input type="date"
           name="fecha_entrega"
           value="{{ old('fecha_entrega', $tarjetaTeleferico && $tarjetaTeleferico->fecha_entrega ? \Carbon\Carbon::parse($tarjetaTeleferico->fecha_entrega)->format('Y-m-d') : '') }}"
           class="form-control @error('fecha_entrega') is-invalid @enderror">
    @error('fecha_entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label small text-muted">Observaciones</label>
    <textarea name="observaciones"
              rows="2"
              class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $tarjetaTeleferico ? $tarjetaTeleferico->observaciones : '') }}</textarea>
    @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

</div>
