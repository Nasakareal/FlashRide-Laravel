@extends('layouts.app')

@section('title','Dashboard')

@php
  $to = function(string $name, string $fallback = '#', array $params = []) {
    return \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : $fallback;
  };

  // Roles (Spatie o columna role)
  $user = auth()->user();

  $isSupport = false;
  $isAdminLike = false;

  try { $isSupport = $user && $user->hasRole('support'); }
  catch (\Throwable $e) { $isSupport = ($user && (($user->role ?? null) === 'support')); }

  try { $isAdminLike = $user && $user->hasAnyRole(['admin','super_admin','superadmin']); }
  catch (\Throwable $e) { $isAdminLike = ($user && in_array(($user->role ?? null), ['admin','super_admin','superadmin'], true)); }

  $canSeeTickets = $isSupport || $isAdminLike;
@endphp

@section('content')

  <section id="accesos">
    <div class="d-flex align-items-end justify-content-between mb-3">
      <div>
        <h2 class="section-title mb-1">Accesos rápidos</h2>
        <div class="small" style="color:var(--muted);">Entrar directo a listados y módulos.</div>
      </div>
    </div>

    <div class="row g-3 g-lg-4">
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <div class="icon-pill mb-3"><i class="fa-solid fa-car-side"></i></div>
              <div class="fw-black" style="font-weight:900;">Vehículos</div>
              <div class="small" style="color:var(--muted);">Registro, placas y estatus.</div>
            </div>
            <div class="text-end">
              <div class="small" style="color:var(--muted);">Total</div>
              <div class="h4 mb-0 fw-black" style="font-weight:900;">
                {{ isset($vehiclesCount) ? number_format($vehiclesCount) : '—' }}
              </div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <a class="btn btn-brand btn-sm px-3 py-2" href="{{ $to('admin.vehicles.index') }}">
              <i class="fa-solid fa-list me-2"></i> Ver
            </a>
            <a class="btn btn-outline-secondary btn-sm px-3 py-2" href="{{ $to('admin.vehicles.create') }}">
              <i class="fa-solid fa-plus me-2"></i> Nuevo
            </a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <div class="icon-pill mb-3"><i class="fa-solid fa-users-gear"></i></div>
              <div class="fw-black" style="font-weight:900;">Conductores</div>
              <div class="small" style="color:var(--muted);">Altas, validación y control.</div>
            </div>
            <div class="text-end">
              <div class="small" style="color:var(--muted);">Total</div>
              <div class="h4 mb-0 fw-black" style="font-weight:900;">
                {{ isset($driversCount) ? number_format($driversCount) : '—' }}
              </div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <a class="btn btn-brand btn-sm px-3 py-2" href="{{ $to('admin.drivers.index') }}">
              <i class="fa-solid fa-list me-2"></i> Ver
            </a>
            <a class="btn btn-outline-secondary btn-sm px-3 py-2" href="{{ $to('admin.drivers.create') }}">
              <i class="fa-solid fa-plus me-2"></i> Nuevo
            </a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <div class="icon-pill mb-3"><i class="fa-solid fa-route"></i></div>
              <div class="fw-black" style="font-weight:900;">Rutas</div>
              <div class="small" style="color:var(--muted);">Rutas de camión registradas.</div>
            </div>
            <div class="text-end">
              <div class="small" style="color:var(--muted);">Total</div>
              <div class="h4 mb-0 fw-black" style="font-weight:900;">
                {{ isset($routesCount) ? number_format($routesCount) : '—' }}
              </div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <a class="btn btn-brand btn-sm px-3 py-2" href="{{ $to('admin.routes.index') }}">
              <i class="fa-solid fa-list me-2"></i> Ver
            </a>
            <a class="btn btn-outline-secondary btn-sm px-3 py-2" href="{{ $to('admin.routes.create') }}">
              <i class="fa-solid fa-plus me-2"></i> Nuevo
            </a>
          </div>
        </div>
      </div>

      {{-- 4ta tarjeta: Admin ve Usuarios / Support ve Tickets --}}
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              @if($canSeeTickets && !$isAdminLike)
                <div class="icon-pill mb-3"><i class="fa-solid fa-headset"></i></div>
                <div class="fw-black" style="font-weight:900;">Tickets</div>
                <div class="small" style="color:var(--muted);">Soporte y seguimiento.</div>
              @else
                <div class="icon-pill mb-3"><i class="fa-solid fa-user-group"></i></div>
                <div class="fw-black" style="font-weight:900;">Usuarios</div>
                <div class="small" style="color:var(--muted);">Admins, roles y acceso.</div>
              @endif
            </div>

            <div class="text-end">
              <div class="small" style="color:var(--muted);">
                @if($canSeeTickets && !$isAdminLike)
                  Abiertos
                @else
                  Total
                @endif
              </div>
              <div class="h4 mb-0 fw-black" style="font-weight:900;">
                @if($canSeeTickets && !$isAdminLike)
                  {{ isset($ticketsOpenCount) ? number_format($ticketsOpenCount) : '—' }}
                @else
                  {{ isset($usersCount) ? number_format($usersCount) : '—' }}
                @endif
              </div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            @if($canSeeTickets && !$isAdminLike)
              <a class="btn btn-brand btn-sm px-3 py-2" href="{{ $to('admin.tickets.index') }}">
                <i class="fa-solid fa-inbox me-2"></i> Ver
              </a>
              <a class="btn btn-outline-secondary btn-sm px-3 py-2"
                 href="{{ $to('admin.tickets.index', '#', ['unassigned' => 1]) }}">
                <i class="fa-solid fa-bolt me-2"></i> Sin asignar
              </a>
            @else
              <a class="btn btn-brand btn-sm px-3 py-2" href="{{ $to('admin.users.index') }}">
                <i class="fa-solid fa-list me-2"></i> Ver
              </a>
              <a class="btn btn-outline-secondary btn-sm px-3 py-2" href="{{ $to('admin.users.create') }}">
                <i class="fa-solid fa-plus me-2"></i> Nuevo
              </a>
            @endif
          </div>
        </div>
      </div>

    </div>
  </section>

  <section class="mt-4">
    <div class="row g-3 g-lg-4">
      <div class="col-lg-8">
        <div class="card-soft overflow-hidden">
          <div class="p-4 d-flex align-items-center justify-content-between" style="border-bottom:1px solid var(--border);">
            <div class="fw-black" style="font-weight:900;">
              <i class="fa-solid fa-route me-2" style="color:var(--brand)"></i> Operación
            </div>
            <div class="d-flex gap-2">
              <a class="btn btn-outline-secondary btn-sm px-3 py-2" href="{{ $to('admin.trips.index') }}">
                <i class="fa-solid fa-list me-2"></i> Viajes
              </a>
              <a class="btn btn-outline-secondary btn-sm px-3 py-2" href="{{ $to('admin.panic.index') }}">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Pánico
              </a>
              @if($canSeeTickets)
                <a class="btn btn-outline-secondary btn-sm px-3 py-2" href="{{ $to('admin.tickets.index') }}">
                  <i class="fa-solid fa-headset me-2"></i> Tickets
                </a>
              @endif
            </div>
          </div>

          <div class="p-4">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                  <div class="small" style="color:var(--muted);">Viajes hoy</div>
                  <div class="h4 mb-0 fw-black" style="font-weight:900;">
                    {{ isset($tripsToday) ? number_format($tripsToday) : '—' }}
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                  <div class="small" style="color:var(--muted);">Incidencias abiertas</div>
                  <div class="h4 mb-0 fw-black" style="font-weight:900;">
                    {{ isset($incidentsOpen) ? number_format($incidentsOpen) : '—' }}
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                  <div class="small" style="color:var(--muted);">Alertas pánico</div>
                  <div class="h4 mb-0 fw-black" style="font-weight:900;">
                    {{ isset($panicAlertsOpen) ? number_format($panicAlertsOpen) : '—' }}
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-3 small" style="color:var(--muted);">
              <i class="fa-solid fa-circle-info me-2"></i>
              Después conectamos esta sección a los estados reales (hoy/abiertas/en revisión).
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card-soft overflow-hidden">
          <div class="p-4 fw-black" style="font-weight:900; border-bottom:1px solid var(--border);">
            <i class="fa-solid fa-clock-rotate-left me-2" style="color:var(--brand)"></i> Actividad reciente
          </div>

          <div class="p-4" style="border-bottom:1px solid var(--border);">
            <div class="fw-black" style="font-weight:900;">Ingreso correcto</div>
            <div class="small" style="color:var(--muted);">Sesión iniciada sin incidencias.</div>
          </div>

          <div class="p-4">
            <div class="fw-black" style="font-weight:900;">Accesos rápidos</div>
            <div class="small" style="color:var(--muted);">
              Usa las tarjetas para entrar directo a cada módulo.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
