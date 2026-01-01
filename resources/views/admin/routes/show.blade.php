@extends('layouts.app')
@section('title','Detalle de ruta')

@section('content')

@php
  $routeColor = strtoupper(ltrim((string)($route->color ?? '0080FF'), '#'));
  if (!preg_match('/^[0-9A-F]{6}$/', $routeColor)) $routeColor = '0080FF';

  $textColor = strtoupper(ltrim((string)($route->text_color ?? 'FFFFFF'), '#'));
  if (!preg_match('/^[0-9A-F]{6}$/', $textColor)) $textColor = 'FFFFFF';

  $badgeState = $route->is_active ? 'bg-success' : 'bg-secondary';
@endphp

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Ruta #{{ $route->id }}</h1>
    <div class="small" style="color:var(--muted);">
      {{ $route->short_name }} — {{ $route->long_name }}
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary px-4">
      <i class="fa-solid fa-arrow-left me-2"></i> Volver
    </a>

    <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-outline-success px-4">
      <i class="fa-regular fa-pen-to-square me-2"></i> Editar
    </a>

    @if($route->is_active)
      <form action="{{ route('admin.routes.deactivate', $route) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-outline-warning px-4">
          <i class="fa-solid fa-ban me-2"></i> Desactivar
        </button>
      </form>
    @else
      <form action="{{ route('admin.routes.activate', $route) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-outline-primary px-4">
          <i class="fa-solid fa-circle-check me-2"></i> Activar
        </button>
      </form>
    @endif
  </div>
</div>

{{-- TOP CARDS --}}
<div class="row g-3 mb-4">

  {{-- INFO --}}
  <div class="col-12 col-lg-8">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
          <div>
            <div class="small" style="color:var(--muted);">Identidad</div>
            <div class="fw-bold">{{ $route->short_name }}</div>
            <div class="text-muted">{{ $route->long_name }}</div>
          </div>

          <div class="text-end">
            <span class="badge {{ $badgeState }}">
              {{ $route->is_active ? 'Activa' : 'Inactiva' }}
            </span>
            <div class="mt-2">
              <span class="badge bg-dark text-uppercase">{{ $route->vehicle_type }}</span>
            </div>
          </div>
        </div>

        {{-- PREVIEW --}}
        <div class="p-3 rounded-3 border"
             style="background:#{{ $routeColor }}; color:#{{ $textColor }};">
          <div class="fw-bold">Preview</div>
          <div class="small">{{ $route->short_name }} — {{ $route->long_name }}</div>
          <div class="small opacity-75">#{{ $routeColor }} · #{{ $textColor }}</div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-12 col-lg-6">
            <div class="small" style="color:var(--muted);">Polyline</div>
            @if(!empty($route->polyline))
              <div class="small text-muted">
                <i class="fa-solid fa-check me-1"></i> Cargada ({{ strlen((string)$route->polyline) }} chars)
              </div>
            @else
              <div class="small text-muted">
                <i class="fa-regular fa-circle-xmark me-1"></i> Vacía
              </div>
            @endif
          </div>

          <div class="col-12 col-lg-6">
            <div class="small" style="color:var(--muted);">Paradas (stops_json)</div>
            @php
              $stops = $route->stops_json;
              $countStops = is_array($stops) ? count($stops) : null;
            @endphp

            @if(is_array($stops))
              <div class="small text-muted">
                <i class="fa-solid fa-location-dot me-1"></i> {{ $countStops }} parada(s)
              </div>
            @elseif(!empty($route->stops_json))
              <div class="small text-muted">
                <i class="fa-solid fa-location-dot me-1"></i> Guardado (texto)
              </div>
            @else
              <div class="small text-muted">
                <i class="fa-regular fa-circle-xmark me-1"></i> Vacío
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- STATS --}}
  <div class="col-12 col-lg-4">
    <div class="card-soft h-100">
      <div class="p-3 p-lg-4">
        <div class="small" style="color:var(--muted);">Unidades vinculadas</div>

        <div class="d-flex align-items-center justify-content-between mt-2">
          <div class="fw-semibold">Directas</div>
          <div class="fw-black">{{ (int)($route->vehicles_count ?? 0) }}</div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-2">
          <div class="fw-semibold">Asignadas (activas)</div>
          <div class="fw-black">{{ (int)($route->active_vehicle_assignments_count ?? 0) }}</div>
        </div>

        <hr>

        <div class="small text-muted">
          <b>Directas</b>:<br>
          <b>Asignadas</b>:
        </div>
      </div>
    </div>
  </div>
</div>

{{-- TABLE: DIRECT VEHICLES --}}
<div class="card-soft overflow-hidden mb-4">
  <div class="p-3 p-lg-4 border-bottom d-flex align-items-center justify-content-between">
    <div>
      <div class="fw-bold">Vehículos directos</div>
      <div class="small" style="color:var(--muted);">vehicles.transit_route_id = {{ $route->id }}</div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="ps-4">ID</th>
          <th>Placas</th>
          <th>Vehículo</th>
          <th>Dueño</th>
          <th class="text-end pe-4">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($directVehicles as $v)
          <tr>
            <td class="ps-4 fw-semibold">{{ $v->id }}</td>

            <td class="text-uppercase fw-bold">{{ $v->plate_number }}</td>

            <td>
              <div class="fw-semibold">{{ $v->brand }} {{ $v->model }}</div>
              <div class="small text-muted">{{ $v->vehicle_type }} · {{ $v->color }}</div>
            </td>

            <td>
              @if($v->user)
                <div class="fw-semibold">{{ $v->user->name }}</div>
                <div class="small text-muted">
                  {{ $v->user->phone ?? '' }}
                  @if(!empty($v->user->email)) · {{ $v->user->email }} @endif
                </div>
              @else
                <span class="text-muted small">Sin dueño</span>
              @endif
            </td>

            <td class="text-end pe-4">
              <a href="{{ route('admin.vehicles.show', $v) }}" class="btn btn-outline-info btn-sm">
                <i class="fa-regular fa-eye"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center py-4 text-muted">Sin vehículos directos.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- TABLE: ASSIGNED VEHICLES (PIVOT) --}}
