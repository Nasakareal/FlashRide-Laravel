@extends('layouts.app')
@section('title','Detalle del conductor')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Conductor</h1>
    <div class="small" style="color:var(--muted);">
      Información completa del conductor
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary px-3">
      <i class="fa-solid fa-arrow-left"></i>
    </a>

    <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-brand px-4">
      <i class="fa-solid fa-pen me-2"></i> Editar
    </a>
  </div>
</div>

@php
  $p = $driver->driverProfile;
  $assign = $driver->activeVehicleAssignment;
  $vehicle = $assign?->vehicle;
@endphp

<div class="row g-4">

  {{-- ======================
      CUENTA
  ====================== --}}
  <div class="col-12 col-lg-6">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <div class="fw-bold mb-2">Cuenta</div>

        <dl class="row mb-0">
          <dt class="col-5 text-muted">Nombre</dt>
          <dd class="col-7">{{ $driver->name }}</dd>

          <dt class="col-5 text-muted">Correo</dt>
          <dd class="col-7">{{ $driver->email }}</dd>

          <dt class="col-5 text-muted">Teléfono</dt>
          <dd class="col-7">{{ $driver->phone }}</dd>

          <dt class="col-5 text-muted">Rol</dt>
          <dd class="col-7">
            <span class="badge bg-secondary">Driver</span>
          </dd>

          <dt class="col-5 text-muted">En línea</dt>
          <dd class="col-7">
            @if($driver->is_online)
              <span class="badge bg-success">Sí</span>
            @else
              <span class="badge bg-secondary">No</span>
            @endif
          </dd>

          <dt class="col-5 text-muted">Creado</dt>
          <dd class="col-7">{{ $driver->created_at->format('d/m/Y H:i') }}</dd>
        </dl>
      </div>
    </div>
  </div>

  {{-- ======================
      EXPEDIENTE
  ====================== --}}
  <div class="col-12 col-lg-6">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <div class="fw-bold mb-2">Expediente</div>

        @if(!$p)
          <div class="text-muted small">
            Este conductor aún no tiene expediente registrado.
          </div>
        @else
          <dl class="row mb-0">
            <dt class="col-5 text-muted">Licencia</dt>
            <dd class="col-7">{{ $p->license_number ?? '—' }}</dd>

            <dt class="col-5 text-muted">Vigencia</dt>
            <dd class="col-7">
              {{ $p->license_expires_at?->format('d/m/Y') ?? '—' }}
            </dd>

            <dt class="col-5 text-muted">CURP</dt>
            <dd class="col-7">{{ $p->curp ?? '—' }}</dd>

            <dt class="col-5 text-muted">RFC</dt>
            <dd class="col-7">{{ $p->rfc ?? '—' }}</dd>

            <dt class="col-5 text-muted">Nacimiento</dt>
            <dd class="col-7">
              {{ $p->birthdate?->format('d/m/Y') ?? '—' }}
            </dd>

            <dt class="col-5 text-muted">Verificado</dt>
            <dd class="col-7">
              @if($p->is_verified)
                <span class="badge bg-success">Sí</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </dd>

            @if($p->verified_at)
              <dt class="col-5 text-muted">Fecha verificación</dt>
              <dd class="col-7">
                {{ $p->verified_at->format('d/m/Y H:i') }}
              </dd>
            @endif
          </dl>
        @endif
      </div>
    </div>
  </div>

  {{-- ======================
      VEHÍCULO ACTIVO
  ====================== --}}
  <div class="col-12">
    <div class="card-soft">
      <div class="p-3 p-lg-4">
        <div class="fw-bold mb-2">Vehículo activo</div>

        @if(!$vehicle)
          <div class="text-muted small">
            El conductor no tiene un vehículo asignado actualmente.
          </div>
        @else
          <dl class="row mb-0">
            <dt class="col-4 col-lg-2 text-muted">Vehículo</dt>
            <dd class="col-8 col-lg-10">
              {{ $vehicle->brand ?? '' }}
              {{ $vehicle->model ?? '' }}
              {{ $vehicle->year ?? '' }}
            </dd>

            <dt class="col-4 col-lg-2 text-muted">Placas</dt>
            <dd class="col-8 col-lg-10">
              {{ $vehicle->plates ?? '—' }}
            </dd>

            <dt class="col-4 col-lg-2 text-muted">Asignado desde</dt>
            <dd class="col-8 col-lg-10">
              {{ $assign->started_at?->format('d/m/Y H:i') ?? '—' }}
            </dd>
          </dl>
        @endif
      </div>
    </div>
  </div>

</div>

@endsection
