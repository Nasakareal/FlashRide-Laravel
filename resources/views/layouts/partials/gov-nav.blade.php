<nav class="navbar gov-nav fixed-top navbar-expand-lg">
  <div class="container-fluid px-3 px-lg-4">

    {{-- LOGO --}}
    <a class="navbar-brand brand-logo d-flex align-items-center gap-3"
       href="https://transporte.michoacan.gob.mx" target="_blank" rel="noopener">
      <img src="https://michoacan.gob.mx/cdn/img/logos/dependencias/transporte.svg"
           alt="Transporte Michoacán">
      <span class="d-none d-md-inline fw-black"
            style="font-weight:900; letter-spacing:-.02em;">
        Taxi Seguro
      </span>
    </a>

    {{-- TOGGLER --}}
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarGov"
            aria-controls="navbarGov"
            aria-expanded="false"
            aria-label="Abrir navegación">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- NAV --}}
    <div class="collapse navbar-collapse" id="navbarGov">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        {{-- LINKS INSTITUCIONALES (SIN INICIO) --}}
        <li class="nav-item">
          <a class="nav-link"
             href="https://dif.michoacan.gob.mx/categoria/noticias/"
             target="_blank" rel="noopener">
            Noticias
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link"
             href="http://tramites.michoacan.gob.mx"
             target="_blank" rel="noopener">
            Trámites
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link"
             target="_blank" rel="noopener"
             href="https://consultapublicamx.plataformadetransparencia.org.mx/vut-web/?idSujetoObigadoParametro=3354&amp;idEntidadParametro=16&amp;idSectorParametro=21">
            Transparencia
          </a>
        </li>

        {{-- SEPARADOR --}}
        <li class="nav-item d-none d-lg-block">
          <span class="mx-2" style="color:#d1d5db;">|</span>
        </li>

        {{-- REDES --}}
        <li class="nav-item">
          <a class="nav-link"
             href="https://www.facebook.com/MichoacanCocotra/"
             target="_blank" rel="noopener" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link"
             href="https://twitter.com/cocotramich"
             target="_blank" rel="noopener" aria-label="X/Twitter">
            <i class="fab fa-twitter"></i>
          </a>
        </li>

        {{-- ACCIONES --}}
        <li class="nav-item mt-2 mt-lg-0 ms-lg-2 d-flex gap-2">

          @auth
            {{-- PANEL -> SIEMPRE DASHBOARD PUBLICO --}}
            <a class="btn btn-brand btn-sm px-3 py-2"
               href="{{ url('/flashride/dashboard') }}">
              <i class="fa-solid fa-gauge-high me-2"></i> Panel
            </a>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-brand btn-sm px-3 py-2">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Salir
              </button>
            </form>
          @else
            {{-- LOGIN --}}
            <a class="btn btn-outline-brand btn-sm px-3 py-2"
               href="{{ route('login') }}">
              <i class="fa-regular fa-user me-2"></i> Iniciar sesión
            </a>
          @endauth

        </li>

      </ul>
    </div>

  </div>
</nav>

@push('styles')
<style>
  .navbar-toggler { border-color: rgba(17,24,39,.18); }
  .navbar-toggler-icon{
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2817,24,39,0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
  }
</style>
@endpush
