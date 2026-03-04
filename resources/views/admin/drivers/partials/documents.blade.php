@php
  $p = $driver->driverProfile ?? null;

  $typeLabels = [
    'TITULO_CONCESION'         => 'Título de concesión',
    'TARJETA_CIRCULACION'      => 'Tarjeta de circulación',
    'POLIZA_SEGURO'            => 'Póliza de seguro',
    'DICTAMEN_FISICO_MECANICO' => 'Dictamen físico-mecánico',
    'MANIFESTACION_PROTESTA'   => 'Manifestación bajo protesta',
    'TARJETA_CONTROL'          => 'Tarjeta de control',
    'LICENCIA_CONDUCIR'        => 'Licencia de conducir (PDF)',
    'INE_FRENTE'               => 'INE (frente)',
    'INE_REVERSO'              => 'INE (reverso / referencia)',
    'COMPROBANTE_DOMICILIO'    => 'Comprobante de domicilio',
  ];

  $requiredTypes = array_keys($typeLabels);

  $docs = $p?->documents ?? collect();

  $activeByType = [];
  foreach ($docs as $d) {
    if (!empty($d->type) && (int)($d->is_active ?? 0) === 1) {
      $activeByType[$d->type] = $d;
    }
  }

  $missing = [];
  foreach ($requiredTypes as $t) {
    if (empty($activeByType[$t])) $missing[] = $t;
  }
@endphp

<div class="card-soft mt-4">
  <div class="p-3 p-lg-4">

    <div class="d-flex align-items-center justify-content-between mb-2">
      <div class="fw-bold">Documentos (PDF)</div>
      <div class="small text-muted">Sube documentos por tipo</div>
    </div>

    @if(!$p)
      <div class="text-muted small">
        Este conductor aún no tiene expediente. Guarda primero y vuelve a intentar.
      </div>
    @else

      <form class="row g-2 align-items-end mb-3"
            action="{{ route('admin.drivers.documents.store', $driver) }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf

        <div class="col-12 col-lg-4">
          <label class="form-label small text-muted mb-1">Tipo</label>
          <select name="type" class="form-select @error('type') is-invalid @enderror">
            @foreach($typeLabels as $k => $label)
              <option value="{{ $k }}" @selected(old('type')===$k)>{{ $label }}</option>
            @endforeach
          </select>
          @error('type')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-lg-5">
          <label class="form-label small text-muted mb-1">Archivo PDF</label>
          <input type="file" name="file" accept="application/pdf" class="form-control @error('file') is-invalid @enderror">
          @error('file')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-lg-3">
          <button class="btn btn-brand w-100">
            <i class="fa-solid fa-upload me-2"></i> Subir
          </button>
        </div>
      </form>

      <div class="mb-3">
        <div class="small text-muted mb-1">Estado rápido</div>
        <div class="d-flex flex-wrap gap-2">
          @foreach($requiredTypes as $t)
            @if(!empty($activeByType[$t]))
              <span class="badge bg-success">{{ $typeLabels[$t] ?? $t }}</span>
            @else
              <span class="badge bg-secondary">{{ $typeLabels[$t] ?? $t }}</span>
            @endif
          @endforeach
        </div>
        @if(count($missing) > 0)
          <div class="small text-muted mt-2">
            Faltan: <b>{{ implode(', ', array_map(fn($t) => $typeLabels[$t] ?? $t, $missing)) }}</b>
          </div>
        @endif
      </div>

      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Archivo</th>
              <th>Subido</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($requiredTypes as $t)
              @php $d = $activeByType[$t] ?? null; @endphp
              <tr>
                <td class="fw-semibold">{{ $typeLabels[$t] ?? $t }}</td>

                <td class="small text-muted">
                  @if($d)
                    {{ $d->original_name ?? 'PDF' }}
                  @else
                    —
                  @endif
                </td>

                <td class="small text-muted">
                  @if($d)
                    {{ $d->uploaded_at?->format('d/m/Y H:i') ?? ($d->created_at?->format('d/m/Y H:i') ?? '—') }}
                  @else
                    —
                  @endif
                </td>

                <td class="text-end">
                  @if($d)
                    <a class="btn btn-outline-secondary btn-sm"
                       href="{{ route('admin.drivers.documents.download', [$driver, $d]) }}">
                      <i class="fa-solid fa-download"></i>
                    </a>

                    <form action="{{ route('admin.drivers.documents.destroy', [$driver, $d]) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Eliminar este documento?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-outline-danger btn-sm">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  @else
                    <span class="badge bg-secondary">Pendiente</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    @endif
  </div>
</div>
