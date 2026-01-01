@extends('layouts.app')
@section('title','Detalle de usuario')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Detalle del usuario</h1>
    <div class="small" style="color:var(--muted);">
      Información completa de la cuenta
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-3">
      <i class="fa-solid fa-arrow-left"></i>
    </a>

    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-success px-4">
      <i class="fa-regular fa-pen-to-square me-2"></i> Editar
    </a>
  </div>
</div>

{{-- CARD --}}
<div class="card-soft mb-3">
  <div class="p-3 p-lg-4">

    <div class="row g-4">

      {{-- AVATAR / INFO --}}
      <div class="col-12 col-lg-4">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="avatar-circle bg-brand text-white">
            {{ strtoupper(substr($user->name,0,1)) }}
          </div>

          <div>
            <div class="fw-semibold fs-5">{{ $user->name }}</div>
            <div class="small text-muted">ID #{{ $user->id }}</div>
          </div>
        </div>

        {{-- ROLES --}}
        <div class="mb-2">
          @php
            $roleNames = method_exists($user, 'getRoleNames')
              ? $user->getRoleNames()
              : collect();

            $legacyRole = $user->role ?? null;
          @endphp

          @if($roleNames->isNotEmpty())
            @foreach($roleNames as $r)
              @php
                $badge = match($r) {
                  'admin'     => 'bg-danger',
                  'driver'    => 'bg-primary',
                  'passenger' => 'bg-secondary',
                  default     => 'bg-dark'
                };
              @endphp
              <span class="badge {{ $badge }} me-1 text-uppercase">{{ $r }}</span>
            @endforeach
          @elseif($legacyRole)
            @php
              $badge = match($legacyRole) {
                'admin'     => 'bg-danger',
                'driver'    => 'bg-primary',
                'passenger' => 'bg-secondary',
                default     => 'bg-dark'
              };
            @endphp
            <span class="badge {{ $badge }} text-uppercase">{{ $legacyRole }}</span>
          @else
            <span class="badge bg-light text-dark">sin rol</span>
          @endif
        </div>
      </div>

      {{-- DATOS --}}
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
            <div class="small text-muted mb-1">Fecha de registro</div>
            <div class="fw-semibold">
              <i class="fa-solid fa-calendar-day me-2 text-muted"></i>
              {{ $user->created_at?->format('d/m/Y H:i') }}
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="small text-muted mb-1">Última actualización</div>
            <div class="fw-semibold">
              <i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i>
              {{ $user->updated_at?->format('d/m/Y H:i') }}
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>
</div>

{{-- ACTIONS --}}
<div class="d-flex justify-content-end gap-2">
  <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-success px-4">
    <i class="fa-regular fa-pen-to-square me-2"></i> Editar
  </a>

  @if(auth()->id() !== $user->id)
    <form action="{{ route('admin.users.destroy', $user) }}"
          method="POST"
          onsubmit="return confirm('¿Eliminar este usuario?');">
      @csrf
      @method('DELETE')
      <button class="btn btn-outline-danger px-4">
        <i class="fa-regular fa-trash-can me-2"></i> Eliminar
      </button>
    </form>
  @endif
</div>

@endsection
