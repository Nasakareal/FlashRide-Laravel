@extends('layouts.app')
@section('title', 'Mi perfil')

@section('content')
@php
  $roleNames = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();
  $rolesLabel = $roleNames->isNotEmpty() ? $roleNames->implode(', ') : ($user->role ?? 'Sin rol asignado');
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Mi perfil</h1>
    <div class="small" style="color:var(--muted);">
      Datos básicos de tu cuenta dentro del panel.
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ url('/flashride/dashboard') }}" class="btn btn-outline-secondary px-3">
      <i class="fa-solid fa-arrow-left"></i>
    </a>

    <a href="{{ route('profile.password.edit') }}" class="btn btn-brand px-4">
      <i class="fa-solid fa-key me-2"></i> Cambiar contraseña
    </a>
  </div>
</div>

<div class="card-soft">
  <div class="p-3 p-lg-4">
    <div class="row g-4">
      <div class="col-12 col-lg-4">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
               style="width:72px; height:72px; background:var(--brand); font-size:1.5rem;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>

          <div>
            <div class="fw-semibold fs-5">{{ $user->name }}</div>
            <div class="small text-muted">Usuario #{{ $user->id }}</div>
          </div>
        </div>

        <div class="small text-muted mb-1">Roles</div>
        <div class="fw-semibold text-uppercase">{{ $rolesLabel }}</div>
      </div>

      <div class="col-12 col-lg-8">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <div class="small text-muted mb-1">Correo electrónico</div>
            <div class="fw-semibold">
              <i class="fa-solid fa-envelope me-2 text-muted"></i>
              {{ $user->email }}
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="small text-muted mb-1">Teléfono</div>
            <div class="fw-semibold">
              <i class="fa-solid fa-phone me-2 text-muted"></i>
              {{ $user->phone ?? 'No registrado' }}
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="small text-muted mb-1">Miembro desde</div>
            <div class="fw-semibold">
              <i class="fa-solid fa-calendar-day me-2 text-muted"></i>
              {{ optional($user->created_at)->format('d/m/Y H:i') }}
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="small text-muted mb-1">Última actualización</div>
            <div class="fw-semibold">
              <i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i>
              {{ optional($user->updated_at)->format('d/m/Y H:i') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
