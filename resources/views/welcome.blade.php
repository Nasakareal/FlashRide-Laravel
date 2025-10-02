<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Taxi Seguro · Bienvenido</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#FF1B8F',
            brandDark: '#E0147E',
            ink: '#0B0B0C'
          },
          dropShadow: { glow: '0 0 25px rgba(255,27,143,.45)' },
          keyframes: {
            floaty: { '0%,100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-10px)'} },
            gradient: { '0%,100%': { 'background-position': '0% 50%' }, '50%': { 'background-position': '100% 50%'} },
            spinSlow: { '0%': { transform:'rotate(0deg)' }, '100%': { transform:'rotate(360deg)'} }
          },
          animation: {
            floaty: 'floaty 6s ease-in-out infinite',
            gradient: 'gradient 12s ease infinite',
            spinSlow: 'spinSlow 32s linear infinite'
          }
        }
      }
    }
  </script>
  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    :root { --brand: #FF1B8F; --ink:#0B0B0C; }
    html,body { height:100%; }
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Noto Sans, "Apple Color Emoji","Segoe UI Emoji"; background:#060608; color:#EDEDED;}
    .noise::after{
      content:''; position:fixed; inset:0; pointer-events:none; opacity:.08;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.6'/%3E%3C/svg%3E");
      mix-blend-mode: soft-light;
    }
    .btn-neo{
      position: relative; transition: all .2s ease; border: 1px solid rgba(255,255,255,.1);
      background: radial-gradient(120% 120% at 10% 10%, rgba(255,27,143,.25), rgba(255,255,255,.02));
      box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 10px 30px rgba(255,27,143,.15);
    }
    .btn-neo:hover{ transform: translateY(-2px); box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 16px 40px rgba(255,27,143,.28); }
    .card-glass{
      background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.04));
      border: 1px solid rgba(255,255,255,.12); backdrop-filter: blur(10px);
    }
    .chip{ border:1px solid rgba(255,255,255,.18); background: rgba(255,255,255,.06); }
  </style>
