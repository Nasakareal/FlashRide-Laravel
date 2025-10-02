<footer class="relative z-10 border-t border-white/10">
  <div class="max-w-7xl mx-auto px-6 md:px-10 py-8 text-sm text-white/60 flex flex-col md:flex-row items-center justify-between gap-3">
    <div>© {{ date('Y') }} FlashRide — Hecho con <span class="text-brand">♥</span> en Michoacán</div>
    <div class="flex items-center gap-4">
      @if (Route::has('admin.dashboard'))
        <a class="hover:text-white/90" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Panel</a>
      @else
        <a class="hover:text-white/90" href="{{ url('/admin') }}"><i class="fa-solid fa-gauge-high"></i> Panel</a>
      @endif

      @if (Route::has('login'))
        <a class="hover:text-white/90" href="{{ route('login') }}"><i class="fa-regular fa-user"></i> Login</a>
      @else
        <a class="hover:text-white/90" href="{{ rtrim(config('app.url'), '/') . '/flashride/login' }}"><i class="fa-regular fa-user"></i> Login</a>
      @endif

      <a class="hover:text-white/90" href="https://github.com/Nasakareal/FlashRide" target="_blank" rel="noopener">
        <i class="fa-brands fa-github"></i> Repo
      </a>
    </div>
  </div>
</footer>
