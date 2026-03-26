@extends('layouts.app')
@section('title', 'Cambiar contrase&ntilde;a')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Cambiar contrase&ntilde;a</h1>
    <div class="small" style="color:var(--muted);">
      Actualiza tu acceso al panel de forma segura.
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary px-4">
      <i class="fa-regular fa-user me-2"></i> Ver perfil
    </a>
  </div>
</div>

<div class="card-soft">
  <div class="p-3 p-lg-4">
    <form method="POST" action="{{ route('profile.password.update') }}" class="row g-3">
      @csrf
      @method('PUT')

      <div class="col-12">
        <label for="current_password" class="form-label small mb-1" style="color:var(--muted);">
          Contrase&ntilde;a actual
        </label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-lock"></i>
          </span>
          <input
            id="current_password"
            name="current_password"
            type="password"
            autocomplete="current-password"
            class="form-control @error('current_password') is-invalid @enderror"
            required>
        </div>
        @error('current_password')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-12 col-lg-6">
        <label for="password" class="form-label small mb-1" style="color:var(--muted);">
          Nueva contrase&ntilde;a
        </label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-key"></i>
          </span>
          <input
            id="password"
            name="password"
            type="password"
            autocomplete="new-password"
            class="form-control @error('password') is-invalid @enderror"
            required>
        </div>
        <div class="form-text">
          Debe tener al menos 8 caracteres y ser diferente a la actual.
        </div>
        @error('password')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-12 col-lg-6">
        <label for="password_confirmation" class="form-label small mb-1" style="color:var(--muted);">
          Confirmar nueva contrase&ntilde;a
        </label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fa-solid fa-shield-halved"></i>
          </span>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            class="form-control"
            required>
        </div>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>

        <button class="btn btn-brand px-4">
          <i class="fa-solid fa-floppy-disk me-2"></i> Guardar nueva contrase&ntilde;a
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