</head>
<body class="noise overflow-x-hidden">
  <!-- BG dinámico -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0">
    <div class="absolute -top-1/3 -left-1/3 w-[70vw] h-[70vw] rounded-full blur-3xl opacity-30 animate-spinSlow"
         style="background: radial-gradient(45% 45% at 50% 50%, rgba(255,27,143,.65), transparent 60%);"></div>
    <div class="absolute -bottom-1/3 -right-1/3 w-[70vw] h-[70vw] rounded-full blur-3xl opacity-30 animate-spinSlow"
         style="animation-direction: reverse; background: radial-gradient(50% 50% at 50% 50%, rgba(86,167,255,.45), transparent 60%);"></div>
    <div class="absolute inset-0 bg-[length:300%_300%] animate-gradient opacity-30"
         style="background-image: linear-gradient(120deg, rgba(255,27,143,.15), rgba(86,167,255,.12), rgba(255,27,143,.15));"></div>
  </div>

  <!-- Nav -->
  <header class="relative z-10">
    <div class="max-w-7xl mx-auto px-6 md:px-10 py-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
             style="background: radial-gradient(100% 100% at 30% 20%, rgba(255,27,143,.9), rgba(255,27,143,.5)); box-shadow: 0 8px 28px rgba(255,27,143,.4);">
          <i class="fa-solid fa-taxi text-white text-lg drop-shadow-glow"></i>
        </div>
        <span class="text-xl md:text-2xl font-extrabold tracking-tight" style="letter-spacing:.4px;">Taxi Seguro</span>
      </div>
      <div class="hidden md:flex items-center gap-3">
        <span class="chip px-3 py-1.5 rounded-full text-sm">v1 Web Admin</span>

        @auth
          @if (Route::has('admin.dashboard'))
            <a href="{{ route('admin.dashboard') }}" class="btn-neo px-4 py-2 rounded-lg font-semibold">Ir al Panel</a>
          @else
            <a href="{{ url('/admin') }}" class="btn-neo px-4 py-2 rounded-lg font-semibold">Ir al Panel</a>
          @endif
        @else
          @if (Route::has('login'))
            <a href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}" class="btn-neo">Iniciar sesión</a>

          @else
            <a href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}" class="btn-neo">Iniciar sesión</a>

          @endif
        @endauth
      </div>
    </div>
  </header>

  <!-- Hero -->
  <main class="relative z-10">
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-12 pb-10 md:pt-20 md:pb-16">
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div>
          <div class="inline-flex items-center gap-2 chip px-3 py-1.5 rounded-full text-xs uppercase tracking-wider mb-4">
            <i class="fa-solid fa-bolt"></i> Algo brutalmente rápido
          </div>
          <h1 class="text-4xl md:text-6xl font-extrabold leading-[1.05]">
            Administra <span class="text-brand drop-shadow-glow">toda la flota</span> con
            precisión de <span class="underline decoration-brand/60">quirófano</span>.
          </h1>
          <p class="mt-5 text-lg text-white/80 max-w-xl">
            Conductores, vehículos, viajes, incidencias y cobros: todo en un solo panel. Telemetría en vivo,
            <span class="text-white">botón de pánico</span> con seguimiento y reportes listos para auditoría.
          </p>

          <div class="mt-7 flex flex-col sm:flex-row gap-3">
            @auth
              @if (Route::has('admin.dashboard'))
                <a href="{{ route('admin.dashboard') }}" class="btn-neo px-6 py-3 rounded-xl font-bold text-base flex items-center justify-center gap-2">
                  <i class="fa-solid fa-gauge-high"></i> Entrar al Panel
                </a>
              @else
                <a href="{{ url('/admin') }}" class="btn-neo px-6 py-3 rounded-xl font-bold text-base flex items-center justify-center gap-2">
                  <i class="fa-solid fa-gauge-high"></i> Entrar al Panel
                </a>
              @endif
            @else
              @if (Route::has('login'))
                <a href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}" class="btn-neo px-6 py-3 rounded-xl font-bold text-base flex items-center justify-center gap-2">
                  <i class="fa-regular fa-user"></i> Iniciar sesión
                </a>
              @else
                <a href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}" class="btn-neo px-6 py-3 rounded-xl font-bold text-base flex items-center justify-center gap-2">
                  <i class="fa-regular fa-user"></i> Iniciar sesión
                </a>
              @endif
            @endauth
          </div>

          <div class="mt-6 flex items-center gap-4 text-sm text-white/60">
            <div class="flex items-center gap-2"><i class="fa-solid fa-shield-halved text-brand"></i> Rol: Admin</div>
            <div class="flex items-center gap-2"><i class="fa-solid fa-lock text-brand"></i> Protegido con auth</div>
            <div class="flex items-center gap-2"><i class="fa-solid fa-code-branch text-brand"></i> API independiente</div>
          </div>
        </div>

        <div class="relative">
          <div class="absolute inset-0 -z-10 blur-2xl opacity-60 animate-spinSlow"
               style="background: conic-gradient(from 0deg, rgba(255,27,143,.3), rgba(86,167,255,.25), rgba(255,27,143,.3)); border-radius: 28px;"></div>

          <div class="card-glass rounded-2xl p-6 md:p-8 shadow-2xl">
            <div class="grid grid-cols-2 gap-4">
              <div class="rounded-xl p-4 border border-white/10 bg-white/5 hover:bg-white/[.08] transition">
                <div class="text-2xl mb-2"><i class="fa-solid fa-users-gear"></i></div>
                <div class="font-semibold">Conductores</div>
                <div class="text-white/70 text-sm mt-1">Altas, ediciones y bloqueos.</div>
              </div>
              <div class="rounded-xl p-4 border border-white/10 bg-white/5 hover:bg-white/[.08] transition">
                <div class="text-2xl mb-2"><i class="fa-solid fa-car-side"></i></div>
                <div class="font-semibold">Vehículos</div>
                <div class="text-white/70 text-sm mt-1">Placas, pólizas y estado.</div>
              </div>
              <div class="rounded-xl p-4 border border-white/10 bg-white/5 hover:bg-white/[.08] transition">
                <div class="text-2xl mb-2"><i class="fa-solid fa-route"></i></div>
                <div class="font-semibold">Viajes</div>
                <div class="text-white/70 text-sm mt-1">Flujo, fases y cierres.</div>
              </div>
              <div class="rounded-xl p-4 border border-white/10 bg-white/5 hover:bg-white/[.08] transition">
                <div class="text-2xl mb-2"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="font-semibold">Pánico</div>
                <div class="text-white/70 text-sm mt-1">Incidentes con evidencia.</div>
              </div>
            </div>

            <div class="mt-6 grid grid-cols-3 gap-3">
              <div class="chip rounded-xl px-3 py-2 text-center">
                <div class="text-xs text-white/70">Activos</div>
                <div class="text-lg font-extrabold">--</div>
              </div>
              <div class="chip rounded-xl px-3 py-2 text-center">
                <div class="text-xs text-white/70">Viajes hoy</div>
                <div class="text-lg font-extrabold">--</div>
              </div>
              <div class="chip rounded-xl px-3 py-2 text-center">
                <div class="text-xs text-white/70">% Éxito</div>
                <div class="text-lg font-extrabold">--</div>
              </div>
            </div>

            <div class="mt-6 rounded-xl overflow-hidden border border-white/10">
              <div class="h-40 bg-[radial-gradient(circle_at_20%_30%,rgba(255,27,143,.18),transparent_40%),radial-gradient(circle_at_80%_70%,rgba(86,167,255,.18),transparent_40%)] flex items-center justify-center text-white/60">
                <span class="animate-floaty">Mapa / Heatmap próximamente</span>
              </div>
            </div>
          </div>

          <div class="absolute -bottom-8 -right-8 md:-right-10">
            <div class="w-28 h-28 md:w-32 md:h-32 rounded-full border border-white/15 flex items-center justify-center animate-spinSlow"
                 style="background: radial-gradient(100% 100% at 50% 50%, rgba(255,255,255,.06), transparent)">
              <div class="w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center"
                   style="background: radial-gradient(100% 100% at 50% 50%, rgba(255,27,143,.35), transparent)">
                <i class="fa-solid fa-award text-2xl text-white/90"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 pb-16">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="card-glass rounded-2xl p-6">
          <div class="text-2xl"><i class="fa-solid fa-wand-magic-sparkles text-brand"></i></div>
          <h3 class="mt-2 text-xl font-bold">UX quirúrgica</h3>
          <p class="text-white/70 mt-1">Acciones en 2–3 clics y todo a la vista. Velocidad > burocracia.</p>
        </div>
        <div class="card-glass rounded-2xl p-6">
          <div class="text-2xl"><i class="fa-solid fa-magnifying-glass-chart text-brand"></i></div>
          <h3 class="mt-2 text-xl font-bold">Observabilidad</h3>
          <p class="text-white/70 mt-1">Métricas clave, auditoría y trazabilidad nativas.</p>
        </div>
        <div class="card-glass rounded-2xl p-6">
          <div class="text-2xl"><i class="fa-solid fa-shield text-brand"></i></div>
          <h3 class="mt-2 text-xl font-bold">Seguridad real</h3>
          <p class="text-white/70 mt-1">Auth de Laravel + rol <b>admin</b> (Spatie). No hay puertas traseras.</p>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="relative z-10 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-6 md:px-10 py-8 text-sm text-white/60 flex flex-col md:flex-row items-center justify-between gap-3">
      <div>© {{ date('Y') }} RRB-Soluciones — Hecho con <span class="text-brand">♥</span> en Michoacán</div>
      <div class="flex items-center gap-4">
        @if (Route::has('admin.dashboard'))
          <a class="hover:text-white/90" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Panel</a>
        @else
          <a class="hover:text-white/90" href="{{ url('/admin') }}"><i class="fa-solid fa-gauge-high"></i> Panel</a>
        @endif

        @if (Route::has('login'))
          <a class="hover:text-white/90" href="{{ route('login') }}"><i class="fa-regular fa-user"></i> Login</a>
        @else
          <a class="hover:text-white/90" href="{{ url('/login') }}"><i class="fa-regular fa-user"></i> Login</a>
        @endif

        <a class="hover:text-white/90" href="https://github.com/Nasakareal/FlashRide" target="_blank" rel="noopener">
          <i class="fa-brands fa-github"></i> Repo
        </a>
      </div>
    </div>
  </footer>

  <!-- Konami Code → Confetti -->
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
  <script>
    (function(){
      const seq = [38,38,40,40,37,39,37,39,66,65];
      let pos = 0;
      window.addEventListener('keydown', (e)=>{
        pos = (e.keyCode === seq[pos]) ? pos+1 : 0;
        if(pos === seq.length){
          pos = 0;
          const end = Date.now() + 800;
          (function frame(){
            confetti({ particleCount: 4, spread: 80, startVelocity: 50, scalar: .9, origin: { x: Math.random(), y: Math.random() - .2 }});
            if(Date.now() < end) requestAnimationFrame(frame);
          })();
        }
      });
    })();
  </script>
</body>
</html>
