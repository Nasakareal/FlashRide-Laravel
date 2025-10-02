<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@hasSection('title')@yield('title') · @endif {{ config('app.name', 'FlashRide') }}</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand:'#FF1B8F', brandDark:'#E0147E', ink:'#0B0B0C' },
          dropShadow: { glow:'0 0 25px rgba(255,27,143,.45)' },
          keyframes: {
            floaty:{'0%,100%':{transform:'translateY(0)'},'50%':{transform:'translateY(-6px)'}},
            gradient:{'0%,100%':{'background-position':'0% 50%'},'50%':{'background-position':'100% 50%'}},
          },
          animation: {
            floaty:'floaty 6s ease-in-out infinite',
            gradient:'gradient 14s ease infinite',
          }
        }
      }
    }
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  @stack('head')
  <style>
    html,body{height:100%}
    body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; background:#060608; color:#EDEDED}
    .card-glass{background:linear-gradient(180deg,rgba(255,255,255,.08),rgba(255,255,255,.04));border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(10px)}
    .btn-neo{border:1px solid rgba(255,255,255,.12);background:radial-gradient(120% 120% at 10% 10%, rgba(255,27,143,.25), rgba(255,255,255,.03));box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 10px 30px rgba(255,27,143,.18);transition:.2s ease}
    .btn-neo:hover{transform:translateY(-2px);box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 16px 40px rgba(255,27,143,.32)}
  </style>
</head>
<body class="h-full overflow-x-hidden">
  {{-- BG suave para guest --}}
  <div aria-hidden="true" class="pointer-events-none fixed inset-0">
    <div class="absolute -top-1/3 -left-1/3 w-[70vw] h-[70vw] rounded-full blur-3xl opacity-30"
         style="background: radial-gradient(45% 45% at 50% 50%, rgba(255,27,143,.45), transparent 60%);"></div>
    <div class="absolute -bottom-1/3 -right-1/3 w-[70vw] h-[70vw] rounded-full blur-3xl opacity-30"
         style="background: radial-gradient(50% 50% at 50% 50%, rgba(86,167,255,.30), transparent 60%);"></div>
    <div class="absolute inset-0 bg-[length:300%_300%] animate-gradient opacity-25"
         style="background-image: linear-gradient(120deg, rgba(255,27,143,.12), rgba(86,167,255,.10), rgba(255,27,143,.12));"></div>
  </div>

  {{-- NAV guest (compacta) --}}
  @include('layouts.partials.nav')

  {{-- Contenido --}}
  <main class="relative z-10 min-h-[calc(100vh-160px)]">
    @yield('content')
  </main>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')

  @stack('scripts')
</body>
</html>
