<header class="relative z-20">
  <div class="max-w-7xl mx-auto px-6 md:px-10 py-5 flex items-center justify-between">
    {{-- Brand --}}
    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center drop-shadow-glow transition-transform group-hover:scale-[1.03]"
           style="background: radial-gradient(100% 100% at 30% 20%, rgba(255,27,143,.9), rgba(255,27,143,.55));">
        <i class="fa-solid fa-taxi text-white text-lg"></i>
      </div>
      <div class="leading-tight">
        <div class="font-extrabold text-lg tracking-tight">Taxi Seguro</div>
        <div class="text-[11px] text-white/60 -mt-0.5">Admin Panel</div>
      </div>
    </a>

    {{-- Desktop nav --}}
    <nav class="hidden md:flex items-center gap-3">
      <span class="px-3 py-1.5 rounded-full text-xs uppercase tracking-wider border border-white/15 text-white/80">v1</span>

      @auth
        {{-- Si es ADMIN: menú del panel --}}
        @role('admin')
          <div class="flex items-center gap-1">
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 hover:underline">Panel</a>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 hover:underline">Usuarios</a>
            <a href="{{ route('admin.drivers.index') }}" class="px-3 py-2 hover:underline">Conductores</a>
            <a href="{{ route('admin.vehicles.index') }}" class="px-3 py-2 hover:underline">Vehículos</a>
            <a href="{{ route('admin.trips.index') }}" class="px-3 py-2 hover:underline">Viajes</a>
            <a href="{{ route('admin.panic.index') }}" class="px-3 py-2 hover:underline">Pánico</a>
            <a href="{{ route('admin.assignments.index') }}" class="px-3 py-2 hover:underline">Asignaciones</a>
            <a href="{{ route('admin.itineraries.index') }}" class="px-3 py-2 hover:underline">Itinerarios</a>
            <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 hover:underline">Reportes</a>
            <a href="{{ route('admin.settings.index') }}" class="px-3 py-2 hover:underline">Ajustes</a>
          </div>
        @endrole

        {{-- Link rápido a Panel (si existe la ruta) --}}
        @if (Route::has('admin.dashboard'))
          <a href="{{ route('admin.dashboard') }}" class="btn-neo px-4 py-2 rounded-lg font-semibold">Ir al Panel</a>
        @else
          <a href="{{ url('/flashride/admin') }}" class="btn-neo px-4 py-2 rounded-lg font-semibold">Ir al Panel</a>
        @endif

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button class="px-4 py-2 rounded-lg font-semibold border border-white/10 hover:bg-white/10 transition">
            Salir
          </button>
        </form>
      @endauth

      @guest
        @if (Route::has('login'))
          <a href="{{ route('login') }}" class="btn-neo px-4 py-2 rounded-lg font-semibold">Iniciar sesión</a>
        @else
          {{-- Fallback absoluto por si la ruta nombrada no existe en algún entorno --}}
          <a href="{{ url('/flashride/login') }}" class="btn-neo px-4 py-2 rounded-lg font-semibold">Iniciar sesión</a>
        @endif
      @endguest
    </nav>

    {{-- Mobile: botón simple (opcional) --}}
    <div class="md:hidden">
      @auth
        <a href="@role('admin'){{ route('admin.dashboard') }}@else{{ route('dashboard') }}@endrole"
           class="btn-neo px-3 py-2 rounded-lg font-semibold">Panel</a>
      @else
        <a href="{{ Route::has('login') ? route('login') : url('/flashride/login') }}"
           class="btn-neo px-3 py-2 rounded-lg font-semibold">Entrar</a>
      @endauth
    </div>
  </div>
</header>
