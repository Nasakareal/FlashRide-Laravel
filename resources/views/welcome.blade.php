{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'Bienvenido')

@section('page-header')
  <div class="row align-items-center g-4 g-lg-5">
    <div class="col-lg-7">
      <div class="hero-badge mb-3">
        <i class="fa-solid fa-shield-halved" style="color: var(--brand);"></i>
        Servicio público · Plataforma de apoyo y control
      </div>

      <h1 class="display-5 display-md-4 mb-3">
        Taxi Seguro
        <span style="color: var(--brand);">Administración</span>
        y seguimiento en un solo lugar.
      </h1>

      <p class="lead mb-4">
        Gestión de conductores, vehículos, viajes e incidencias con trazabilidad.
        Panel listo para operación y revisión.
      </p>

      <div class="d-flex flex-column flex-sm-row gap-2">
        @auth
          @if (Route::has('admin.dashboard'))
            <a href="{{ route('admin.dashboard') }}" class="btn btn-brand btn-lg px-4">
              <i class="fa-solid fa-gauge-high me-2"></i> Entrar al Panel
            </a>
          @else
            <a href="{{ url('/admin') }}" class="btn btn-brand btn-lg px-4">
              <i class="fa-solid fa-gauge-high me-2"></i> Entrar al Panel
            </a>
          @endif
        @else
          <a href="{{ route('login') }}" class="btn btn-brand btn-lg px-4">
            <i class="fa-regular fa-user me-2"></i> Iniciar sesión
          </a>
        @endauth

        <a href="{{ route('teleferico.preregistro.create') }}" class="btn btn-outline-primary btn-lg px-4">
          <i class="fa-solid fa-id-card me-2"></i> Preregistro de tarjeta
        </a>

        <a href="#servicios" class="btn btn-outline-secondary btn-lg px-4">
          Ver módulos
        </a>
      </div>

      <div class="mt-3 small" style="color: var(--muted);">
        <i class="fa-solid fa-circle-info me-2"></i>
        Acceso restringido para módulos internos. El preregistro de tarjeta está disponible al público.
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card-soft p-4 p-lg-4">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="icon-pill"><i class="fa-solid fa-id-card"></i></div>
          <div>
            <div class="fw-black" style="font-weight:900;">Preregistro de tarjeta</div>
            <div class="small" style="color:var(--muted);">Disponible para solicitud pública</div>
          </div>
        </div>

        <div class="p-3 rounded-3 mb-3" style="background:#f9fafb; border:1px solid var(--border);">
          <div class="small" style="color:var(--muted);">Proceso</div>
          <div class="fw-semibold">Captura tus datos y registra tu solicitud antes de la entrega física.</div>
        </div>

        <div class="p-3 rounded-3 mb-3" style="background:#f9fafb; border:1px solid var(--border);">
          <div class="small" style="color:var(--muted);">Estatus inicial</div>
          <div class="fw-semibold">Tu preregistro se guarda como INACTIVA hasta la entrega de la tarjeta.</div>
        </div>

        <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
          <div class="small" style="color:var(--muted);">Validación</div>
          <div class="fw-semibold">La CURP se revisa para evitar registros duplicados.</div>
        </div>

        <hr class="my-4">

        <a href="{{ route('teleferico.preregistro.create') }}" class="btn btn-brand w-100">
          <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Ir al preregistro
        </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <section class="mb-5">
    <div class="row g-3 g-lg-4">
      <div class="col-lg-4">
        <div class="card-soft p-4 h-100">
          <div class="icon-pill mb-3"><i class="fa-solid fa-file-signature"></i></div>
          <div class="fw-black mb-2" style="font-weight:900;">Preregistro en línea</div>
          <div class="small" style="color:var(--muted);">
            Los ciudadanos pueden capturar sus datos desde el sitio web antes de la inauguración.
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card-soft p-4 h-100">
          <div class="icon-pill mb-3"><i class="fa-solid fa-database"></i></div>
          <div class="fw-black mb-2" style="font-weight:900;">Validación de CURP</div>
          <div class="small" style="color:var(--muted);">
            El sistema revisa si la CURP ya fue registrada para evitar duplicados.
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card-soft p-4 h-100">
          <div class="icon-pill mb-3"><i class="fa-solid fa-check-circle"></i></div>
          <div class="fw-black mb-2" style="font-weight:900;">Activación posterior</div>
          <div class="small" style="color:var(--muted);">
            La tarjeta se activa únicamente al momento de su entrega oficial.
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- MÓDULOS --}}
  <section id="servicios">
    <div class="row align-items-end mb-4">
      <div class="col-lg-8">
        <h2 class="section-title mb-2">Módulos principales</h2>
        <p class="mb-0" style="color:var(--muted);">
          Herramientas para control administrativo, evidencia y seguimiento.
        </p>
      </div>
    </div>

    <div class="row g-3 g-lg-4">
      <div class="col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="icon-pill mb-3"><i class="fa-solid fa-users-gear"></i></div>
          <div class="fw-black" style="font-weight:900;">Conductores</div>
          <div class="small" style="color:var(--muted);">Altas, estado, validación y control.</div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="icon-pill mb-3"><i class="fa-solid fa-car-side"></i></div>
          <div class="fw-black" style="font-weight:900;">Vehículos</div>
          <div class="small" style="color:var(--muted);">Placas, pólizas, revisión y estatus.</div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="icon-pill mb-3"><i class="fa-solid fa-route"></i></div>
          <div class="fw-black" style="font-weight:900;">Viajes</div>
          <div class="small" style="color:var(--muted);">Flujo, fases, cierres y reportes.</div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card-soft p-4">
          <div class="icon-pill mb-3"><i class="fa-solid fa-triangle-exclamation"></i></div>
          <div class="fw-black" style="font-weight:900;">Incidencias</div>
          <div class="small" style="color:var(--muted);">Eventos, evidencias y seguimiento.</div>
        </div>
      </div>
    </div>

    <div class="row g-3 g-lg-4 mt-1">
      <div class="col-lg-6">
        <div class="card-soft p-4">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-pill"><i class="fa-solid fa-chart-line"></i></div>
            <div>
              <div class="fw-black" style="font-weight:900;">Trazabilidad</div>
              <div class="small" style="color:var(--muted);">Bitácoras, acciones y evidencia para auditoría.</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card-soft p-4">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-pill"><i class="fa-solid fa-shield"></i></div>
            <div>
              <div class="fw-black" style="font-weight:900;">Seguridad</div>
              <div class="small" style="color:var(--muted);">Acceso por autenticación y permisos.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CONTACTO --}}
  <section class="py-5 mt-5" style="background: var(--soft);" id="contacto">
    <div class="row align-items-center g-4">
      <div class="col-lg-8">
        <h2 class="section-title mb-2">Contacto</h2>
        <p class="mb-0" style="color:var(--muted);">
          Para dudas operativas o soporte técnico, utiliza los canales institucionales correspondientes.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="https://transporte.michoacan.gob.mx" class="btn btn-outline-secondary px-4" target="_blank" rel="noopener">
          <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Sitio oficial
        </a>
      </div>
    </div>
  </section>
@endsection
