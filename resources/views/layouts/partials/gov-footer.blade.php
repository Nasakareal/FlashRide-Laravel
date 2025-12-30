{{-- resources/views/layouts/partials/gov-footer.blade.php --}}
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
