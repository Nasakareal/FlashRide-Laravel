@extends('layouts.app')
@section('title','Detalle de tarjeta teleférico')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Detalle de tarjeta teleférico</h1>
    <div class="small" style="color:var(--muted);">
      Información completa del registro
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.tarjetas-teleferico.index') }}" class="btn btn-outline-secondary px-4">
      <i class="fa-solid fa-arrow-left me-2"></i> Volver
    </a>

    <a href="{{ route('admin.tarjetas-teleferico.edit', $tarjetaTeleferico->id) }}" class="btn btn-outline-primary px-4">
      <i class="fa-solid fa-pen me-2"></i> Editar
    </a>
  </div>
</div>

<div class="card-soft">
  <div class="p-3 p-lg-4">

    <div class="row g-4">

      <div class="col-12">
        <div class="fw-bold mb-1">Datos del titular</div>
        <hr class="mt-2 mb-0">
      </div>

      <div class="col-12 col-lg-6">
        <div class="small text-muted">Nombres</div>
        <div class="fw-semibold">{{ $tarjetaTeleferico->nombres }}</div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="small text-muted">Apellidos</div>
        <div class="fw-semibold">{{ $tarjetaTeleferico->apellidos }}</div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="small text-muted">CURP</div>
        <div class="fw-semibold">{{ $tarjetaTeleferico->curp }}</div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="small text-muted">Celular</div>
        <div class="fw-semibold">{{ $tarjetaTeleferico->celular ?? '—' }}</div>
      </div>

      <div class="col-12 mt-2">
        <div class="fw-bold mb-1">Tarjeta</div>
        <hr class="mt-2 mb-0">
      </div>

      <div class="col-12 col-lg-6">
        <div class="small text-muted">Folio de tarjeta</div>
        <div class="fw-semibold">{{ $tarjetaTeleferico->folio_tarjeta }}</div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="small text-muted">Estatus</div>
        <div class="fw-semibold">
          @switch($tarjetaTeleferico->estatus)
            @case('ACTIVA') Activa @break
            @case('INACTIVA') Inactiva @break
            @case('CANCELADA') Cancelada @break
            @case('REPOSICION') Reposición @break
            @default —
          @endswitch
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="small text-muted">Fecha de entrega</div>
        <div class="fw-semibold">
          {{ $tarjetaTeleferico->fecha_entrega ? \Carbon\Carbon::parse($tarjetaTeleferico->fecha_entrega)->format('d/m/Y') : '—' }}
        </div>
      </div>

      <div class="col-12">
        <div class="small text-muted">Observaciones</div>
        <div class="fw-semibold">
          {{ $tarjetaTeleferico->observaciones ?: '—' }}
        </div>
      </div>

    </div>

  </div>
</div>

@endsection
