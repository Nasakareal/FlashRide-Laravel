<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Taxi Seguro · Bienvenido</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --brand:#FF1B8F;
      --ink:#111827;
      --muted:#6B7280;
      --border:#E5E7EB;
      --soft:#F8FAFC;
    }

    html,body{ height:100%; }
    body{
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Noto Sans;
      background:#fff;
      color:var(--ink);
      padding-top: 96px; /* navbar fixed-top */
    }

    /* Navbar estilo gobierno: blanca, borde abajo, links sobrios */
    .gov-nav{
      background:#fff !important;
      border-bottom: 1px solid var(--border);
    }
    .gov-nav .nav-link{
      color:#111827 !important;
      font-weight:600;
      padding: .75rem .9rem;
    }
    .gov-nav .nav-link:hover{ color: var(--brand) !important; }
    .gov-nav .navbar-toggler{ border-color: rgba(17,24,39,.18); }

    /* Botones */
    .btn-brand{
      background: var(--brand);
      border-color: var(--brand);
      color:#fff;
      font-weight:800;
    }
    .btn-brand:hover{
      background: #e0147e;
      border-color: #e0147e;
      color:#fff;
    }
    .btn-outline-brand{
      border:1px solid var(--brand);
      color: var(--brand);
      font-weight:800;
      background:#fff;
    }
    .btn-outline-brand:hover{
      background: rgba(255,27,143,.08);
      border-color: var(--brand);
      color: var(--brand);
    }

    /* Hero */
    .hero{
      background: linear-gradient(180deg, #ffffff 0%, var(--soft) 100%);
      border-bottom: 1px solid var(--border);
    }
    .hero-badge{
      display:inline-flex;
      align-items:center;
      gap:.5rem;
      font-size:.85rem;
      font-weight:700;
      color:#111827;
      background:#fff;
      border:1px solid var(--border);
      border-radius: 999px;
      padding: .45rem .75rem;
    }
    .hero h1{
      font-weight: 900;
      letter-spacing: -0.02em;
      line-height: 1.05;
    }
    .hero p{ color: var(--muted); }

    /* Tarjetas */
    .card-soft{
      border:1px solid var(--border);
      border-radius: 16px;
      background:#fff;
      box-shadow: 0 6px 18px rgba(17,24,39,.06);
      height:100%;
    }
    .icon-pill{
      width:44px;
      height:44px;
      border-radius: 12px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: rgba(255,27,143,.10);
      color: var(--brand);
    }

    /* Sección informativa */
    .section-title{
      font-weight: 900;
      letter-spacing: -0.01em;
    }

    /* Footer */
    footer{
      border-top: 1px solid var(--border);
      background:#fff;
    }
    .footer-link{ color:#111827; text-decoration:none; font-weight:700; }
    .footer-link:hover{ color: var(--brand); }

    /* Ajuste de logo en navbar */
    .brand-logo img{ height:64px; }
    @media (min-width: 992px){
      body{ padding-top: 92px; }
      .brand-logo img{ height:72px; }
    }
  </style>
</head>

<body>

  <!-- NAVBAR (Gobierno) -->
  <nav class="navbar gov-nav fixed-top navbar-expand-lg">
    <div class="container-fluid px-3 px-lg-4">

      <a class="navbar-brand brand-logo d-flex align-items-center gap-3" href="https://transporte.michoacan.gob.mx">
        <img src="https://michoacan.gob.mx/cdn/img/logos/dependencias/transporte.svg" alt="Transporte Michoacán">
        <span class="d-none d-md-inline fw-black" style="font-weight:900; letter-spacing:-.02em;">
          Taxi Seguro
        </span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGov"
              aria-controls="navbarGov" aria-expanded="false" aria-label="Abrir navegación">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarGov">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="https://transporte.michoacan.gob.mx">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="https://dif.michoacan.gob.mx/categoria/noticias/">Noticias</a></li>
          <li class="nav-item"><a class="nav-link" href="http://tramites.michoacan.gob.mx" target="_blank" rel="noopener">Trámites</a></li>
          <li class="nav-item">
            <a class="nav-link" target="_blank" rel="noopener"
               href="https://consultapublicamx.plataformadetransparencia.org.mx/vut-web/?idSujetoObigadoParametro=3354&amp;idEntidadParametro=16&amp;idSectorParametro=21">
              Transparencia
            </a>
          </li>
          <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>

          <li class="nav-item d-none d-lg-block"><span class="mx-2" style="color:#d1d5db;">|</span></li>

          <li class="nav-item">
            <a class="nav-link" href="https://www.facebook.com/MichoacanCocotra/" target="_blank" rel="noopener" aria-label="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://twitter.com/cocotramich" target="_blank" rel="noopener" aria-label="X/Twitter">
              <i class="fab fa-twitter"></i>
            </a>
          </li>

          <!-- Acciones (Panel / Login) -->
          <li class="nav-item mt-2 mt-lg-0 ms-lg-2">
            @auth
              @if (Route::has('admin.dashboard'))
                <a class="btn btn-brand btn-sm px-3 py-2" href="{{ route('admin.dashboard') }}">
                  <i class="fa-solid fa-gauge-high me-2"></i> Ir al Panel
                </a>
              @else
                <a class="btn btn-brand btn-sm px-3 py-2" href="{{ url('/admin') }}">
                  <i class="fa-solid fa-gauge-high me-2"></i> Ir al Panel
                </a>
              @endif
            @else
              <a class="btn btn-outline-brand btn-sm px-3 py-2" href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}">
                <i class="fa-regular fa-user me-2"></i> Iniciar sesión
              </a>
            @endauth
          </li>
        </ul>
      </div>

    </div>
  </nav>

  <!-- HERO -->
  <header class="hero py-5">
    <div class="container py-2 py-lg-4">
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
              <a href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}" class="btn btn-brand btn-lg px-4">
                <i class="fa-regular fa-user me-2"></i> Iniciar sesión
              </a>
            @endauth

            <a href="#servicios" class="btn btn-outline-secondary btn-lg px-4">
              Ver módulos
            </a>
          </div>

          <div class="mt-3 small" style="color: var(--muted);">
            <i class="fa-solid fa-circle-info me-2"></i>
            Acceso restringido. Requiere credenciales autorizadas.
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card-soft p-4 p-lg-4">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="icon-pill"><i class="fa-solid fa-taxi"></i></div>
              <div>
                <div class="fw-black" style="font-weight:900;">Estado del sistema</div>
                <div class="small" style="color:var(--muted);">Vista informativa (demo)</div>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-6">
                <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                  <div class="small" style="color:var(--muted);">Conductores</div>
                  <div class="h5 mb-0 fw-black" style="font-weight:900;">—</div>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                  <div class="small" style="color:var(--muted);">Vehículos</div>
                  <div class="h5 mb-0 fw-black" style="font-weight:900;">—</div>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                  <div class="small" style="color:var(--muted);">Viajes hoy</div>
                  <div class="h5 mb-0 fw-black" style="font-weight:900;">—</div>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                  <div class="small" style="color:var(--muted);">Incidencias</div>
                  <div class="h5 mb-0 fw-black" style="font-weight:900;">—</div>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <div class="small" style="color:var(--muted);">
              <i class="fa-solid fa-lock me-2"></i>
              Los módulos operativos se muestran al iniciar sesión.
            </div>
          </div>
        </div>

      </div>
    </div>
  </header>

  <!-- SERVICIOS -->
  <main class="py-5" id="servicios">
    <div class="container">
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

    </div>
  </main>

  <!-- CONTACTO -->
  <section class="py-5" style="background: var(--soft);" id="contacto">
    <div class="container">
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
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="py-4">
    <div class="container">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="small" style="color:var(--muted);">
          © {{ date('Y') }} Taxi Seguro · RRB-Soluciones
        </div>
        <div class="d-flex align-items-center gap-3">
          @auth
            @if (Route::has('admin.dashboard'))
              <a class="footer-link" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high me-2"></i>Panel</a>
            @else
              <a class="footer-link" href="{{ url('/admin') }}"><i class="fa-solid fa-gauge-high me-2"></i>Panel</a>
            @endif
          @else
            <a class="footer-link" href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}"><i class="fa-regular fa-user me-2"></i>Login</a>
          @endauth

          <a class="footer-link" href="https://github.com/Nasakareal/FlashRide" target="_blank" rel="noopener">
            <i class="fa-brands fa-github me-2"></i>Repo
          </a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
