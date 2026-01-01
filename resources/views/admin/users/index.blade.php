@extends('layouts.app')
@section('title','Usuarios')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Usuarios</h1>
    <div class="small" style="color:var(--muted);">Administración de cuentas y roles</div>
  </div>

  <a href="{{ route('admin.users.create') }}" class="btn btn-brand px-4">
    <i class="fa-solid fa-user-plus me-2"></i> Nuevo usuario
  </a>
</div>

<div class="card-soft mb-3">
  <div class="p-3 p-lg-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
      <div class="col-12 col-lg-5">
        <label class="form-label small mb-1" style="color:var(--muted);">Buscar</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text"
                 name="q"
                 value="{{ $q ?? request('q') }}"
                 class="form-control"
                 placeholder="Nombre, email o teléfono">
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <label class="form-label small mb-1" style="color:var(--muted);">Rol</label>
        <select name="role" class="form-select">
          <option value="">Todos</option>
          @foreach(($roles ?? []) as $r)
            <option value="{{ $r }}" @selected(($role ?? request('role')) === $r)>{{ $r }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-lg-4 d-flex gap-2">
        <button class="btn btn-outline-secondary w-100" type="submit">
          <i class="fa-solid fa-filter me-2"></i> Filtrar
        </button>

        <a class="btn btn-outline-secondary w-100"
           href="{{ route('admin.users.index') }}">
          <i class="fa-solid fa-rotate-left me-2"></i> Limpiar
        </a>
      </div>
    </form>
  </div>
</div>

<div class="card-soft overflow-hidden">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="ps-4">ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Rol</th>
          <th class="text-end pe-4">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($users as $u)
          <tr>
            <td class="ps-4 fw-semibold">{{ $u->id }}</td>

            <td>
              <div class="fw-semibold">{{ $u->name }}</div>
              @if(!empty($u->phone))
                <div class="small" style="color:var(--muted);">{{ $u->phone }}</div>
              @endif
            </td>

            <td class="text-muted">{{ $u->email }}</td>

            <td>
              @php
                $roleNames = method_exists($u, 'getRoleNames') ? $u->getRoleNames() : collect();
                $legacyRole = $u->role ?? null;
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
            </td>

            <td class="text-end pe-4">
              <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('admin.users.show', $u) }}"
                   class="btn btn-outline-info"
                   title="Ver">
                  <i class="fa-regular fa-eye"></i>
                </a>

                <a href="{{ route('admin.users.edit', $u) }}"
                   class="btn btn-outline-success"
                   title="Editar">
                  <i class="fa-regular fa-pen-to-square"></i>
                </a>

                @if(auth()->id() !== $u->id)
                  <form action="{{ route('admin.users.destroy', $u) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('¿Eliminar este usuario?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" title="Eliminar">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center py-4 text-muted">
              No hay usuarios registrados.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($users->hasPages())
  <div class="mt-4 d-flex justify-content-end">
    {{ $users->links('vendor.pagination.bootstrap-4') }}

  </div>
@endif

@endsection
