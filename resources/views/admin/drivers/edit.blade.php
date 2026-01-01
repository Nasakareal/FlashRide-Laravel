@extends('layouts.app')
@section('title','Editar conductor')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Editar conductor</h1>
    <div class="small" style="color:var(--muted);">
      Actualiza la cuenta y el expediente del conductor
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary px-3">
      <i class="fa-solid fa-arrow-left"></i>
    </a>

    <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-outline-secondary px-4">
      <i class="fa-regular fa-eye me-2"></i> Ver
    </a>
  </div>
</div>

@php
  $p = $driver->driverProfile; // puede ser null
@endphp

{{-- CARD --}}
<div class="card-soft">
  <div class="p-3 p-lg-4">

    <form method="POST" action="{{ route('admin.drivers.update', $driver) }}" class="row g-3">
      @csrf
      @method('PUT')

      {{-- =========================
          CUENTA (users)
      ========================= --}}

      <div class="col-12">
        <div class="fw-bold mb-1">Cuenta</div>
        <div class="small text-muted">Datos para iniciar sesión</div>
        <hr class="mt-2 mb-0">
      </div>

      {{-- NOMBRE --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Nombre completo
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
          <input
            name="name"
            value="{{ old('name', $driver->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            required>
        </div>
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- EMAIL --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Correo electrónico
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
          <input
            name="email"
            type="email"
            value="{{ old('email', $driver->email) }}"
            class="form-control @error('email') is-invalid @enderror"
            required>
        </div>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- PHONE --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Teléfono
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
          <input
            name="phone"
            value="{{ old('phone', $driver->phone) }}"
            class="form-control @error('phone') is-invalid @enderror"
            required>
        </div>
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- PASSWORD (opcional) --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Nueva contraseña (opcional)
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
          <input
            id="password"
            name="password"
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Dejar vacío si no deseas cambiarla"
            autocomplete="new-password">
        </div>

        {{-- Reglas en vivo --}}
        <div class="form-text mt-2">
          Si la cambias, debe contener:
          <ul class="mb-0 ps-0" id="pwRules" style="list-style:none;">
            <li id="rule-len" class="pw-rule">
              <span class="pw-icon"><i class="fa-regular fa-circle"></i></span>
              <span>Mínimo 8 caracteres</span>
            </li>
            <li id="rule-upper" class="pw-rule">
              <span class="pw-icon"><i class="fa-regular fa-circle"></i></span>
              <span>Al menos una letra mayúscula</span>
            </li>
            <li id="rule-num" class="pw-rule">
              <span class="pw-icon"><i class="fa-regular fa-circle"></i></span>
              <span>Al menos un número</span>
            </li>
            <li id="rule-sym" class="pw-rule">
              <span class="pw-icon"><i class="fa-regular fa-circle"></i></span>
              <span>Al menos un símbolo (ej. ! @ # $ %)</span>
            </li>
          </ul>
        </div>

        @error('password')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- =========================
          EXPEDIENTE (drivers)
      ========================= --}}

      <div class="col-12 mt-2">
        <div class="fw-bold mb-1">Expediente</div>
        <div class="small text-muted">Datos adicionales del conductor</div>
        <hr class="mt-2 mb-0">
      </div>

      {{-- LICENCIA --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">Número de licencia</label>
        <input
          name="license_number"
          value="{{ old('license_number', $p->license_number ?? '') }}"
          class="form-control @error('license_number') is-invalid @enderror"
          placeholder="Ej. A12345678">
        @error('license_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">Vigencia de licencia</label>
        <input
          type="date"
          name="license_expires_at"
          value="{{ old('license_expires_at', optional($p?->license_expires_at)->format('Y-m-d')) }}"
          class="form-control @error('license_expires_at') is-invalid @enderror">
        @error('license_expires_at')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- CURP / RFC --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">CURP</label>
        <input
          name="curp"
          value="{{ old('curp', $p->curp ?? '') }}"
          class="form-control @error('curp') is-invalid @enderror"
          maxlength="18">
        @error('curp')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">RFC</label>
        <input
          name="rfc"
          value="{{ old('rfc', $p->rfc ?? '') }}"
          class="form-control @error('rfc') is-invalid @enderror"
          maxlength="13">
        @error('rfc')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- NACIMIENTO --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">Fecha de nacimiento</label>
        <input
          type="date"
          name="birthdate"
          value="{{ old('birthdate', optional($p?->birthdate)->format('Y-m-d')) }}"
          class="form-control @error('birthdate') is-invalid @enderror">
        @error('birthdate')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- VERIFICADO --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">Verificación</label>
        @php
          $oldVerified = old('is_verified');
          $currentVerified = isset($oldVerified)
            ? $oldVerified
            : (isset($p) ? (string)($p->is_verified ? 1 : 0) : '');
        @endphp

        <select name="is_verified" class="form-select @error('is_verified') is-invalid @enderror">
          <option value="" @selected($currentVerified==='')>Sin definir</option>
          <option value="1" @selected($currentVerified==='1')>Verificado</option>
          <option value="0" @selected($currentVerified==='0')>No verificado</option>
        </select>

        @error('is_verified')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        @if($p && $p->verified_at)
          <div class="small text-muted mt-1">
            Verificado el: <b>{{ $p->verified_at->format('d/m/Y H:i') }}</b>
          </div>
        @endif
      </div>

      {{-- DIRECCIÓN --}}
      <div class="col-12">
        <label class="form-label small mb-1 text-muted">Dirección</label>
        <textarea
          name="address"
          rows="2"
          class="form-control @error('address') is-invalid @enderror"
        >{{ old('address', $p->address ?? '') }}</textarea>
        @error('address')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- NOTAS --}}
      <div class="col-12">
        <label class="form-label small mb-1 text-muted">Notas internas</label>
        <textarea
          name="notes"
          rows="2"
          class="form-control @error('notes') is-invalid @enderror"
        >{{ old('notes', $p->notes ?? '') }}</textarea>
        @error('notes')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- ACTIONS --}}
      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-outline-secondary px-4">
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

