{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>
    @hasSection('title')@yield('title') · @endif {{ config('app.name', 'Taxi Seguro') }}
  </title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

  @stack('head')

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

    /* Navbar estilo gobierno */
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

    /* “Hero” reusable */
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

    /* Cards */
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

    .section-title{
      font-weight: 900;
      letter-spacing: -0.01em;
    }

    footer{
      border-top: 1px solid var(--border);
      background:#fff;
    }
    .footer-link{ color:#111827; text-decoration:none; font-weight:700; }
    .footer-link:hover{ color: var(--brand); }

    .brand-logo img{ height:64px; }
    @media (min-width: 992px){
      body{ padding-top: 92px; }
      .brand-logo img{ height:72px; }
    }
  </style>

  @stack('styles')
</head>

<body>

  {{-- NAV --}}
  @include('layouts.partials.gov-nav')

  {{-- PAGE HEADER --}}
  @hasSection('page-header')
    <header class="hero py-5">
      <div class="container py-2 py-lg-4">
        @yield('page-header')
      </div>
    </header>
  @endif

  {{-- CONTENT --}}
  <main class="py-5">
    <div class="container">
      @yield('content')
    </div>
  </main>

  {{-- FOOTER --}}
  @include('layouts.partials.gov-footer')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