<div class="card-soft overflow-hidden">
  <div class="p-3 p-lg-4 border-bottom d-flex align-items-center justify-content-between">
    <div>
      <div class="fw-bold">Vehículos asignados</div>
      <div class="small" style="color:var(--muted);">
        route_vehicle_assignments (active=1 y ended_at NULL)
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="ps-4">Asignación</th>
          <th>Vehículo</th>
          <th>Placas</th>
          <th>Inicio</th>
          <th>Notas</th>
          <th class="text-end pe-4">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($assignedVehicles as $a)
          @php $v = $a->vehicle; @endphp
          <tr>
            <td class="ps-4 fw-semibold">#{{ $a->id }}</td>

            <td>
              @if($v)
                <div class="fw-semibold">{{ $v->brand }} {{ $v->model }}</div>
                <div class="small text-muted">{{ $v->vehicle_type }} · {{ $v->color }}</div>
              @else
                <span class="text-muted small">Vehículo no disponible</span>
              @endif
            </td>

            <td class="text-uppercase fw-bold">
              {{ $v->plate_number ?? '—' }}
            </td>

            <td class="text-muted">
              {{ optional($a->started_at)->format('Y-m-d H:i') ?? '—' }}
            </td>

            <td class="text-muted">
              {{ $a->notes ?? '—' }}
            </td>

            <td class="text-end pe-4">
              @if($v)
                <a href="{{ route('admin.vehicles.show', $v) }}" class="btn btn-outline-info btn-sm">
                  <i class="fa-regular fa-eye"></i>
                </a>
              @else
                <span class="text-muted small">—</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-4 text-muted">Sin asignaciones activas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- MAPA --}}
<div class="card-soft overflow-hidden mb-4">
  <div class="p-3 p-lg-4 border-bottom d-flex align-items-center justify-content-between">
    <div>
      <div class="fw-bold">Mapa de la ruta</div>
      <div class="small" style="color:var(--muted);">
        Se dibuja desde <code>polyline</code>
      </div>
    </div>

    @if(empty($route->polyline))
      <span class="badge bg-warning text-dark">
        <i class="fa-solid fa-triangle-exclamation me-1"></i> Sin polyline
      </span>
    @endif
  </div>

  <div class="p-0">
    <div id="routeMap" style="height:420px; width:100%;"></div>
  </div>
</div>


@endsection

@push('scripts')
<script>
  (function () {
    const encoded = @json((string)($route->polyline ?? ''));

    // Si no hay polyline, mostramos un mapa centrado en Morelia
    const fallbackCenter = { lat: 19.705, lng: -101.194 };

    // Decoder simple para encoded polyline (Google)
    function decodePolyline(str) {
      let index = 0, lat = 0, lng = 0, coords = [];
      const len = str.length;

      while (index < len) {
        let b, shift = 0, result = 0;
        do {
          b = str.charCodeAt(index++) - 63;
          result |= (b & 0x1f) << shift;
          shift += 5;
        } while (b >= 0x20);
        const dlat = (result & 1) ? ~(result >> 1) : (result >> 1);
        lat += dlat;

        shift = 0; result = 0;
        do {
          b = str.charCodeAt(index++) - 63;
          result |= (b & 0x1f) << shift;
          shift += 5;
        } while (b >= 0x20);
        const dlng = (result & 1) ? ~(result >> 1) : (result >> 1);
        lng += dlng;

        coords.push({ lat: lat / 1e5, lng: lng / 1e5 });
      }
      return coords;
    }

    window.__initRouteMap = function () {
      const el = document.getElementById('routeMap');
      if (!el || typeof google === 'undefined' || !google.maps) return;

      const map = new google.maps.Map(el, {
        center: fallbackCenter,
        zoom: 12,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: true,
      });

      if (!encoded || encoded.length < 5) return;

      let path = [];
      try {
        path = decodePolyline(encoded);
      } catch (e) {
        console.warn('Polyline inválida', e);
        return;
      }
      if (!path.length) return;

      const routeLine = new google.maps.Polyline({
        path,
        geodesic: true,
        strokeOpacity: 1,
        strokeWeight: 5,
        // Nota: no fijo color aquí; usamos el de tu BD si existe
        strokeColor: '#{{ strtoupper(ltrim((string)($route->color ?? '0080FF'), '#')) }}',
      });

      routeLine.setMap(map);

      // Fit bounds para que se vea completa
      const bounds = new google.maps.LatLngBounds();
      path.forEach(p => bounds.extend(p));
      map.fitBounds(bounds);

      // Marcadores de inicio/fin (opcional)
      new google.maps.Marker({ position: path[0], map, title: 'Inicio' });
      new google.maps.Marker({ position: path[path.length - 1], map, title: 'Fin' });
    };

    // Cargar Google Maps JS dinámicamente (una sola vez)
    function loadGoogleMaps() {
      if (typeof google !== 'undefined' && google.maps) {
        window.__initRouteMap();
        return;
      }

      const existing = document.querySelector('script[data-gmaps="1"]');
      if (existing) return;

      const s = document.createElement('script');
      s.src = "https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=__initRouteMap";
      s.async = true;
      s.defer = true;
      s.setAttribute('data-gmaps','1');
      document.head.appendChild(s);
    }

    loadGoogleMaps();
  })();
</script>
@endpush
