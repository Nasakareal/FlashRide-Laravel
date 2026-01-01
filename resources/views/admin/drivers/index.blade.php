@extends('layouts.app')
@section('title','Conductores')

@section('content')

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Conductores</h1>
    <div class="small" style="color:var(--muted);">
      Usuarios con rol <b>driver</b> y su expediente
    </div>
  </div>

  <a href="{{ route('admin.drivers.create') }}" class="btn btn-brand px-4">
    <i class="fa-solid fa-plus me-2"></i> Nuevo conductor
  </a>
</div>

{{-- FILTERS --}}
<div class="card-soft mb-4">
  <div class="p-3 p-lg-4">
    <form method="GET" action="{{ route('admin.drivers.index') }}" class="row g-3 align-items-end">
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">Buscar</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input
            name="q"
            value="{{ $q ?? request('q') }}"
            class="form-control"
            placeholder="Nombre, correo, teléfono, licencia, CURP...">
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">Verificación</label>
        <select name="verified" class="form-select">
          @php $v = (string)($verified ?? request('verified', '')); @endphp
          <option value=""  @selected($v==='')>Todos</option>
          <option value="1" @selected($v==='1')>Verificados</option>
          <option value="0" @selected($v==='0')>No verificados</option>
        </select>
      </div>

      <div class="col-12 col-lg-3 d-flex gap-2">
        <button class="btn btn-brand px-4 w-100">
          <i class="fa-solid fa-filter me-2"></i> Filtrar
        </button>
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary px-4">
          Limpiar
        </a>
      </div>
    </form>
  </div>
</div>

{{-- TABLE --}}
<div class="card-soft">
  <div class="p-0 overflow-auto">
    <table class="table mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th class="ps-3">ID</th>
          <th>Conductor</th>
          <th>Teléfono</th>
          <th>Online</th>
          <th>Verificado</th>
          <th>Rating</th>
          <th>Vehículo activo</th>
          <th class="text-end pe-3">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($drivers as $d)
          @php
            $profile = $d->driverProfile;
            $isVerified = (bool) optional($profile)->is_verified;
            $ratingAvg = optional($profile)->rating_avg ?? 0;
            $ratingCount = optional($profile)->rating_count ?? 0;

            $activeAssign = $d->activeVehicleAssignment ?? null;
            $vehicle = optional($activeAssign)->vehicle;
          @endphp

          <tr>
            <td class="ps-3">{{ $d->id }}</td>

            <td>
              <div class="fw-bold">{{ $d->name }}</div>
              <div class="small text-muted">{{ $d->email }}</div>
            </td>

            <td>{{ $d->phone }}</td>

            <td>
              @if($d->is_online)
                <span class="badge text-bg-success">Sí</span>
              @else
                <span class="badge text-bg-secondary">No</span>
              @endif
            </td>

            <td>
              @if($isVerified)
                <span class="badge text-bg-success">
                  <i class="fa-solid fa-circle-check me-1"></i> Sí
                </span>
              @else
                <span class="badge text-bg-warning">
                  <i class="fa-solid fa-triangle-exclamation me-1"></i> No
                </span>
              @endif
            </td>

            <td>
              <div class="fw-bold">{{ number_format((float)$ratingAvg, 2) }}</div>
              <div class="small text-muted">{{ (int)$ratingCount }} calif.</div>
            </td>

            <td>
              @if($vehicle)
                <div class="fw-bold">{{ $vehicle->brand ?? 'Vehículo' }} {{ $vehicle->model ?? '' }}</div>
                <div class="small text-muted">
                  {{ $vehicle->plates ?? '' }}
                </div>
              @else
                <span class="text-muted small">Sin asignación</span>
              @endif
            </td>

            <td class="text-end pe-3">
              <div class="d-inline-flex gap-2">
                <a href="{{ route('admin.drivers.show', $d) }}" class="btn btn-outline-info btn-sm">
                  <i class="fa-regular fa-eye"></i>
                </a>

                <a href="{{ route('admin.drivers.edit', $d) }}" class="btn btn-outline-success btn-sm">
                  <i class="fa-solid fa-pen"></i>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="p-4 text-center text-muted">
              Sin conductores.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($drivers->hasPages())
  <div class="mt-4 d-flex justify-content-end">
    {{ $drivers->links('vendor.pagination.bootstrap-4') }}
  </div>
@endif


@endsection
