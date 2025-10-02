<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Taxi Seguro · Iniciar sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: '#FF1B8F', ink:'#0B0B0C' },
          dropShadow: { glow: '0 0 25px rgba(255,27,143,.45)' },
          keyframes: {
            floaty: { '0%,100%':{transform:'translateY(0)'}, '50%':{transform:'translateY(-6px)'} },
            gradient: { '0%,100%':{'background-position':'0% 50%'}, '50%':{'background-position':'100% 50%'} }
          },
          animation: {
            floaty:'floaty 6s ease-in-out infinite',
            gradient:'gradient 12s ease infinite',
          }
        }
      }
    }
  </script>
  <!-- Inter + Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    html,body{ height:100% }
    body{ font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; background:#060608; color:#EDEDED }
    .noise:after{
      content:''; position:fixed; inset:0; pointer-events:none; opacity:.08;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.6'/%3E%3C/svg%3E");
      mix-blend-mode:soft-light;
    }
    .card-glass{ background:linear-gradient(180deg,rgba(255,255,255,.09),rgba(255,255,255,.04)); border:1px solid rgba(255,255,255,.12); backdrop-filter:blur(12px) }
    .btn-neo{ border:1px solid rgba(255,255,255,.12); background:radial-gradient(120% 120% at 10% 10%, rgba(255,27,143,.25), rgba(255,255,255,.03)); box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 10px 30px rgba(255,27,143,.18); transition:.2s ease }
    .btn-neo:hover{ transform:translateY(-2px); box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 16px 40px rgba(255,27,143,.32) }
    .input{ background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.15); transition:.15s; }
    .input:focus{ outline:none; border-color:#FF1B8F; box-shadow:0 0 0 3px rgba(255,27,143,.25) }
  </style>
</head>
<body class="noise overflow-x-hidden">
  <!-- BG -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0">
    <div class="absolute -top-1/3 -left-1/3 w-[70vw] h-[70vw] rounded-full blur-3xl opacity-30"
         style="background: radial-gradient(45% 45% at 50% 50%, rgba(255,27,143,.55), transparent 60%);"></div>
    <div class="absolute -bottom-1/3 -right-1/3 w-[70vw] h-[70vw] rounded-full blur-3xl opacity-30"
         style="background: radial-gradient(50% 50% at 50% 50%, rgba(86,167,255,.38), transparent 60%);"></div>
    <div class="absolute inset-0 bg-[length:300%_300%] animate-gradient opacity-25"
         style="background-image: linear-gradient(120deg, rgba(255,27,143,.12), rgba(86,167,255,.10), rgba(255,27,143,.12));"></div>
  </div>

  <!-- Contenido -->
  <main class="relative z-10 min-h-full flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
      <!-- Logo / título -->
      <div class="mb-8 flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center drop-shadow-glow"
             style="background: radial-gradient(100% 100% at 30% 20%, rgba(255,27,143,.9), rgba(255,27,143,.55));">
          <i class="fa-solid fa-taxi text-white text-lg"></i>
        </div>
        <div>
          <div class="text-2xl font-extrabold tracking-tight">Taxi Seguro</div>
          <div class="text-xs text-white/60 -mt-0.5">Panel seguro de administración</div>
        </div>
      </div>

      <div class="card-glass rounded-2xl p-6 md:p-7 shadow-2xl">
        <h1 class="text-xl md:text-2xl font-bold">Iniciar sesión</h1>
        <p class="text-white/70 text-sm mt-1">Usa tus credenciales de administrador.</p>

        @if (session('status'))
          <div class="mt-4 text-sm rounded-lg bg-green-500/15 border border-green-500/30 px-3 py-2 text-green-200">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mt-4 text-sm rounded-lg bg-rose-500/15 border border-rose-500/30 px-3 py-2 text-rose-200">
            {{ $errors->first() }}
          </div>
        @endif

        <form class="mt-6 space-y-4" method="POST" action="{{ route('login.post') }}">
          @csrf
          <div>
            <label for="email" class="block text-sm mb-1.5">Correo</label>
            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                   value="{{ old('email') }}"
                   class="input w-full rounded-lg px-3 py-2.5 placeholder-white/40"
                   placeholder="tú@empresa.com">
          </div>

          <div>
            <div class="flex items-center justify-between">
              <label for="password" class="block text-sm mb-1.5">Contraseña</label>
              {{-- Si luego agregas recovery, activa este enlace --}}
              {{-- <a href="{{ route('password.request') }}" class="text-xs text-white/70 hover:text-white">¿Olvidaste tu contraseña?</a> --}}
            </div>
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="input w-full rounded-lg px-3 py-2.5 placeholder-white/40"
                   placeholder="••••••••">
          </div>

          <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="remember" class="rounded border-white/20 bg-transparent">
              <span>Recordarme</span>
            </label>
            @if (Route::has('home'))
              <a href="{{ route('home') }}" class="text-white/70 hover:text-white">Volver al inicio</a>
            @endif
          </div>

          <button type="submit" class="btn-neo w-full rounded-xl px-5 py-3 font-bold flex items-center justify-center gap-2">
            <i class="fa-regular fa-unlock-keyhole"></i> Entrar
          </button>
        </form>
      </div>

      <p class="text-center text-xs text-white/50 mt-6">
        © {{ date('Y') }} FlashRide · Seguridad primero · Hecho con <span class="text-brand">♥</span>
      </p>
    </div>
  </main>
</body>
</html>
