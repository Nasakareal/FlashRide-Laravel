@extends('layouts.app')
@section('title','Nueva tarjeta teleférico')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h4 mb-1 fw-black">Nueva tarjeta teleférico</h1>
    <div class="small" style="color:var(--muted);">
      Registrar nueva tarjeta entregada
    </div>
  </div>

  <a href="{{ route('admin.tarjetas-teleferico.index') }}" class="btn btn-outline-secondary px-4">
    <i class="fa-solid fa-arrow-left me-2"></i> Volver
  </a>
</div>

<div class="card-soft">
  <div class="p-3 p-lg-4">

    <form method="POST" action="{{ route('admin.tarjetas-teleferico.store') }}">
      @csrf

      @include('admin.tarjetas_teleferico._form')

      <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.tarjetas-teleferico.index') }}" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>

        <button class="btn btn-brand px-4">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar tarjeta
        </button>
      </div>

    </form>

  </div>
</div>

@endsection
