@extends('layouts.app')
@section('title','Nuevo conductor')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Nuevo conductor</h1>
    <div class="small" style="color:var(--muted);">
      Crear cuenta de conductor y su expediente
    </div>
  </div>

  <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary px-4">
    <i class="fa-solid fa-arrow-left me-2"></i> Volver
  </a>
</div>

{{-- CARD --}}
<div class="card-soft">
  <div class="p-3 p-lg-4">

    <form method="POST" action="{{ route('admin.drivers.store') }}" class="row g-3">
      @csrf

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
            value="{{ old('name') }}"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Nombre del conductor"
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
            value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="correo@ejemplo.com"
            required>
        </div>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- PHONE (REQUIRED porque tu DB no deja null) --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Teléfono
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
          <input
            name="phone"
            value="{{ old('phone') }}"
            class="form-control @error('phone') is-invalid @enderror"
            placeholder="10 dígitos"
            required>
        </div>
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- PASSWORD --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Contraseña
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
          <input
            id="password"
            name="password"
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Contraseña segura"
            autocomplete="new-password"
            required>
        </div>

        {{-- Reglas en vivo --}}
        <div class="form-text mt-2">
          La contraseña debe contener:
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
        <div class="small text-muted">Datos adicionales del conductor (opcional por ahora)</div>
        <hr class="mt-2 mb-0">
      </div>

      {{-- LICENCIA --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">Número de licencia</label>
        <input
          name="license_number"
          value="{{ old('license_number') }}"
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
          value="{{ old('license_expires_at') }}"
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
          value="{{ old('curp') }}"
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
          value="{{ old('rfc') }}"
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
          value="{{ old('birthdate') }}"
          class="form-control @error('birthdate') is-invalid @enderror">
        @error('birthdate')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- VERIFICADO --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1 text-muted">Verificación</label>
        <select name="is_verified" class="form-select @error('is_verified') is-invalid @enderror">
          <option value="" @selected(old('is_verified')==='')>Sin definir</option>
          <option value="1" @selected(old('is_verified')==='1')>Verificado</option>
          <option value="0" @selected(old('is_verified')==='0')>No verificado</option>
        </select>
        @error('is_verified')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- DIRECCIÓN --}}
      <div class="col-12">
        <label class="form-label small mb-1 text-muted">Dirección</label>
        <textarea
          name="address"
          rows="2"
          class="form-control @error('address') is-invalid @enderror"
        >{{ old('address') }}</textarea>
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
        >{{ old('notes') }}</textarea>
        @error('notes')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- ACTIONS --}}
      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>

        <button class="btn btn-brand px-4">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar conductor
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