@push('styles')
<style>
  .pw-rule{
    display:flex;
    align-items:center;
    gap:.5rem;
    margin:.25rem 0;
    color:#6B7280;
    transition: all .15s ease;
  }
  .pw-rule .pw-icon{
    width:18px;
    display:inline-flex;
    justify-content:center;
  }
  .pw-rule.is-ok{
    color:#16a34a;
    font-weight:600;
  }
  .pw-rule.is-ok .pw-icon i{
    color:#16a34a;
  }
</style>
@endpush

@push('scripts')
<script>
(function () {
  const input = document.getElementById('password');
  if (!input) return;

  const ruleLen   = document.getElementById('rule-len');
  const ruleUpper = document.getElementById('rule-upper');
  const ruleNum   = document.getElementById('rule-num');
  const ruleSym   = document.getElementById('rule-sym');

  function setRule(el, ok) {
    if (!el) return;
    const icon = el.querySelector('.pw-icon i');

    if (!input.value) ok = false;

    if (ok) {
      el.classList.add('is-ok');
      if (icon) icon.className = 'fa-solid fa-circle-check';
    } else {
      el.classList.remove('is-ok');
      if (icon) icon.className = 'fa-regular fa-circle';
    }
  }

  function check() {
    const v = input.value || '';
    if (!v) {
      setRule(ruleLen, false);
      setRule(ruleUpper, false);
      setRule(ruleNum, false);
      setRule(ruleSym, false);
      return;
    }

    setRule(ruleLen,   v.length >= 8);
    setRule(ruleUpper, /[A-Z]/.test(v));
    setRule(ruleNum,   /[0-9]/.test(v));
    setRule(ruleSym,   /[^A-Za-z0-9\s_]/.test(v));
  }

  input.addEventListener('input', check);
  input.addEventListener('blur', check);
  check();
})();
</script>
@endpush
