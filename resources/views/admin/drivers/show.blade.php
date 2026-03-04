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

  $assign = $p?->activeVehicleAssignment;
  $vehicle = $assign?->vehicle;

  // Documentos activos (si ya los cargaste en el controller con ->load)
  $docs = $p?->documents ?? collect();

  $typeLabels = [
    'TITULO_CONCESION'        => 'Título de concesión',
    'TARJETA_CIRCULACION'     => 'Tarjeta de circulación',
    'POLIZA_SEGURO'           => 'Póliza de seguro',
    'DICTAMEN_FISICO_MECANICO'=> 'Dictamen físico-mecánico',
    'MANIFESTACION_PROTESTA'  => 'Manifestación bajo protesta',
    'TARJETA_CONTROL'         => 'Tarjeta de control',
    'LICENCIA_CONDUCIR'       => 'Licencia de conducir (PDF)',
    'INE_FRENTE'              => 'INE (frente)',
    'INE_REVERSO'             => 'INE (reverso / referencia)',
    'COMPROBANTE_DOMICILIO'   => 'Comprobante de domicilio',
  ];

  $requiredTypes = array_keys($typeLabels);

  // Para mostrar "faltantes"
  $hasActive = [];
  foreach($docs as $d){
    if(!empty($d->type) && (int)$d->is_active === 1){
      $hasActive[$d->type] = true;
    }
  }
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
            <dt class="col-5 text-muted">Nombre conforme INE</dt>
            <dd class="col-7">{{ $p->full_name_ine ?? '—' }}</dd>

            <dt class="col-5 text-muted">Lugar de nacimiento</dt>
            <dd class="col-7">{{ $p->birth_place ?? '—' }}</dd>

            <dt class="col-5 text-muted">Madre</dt>
            <dd class="col-7">{{ $p->mother_full_name ?? '—' }}</dd>

            <dt class="col-5 text-muted">Padre</dt>
            <dd class="col-7">{{ $p->father_full_name ?? '—' }}</dd>

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

            <dt class="col-5 text-muted">Domicilio</dt>
            <dd class="col-7">{{ $p->address ?? '—' }}</dd>

            <dt class="col-5 text-muted">Referencia</dt>
            <dd class="col-7">{{ $p->reference ?? '—' }}</dd>

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
      DOCUMENTOS (PDF)
  ====================== --}}
  <div class="col-12">
    <div class="card-soft">
      <div class="p-3 p-lg-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="fw-bold">Documentos (PDF)</div>
          <div class="small text-muted">
            Sube los documentos requeridos para el alta del chofer
          </div>
        </div>

        @if(!$p)
          <div class="text-muted small">
            Aún no hay expediente. Primero guarda el conductor (o entra a editar) para crear el perfil.
          </div>
        @else

          {{-- Subir --}}
          <form class="row g-2 align-items-end mb-3"
                action="{{ route('admin.drivers.documents.store', $driver) }}"
                method="POST"
                enctype="multipart/form-data">
            @csrf

            <div class="col-12 col-lg-4">
              <label class="form-label small text-muted mb-1">Tipo</label>
              <select name="type" class="form-select">
                @foreach($typeLabels as $k => $label)
                  <option value="{{ $k }}">{{ $label }}</option>
                @endforeach
              </select>
              @error('type')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-lg-5">
              <label class="form-label small text-muted mb-1">Archivo PDF</label>
              <input type="file" name="file" accept="application/pdf" class="form-control">
              @error('file')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-lg-3">
              <button class="btn btn-brand w-100">
                <i class="fa-solid fa-upload me-2"></i> Subir
              </button>
            </div>
          </form>

          {{-- Faltantes --}}
          <div class="mb-3">
            <div class="small text-muted mb-1">Estado rápido</div>
            <div class="d-flex flex-wrap gap-2">
              @foreach($requiredTypes as $t)
                @if(!empty($hasActive[$t]))
                  <span class="badge bg-success">{{ $typeLabels[$t] ?? $t }}</span>
                @else
                  <span class="badge bg-secondary">{{ $typeLabels[$t] ?? $t }}</span>
                @endif
              @endforeach
            </div>
          </div>

          {{-- Lista --}}
          @if($docs->isEmpty())
            <div class="text-muted small">
              Aún no hay documentos subidos.
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Tipo</th>
                    <th>Archivo</th>
                    <th>Subido</th>
                    <th class="text-end">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($docs as $d)
                    <tr>
                      <td class="fw-semibold">
                        {{ $typeLabels[$d->type] ?? $d->type }}
                      </td>
                      <td class="small text-muted">
                        {{ $d->original_name ?? 'PDF' }}
                      </td>
                      <td class="small text-muted">
                        {{ $d->uploaded_at?->format('d/m/Y H:i') ?? ($d->created_at?->format('d/m/Y H:i') ?? '—') }}
                      </td>
                      <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm"
                           href="{{ route('admin.drivers.documents.download', [$driver, $d]) }}">
                          <i class="fa-solid fa-download"></i>
                        </a>

                        <form action="{{ route('admin.drivers.documents.destroy', [$driver, $d]) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar este documento?');">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-outline-danger btn-sm">
                            <i class="fa-solid fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif

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
              {{ $assign?->started_at?->format('d/m/Y H:i') ?? '—' }}
            </dd>
          </dl>
        @endif
      </div>
    </div>
  </div>

</div>

@endsection
