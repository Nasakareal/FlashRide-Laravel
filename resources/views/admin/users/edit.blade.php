@extends('layouts.app')
@section('title','Editar usuario')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Editar usuario</h1>
    <div class="small" style="color:var(--muted);">
      Actualizar información y rol del usuario
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-3">
      <i class="fa-solid fa-arrow-left"></i>
    </a>

    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary px-4">
      <i class="fa-regular fa-eye me-2"></i> Ver
    </a>
  </div>
</div>

{{-- CARD --}}
<div class="card-soft">
  <div class="p-3 p-lg-4">

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="row g-3">
      @csrf
      @method('PUT')

      {{-- NOMBRE --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Nombre completo
        </label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-user"></i>
          </span>
          <input
            name="name"
            value="{{ old('name', $user->name) }}"
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
          <span class="input-group-text">
            <i class="fa-solid fa-envelope"></i>
          </span>
          <input
            name="email"
            type="email"
            value="{{ old('email', $user->email) }}"
            class="form-control @error('email') is-invalid @enderror"
            required>
        </div>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- PHONE (OBLIGATORIO POR BD) --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Teléfono
        </label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-phone"></i>
          </span>
          <input
            name="phone"
            value="{{ old('phone', $user->phone) }}"
            class="form-control @error('phone') is-invalid @enderror"
            placeholder="Ej. 4431234567"
            required>
        </div>
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- PASSWORD (OPCIONAL) --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Nueva contraseña (opcional)
        </label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-lock"></i>
          </span>
          <input
            id="password"
            name="password"
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Dejar vacío si no desea cambiarla"
            autocomplete="new-password">
        </div>

        {{-- Reglas visibles (con estado en vivo) --}}
        <div class="form-text mt-2">
          Si deseas cambiarla, debe contener:
          <ul class="mb-0 ps-3" id="pwRules" style="list-style:none; padding-left:0;">
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

      {{-- ROL --}}
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">
          Rol del usuario
        </label>

        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
          @php
            $rolesList = isset($roles) && !empty($roles) ? $roles : ['admin','driver','passenger'];
            $selectedRole = old('role');
          @endphp

          @foreach($rolesList as $r)
            @php
              $isSelected = $selectedRole
                ? ($selectedRole === $r)
                : (method_exists($user,'hasRole') ? $user->hasRole($r) : (($user->role ?? null) === $r));
            @endphp

            <option value="{{ $r }}" @selected($isSelected)>{{ ucfirst($r) }}</option>
          @endforeach
        </select>

        <div class="small text-muted mt-1">
          Cambiar el rol modifica los permisos del sistema
        </div>

        @error('role')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- ACTIONS --}}
      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>

        <button class="btn btn-brand px-4">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Actualizar usuario
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

    function setNeutral() {
      setRule(ruleLen, false);
      setRule(ruleUpper, false);
      setRule(ruleNum, false);
      setRule(ruleSym, false);
    }

    function check() {
      const v = input.value || '';

      if (v.length === 0) {
        setNeutral();
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
