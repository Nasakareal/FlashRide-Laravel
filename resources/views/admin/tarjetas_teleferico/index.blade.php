@extends('layouts.app')
@section('title','Tarjetas Teleférico')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Tarjetas Teleférico</h1>
    <div class="small" style="color:var(--muted);">
      Control y registro de tarjetas entregadas
    </div>
  </div>

  <a href="{{ route('admin.tarjetas-teleferico.create') }}" class="btn btn-brand px-4">
    <i class="fa-solid fa-plus me-2"></i> Nueva tarjeta
  </a>
</div>

<div class="card-soft mb-4">
  <div class="p-3 p-lg-4">
    <form method="GET" action="{{ route('admin.tarjetas-teleferico.index') }}" class="row g-3 align-items-end">
      <div class="col-12 col-lg-6">
        <label class="form-label small mb-1" style="color:var(--muted);">Buscar</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input
            name="q"
            value="{{ $q ?? request('q') }}"
            class="form-control"
            placeholder="Nombre, CURP, celular o folio...">
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">Estatus</label>
        @php $e = (string)($estatus ?? request('estatus','')); @endphp
        <select name="estatus" class="form-select">
          <option value="" {{ $e === '' ? 'selected' : '' }}>Todos</option>
          <option value="ACTIVA" {{ $e === 'ACTIVA' ? 'selected' : '' }}>Activa</option>
          <option value="INACTIVA" {{ $e === 'INACTIVA' ? 'selected' : '' }}>Inactiva</option>
          <option value="CANCELADA" {{ $e === 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
          <option value="REPOSICION" {{ $e === 'REPOSICION' ? 'selected' : '' }}>Reposición</option>
        </select>
      </div>

      <div class="col-12 col-lg-3 d-flex gap-2">
        <button class="btn btn-brand px-4 w-100">
          <i class="fa-solid fa-filter me-2"></i> Filtrar
        </button>
        <a href="{{ route('admin.tarjetas-teleferico.index') }}" class="btn btn-outline-secondary px-4">
          Limpiar
        </a>
      </div>
    </form>
  </div>
</div>

<div class="card-soft">
  <div class="p-0 overflow-auto">
    <table class="table mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th class="ps-3">ID</th>
          <th>Nombre</th>
          <th>CURP</th>
          <th>Celular</th>
          <th>Folio</th>
          <th>Estatus</th>
          <th>Entrega</th>
          <th class="text-end pe-3">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($tarjetas as $t)
          <tr>
            <td class="ps-3">{{ $t->id }}</td>

            <td>
              <div class="fw-bold">{{ $t->nombres }} {{ $t->apellidos }}</div>
            </td>

            <td>{{ $t->curp }}</td>

            <td>{{ $t->celular ?? '—' }}</td>

            <td>
              <span class="fw-bold">{{ $t->folio_tarjeta }}</span>
            </td>

            <td>
              @switch($t->estatus)
                @case('ACTIVA')
                  <span class="badge text-bg-success">Activa</span>
                  @break
                @case('INACTIVA')
                  <span class="badge text-bg-secondary">Inactiva</span>
                  @break
                @case('CANCELADA')
                  <span class="badge text-bg-danger">Cancelada</span>
                  @break
                @case('REPOSICION')
                  <span class="badge text-bg-warning">Reposición</span>
                  @break
                @default
                  <span class="badge text-bg-light">—</span>
              @endswitch
            </td>

            <td>
              {{ $t->fecha_entrega ? \Carbon\Carbon::parse($t->fecha_entrega)->format('d/m/Y') : '—' }}
            </td>

            <td class="text-end pe-3">
              <div class="d-inline-flex gap-2">
                <a href="{{ route('admin.tarjetas-teleferico.show', $t) }}" class="btn btn-outline-info btn-sm">
                  <i class="fa-regular fa-eye"></i>
                </a>

                <a href="{{ route('admin.tarjetas-teleferico.edit', $t) }}" class="btn btn-outline-success btn-sm">
                  <i class="fa-solid fa-pen"></i>
                </a>

                <form action="{{ route('admin.tarjetas-teleferico.destroy', $t) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar esta tarjeta?')">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="p-4 text-center text-muted">
              Sin tarjetas registradas.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($tarjetas->hasPages())
  <div class="mt-4 d-flex justify-content-end">
    {{ $tarjetas->links('vendor.pagination.bootstrap-4') }}
  </div>
@endif

@endsection
