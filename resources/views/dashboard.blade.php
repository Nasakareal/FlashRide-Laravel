{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header')
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Dashboard</h1>
      <p class="text-white/60 mt-1 text-sm">Resumen operativo de la flota y accesos rápidos.</p>
    </div>
    <div class="hidden md:flex items-center gap-2">
      <a href="{{ route('admin.dashboard') }}" class="btn-neo px-4 py-2 rounded-lg font-semibold">
        <i class="fa-solid fa-gauge-high"></i> Panel Admin
      </a>
    </div>
  </div>
@endsection

@section('content')
  {{-- Mensaje principal (equivalente al “You’re logged in!”) --}}
  <div class="card-glass rounded-2xl border border-white/10 p-5 mb-8">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center"
           style="background: radial-gradient(100% 100% at 30% 20%, rgba(255,27,143,.9), rgba(255,27,143,.55));">
        <i class="fa-regular fa-circle-check text-white text-lg"></i>
      </div>
      <div>
        <div class="font-bold">¡Sesión iniciada!</div>
        <div class="text-white/70 text-sm">Bienvenido de vuelta, {{ Auth::user()->name ?? 'admin' }}.</div>
      </div>
    </div>
  </div>

  {{-- Tarjetas de KPIs --}}
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card-glass rounded-xl p-5 border border-white/10">
      <div class="text-white/70 text-xs">Conductores activos</div>
      <div class="mt-2 text-2xl font-extrabold tracking-tight">--</div>
      <div class="mt-3 text-white/60 text-xs flex items-center gap-2">
        <i class="fa-solid fa-users-gear"></i> Última hora
      </div>
    </div>
    <div class="card-glass rounded-xl p-5 border border-white/10">
      <div class="text-white/70 text-xs">Viajes hoy</div>
      <div class="mt-2 text-2xl font-extrabold tracking-tight">--</div>
      <div class="mt-3 text-white/60 text-xs flex items-center gap-2">
        <i class="fa-solid fa-route"></i> Corte 23:59
      </div>
    </div>
    <div class="card-glass rounded-xl p-5 border border-white/10">
      <div class="text-white/70 text-xs">% Éxito</div>
      <div class="mt-2 text-2xl font-extrabold tracking-tight">--</div>
      <div class="mt-3 text-white/60 text-xs flex items-center gap-2">
        <i class="fa-solid fa-chart-line"></i> Últimas 24 h
      </div>
    </div>
    <div class="card-glass rounded-xl p-5 border border-white/10">
      <div class="text-white/70 text-xs">Alertas de pánico</div>
      <div class="mt-2 text-2xl font-extrabold tracking-tight">--</div>
      <div class="mt-3 text-white/60 text-xs flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i> En revisión
      </div>
    </div>
  </div>

  {{-- Panel doble: mapa placeholder + actividad reciente --}}
  <div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 card-glass rounded-2xl border border-white/10 overflow-hidden">
      <div class="flex items-center justify-between p-5 border-b border-white/10">
        <div class="font-semibold"><i class="fa-solid fa-map-location-dot"></i> Telemetría</div>
        <span class="text-xs text-white/60">Mapa / Heatmap próximamente</span>
      </div>
      <div class="h-64 md:h-80 bg-[radial-gradient(circle_at_20%_30%,rgba(255,27,143,.12),transparent_40%),radial-gradient(circle_at_80%_70%,rgba(86,167,255,.12),transparent_40%)] flex items-center justify-center text-white/60">
        <span class="animate-floaty">Aquí irá el mapa en tiempo real</span>
      </div>
    </div>

    <div class="card-glass rounded-2xl border border-white/10">
      <div class="p-5 border-b border-white/10 font-semibold">
        <i class="fa-solid fa-clock-rotate-left"></i> Actividad reciente
      </div>
      <ul class="divide-y divide-white/10">
        <li class="p-5 text-sm">
          <div class="font-semibold">You’re logged in!</div>
          <div class="text-white/60">Ingreso correcto al sistema.</div>
        </li>
        <li class="p-5 text-sm">
          <div class="font-semibold">Placeholder</div>
          <div class="text-white/60">Eventos operativos próximos.</div>
        </li>
        <li class="p-5 text-sm">
          <div class="font-semibold">Placeholder</div>
          <div class="text-white/60">Logs, auditoría, etc.</div>
        </li>
      </ul>
    </div>
  </div>
@endsection
