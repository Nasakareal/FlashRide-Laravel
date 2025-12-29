<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Taxi Seguro · Iniciar sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 (solo para grid/spacing y que se vea institucional) -->
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
      --danger:#ef4444;
      --success:#22c55e;
    }

    html,body{ height:100%; }
    body{
      font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,Noto Sans;
      background:#fff;
      color:var(--ink);
    }

    /* fondo suave como welcome */
    .bg-soft{
      min-height:100%;
      background:
        radial-gradient(900px 420px at 15% 10%, rgba(255,27,143,.10), transparent 55%),
        radial-gradient(820px 420px at 85% 20%, rgba(86,167,255,.10), transparent 55%),
        linear-gradient(180deg, #fff 0%, var(--soft) 100%);
    }

    .card-soft{
      border:1px solid var(--border);
      border-radius: 18px;
      background:#fff;
      box-shadow: 0 12px 30px rgba(17,24,39,.08);
    }

    .brand-mark{
      width:46px; height:46px;
      border-radius: 14px;
      display:flex; align-items:center; justify-content:center;
      background: rgba(255,27,143,.10);
      border:1px solid rgba(255,27,143,.18);
      color: var(--brand);
    }

    .title{
      font-weight: 900;
      letter-spacing: -0.02em;
    }

    .muted{ color: var(--muted); }

    .form-label{
      font-weight: 700;
      color: var(--ink);
      margin-bottom: .4rem;
    }

    .form-control{
      border:1px solid var(--border);
      border-radius: 12px;
      padding: .72rem .9rem;
      font-weight: 600;
    }
    .form-control:focus{
      border-color: rgba(255,27,143,.55);
      box-shadow: 0 0 0 .25rem rgba(255,27,143,.15);
    }

    .btn-brand{
      background: var(--brand);
      border:1px solid var(--brand);
      color:#fff;
      font-weight: 900;
      border-radius: 12px;
      padding: .85rem 1rem;
    }
    .btn-brand:hover{
      background:#e0147e;
      border-color:#e0147e;
      color:#fff;
    }

    .btn-outline-brand{
      border:1px solid var(--brand);
      color: var(--brand);
      background:#fff;
      font-weight: 900;
      border-radius: 12px;
      padding: .75rem 1rem;
    }
    .btn-outline-brand:hover{
      background: rgba(255,27,143,.08);
      color: var(--brand);
      border-color: var(--brand);
    }

    .alert-soft{
      border-radius: 12px;
      border: 1px solid var(--border);
      background: #fff;
      padding: .75rem .9rem;
      font-weight: 650;
    }
    .alert-success{
      border-color: rgba(34,197,94,.35);
      background: rgba(34,197,94,.08);
      color: #065f46;
    }
    .alert-danger{
      border-color: rgba(239,68,68,.35);
      background: rgba(239,68,68,.08);
      color: #7f1d1d;
    }

    .small-link{
      font-weight: 800;
      text-decoration: none;
      color: var(--ink);
    }
    .small-link:hover{ color: var(--brand); }

    .footer-note{
      color: var(--muted);
      font-size: .82rem;
    }
  </style>
</head>

<body>
  <main class="bg-soft d-flex align-items-center py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5">

          <!-- Encabezado sin navbar -->
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-mark">
              <i class="fa-solid fa-taxi"></i>
            </div>
            <div>
              <div class="h4 mb-0 title">Taxi Seguro</div>
              <div class="muted" style="font-size:.92rem;">Acceso al panel de administración</div>
            </div>
          </div>

          <div class="card-soft p-4 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
              <div>
                <h1 class="h4 title mb-1">Iniciar sesión</h1>
                <div class="muted">Ingresa con tus credenciales autorizadas.</div>
              </div>
              <div class="d-none d-md-flex align-items-center gap-2 muted" style="font-weight:800;">
                <i class="fa-solid fa-shield-halved" style="color:var(--brand);"></i>
                Acceso restringido
              </div>
            </div>

            @if (session('status'))
              <div class="alert-soft alert-success mt-3">
                {{ session('status') }}
              </div>
            @endif

            @if ($errors->any())
              <div class="alert-soft alert-danger mt-3">
                {{ $errors->first() }}
              </div>
            @endif

            <form class="mt-4" method="POST" action="{{ route('login.post') }}">
              @csrf

              <div class="mb-3">
                <label for="email" class="form-label">Correo</label>
                <input
                  id="email"
                  name="email"
                  type="email"
                  class="form-control"
                  placeholder="tú@dependencia.gob.mx"
                  autocomplete="email"
                  value="{{ old('email') }}"
                  required
                  autofocus
                >
              </div>

              <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between">
                  <label for="password" class="form-label mb-0">Contraseña</label>
                  {{-- Si luego agregas recovery, activa este enlace --}}
                  {{-- <a href="{{ route('password.request') }}" class="small-link" style="font-size:.85rem;">¿Olvidaste tu contraseña?</a> --}}
                </div>
                <input
                  id="password"
                  name="password"
                  type="password"
                  class="form-control mt-2"
                  placeholder="••••••••"
                  autocomplete="current-password"
                  required
                >
              </div>

              <div class="d-flex align-items-center justify-content-between mb-3">
                <label class="d-inline-flex align-items-center gap-2" style="font-weight:800; color:var(--ink);">
                  <input type="checkbox" name="remember" class="form-check-input m-0" style="border-color: var(--border);">
                  <span style="font-size:.95rem;">Recordarme</span>
                </label>

                <a href="{{ url('/') }}" class="small-link" style="font-size:.92rem;">
                  Volver al inicio
                </a>
              </div>

              <button type="submit" class="btn btn-brand w-100">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Entrar
              </button>

              <div class="mt-3">
                <a class="btn btn-outline-brand w-100" href="{{ url('/') }}">
                  <i class="fa-solid fa-house me-2"></i> Página de bienvenida
                </a>
              </div>
            </form>

            <div class="mt-4 muted" style="font-size:.9rem;">
              <i class="fa-solid fa-circle-info me-2"></i>
              Si no tienes acceso, solicita alta con el administrador del sistema.
            </div>
          </div>

          <div class="text-center mt-4 footer-note">
            © {{ date('Y') }} Taxi Seguro · FlashRide · Hecho con <span style="color:var(--brand); font-weight:900;">♥</span> en Michoacán
          </div>

        </div>
      </div>
    </div>
  </main>

  <!-- Bootstrap JS (no es necesario, pero no estorba) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
