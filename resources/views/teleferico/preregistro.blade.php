{{-- resources/views/teleferico/preregistro.blade.php --}}
@extends('layouts.app')

@section('title', 'Preregistro de tarjeta')

@section('page-header')
<div class="row align-items-center g-4 g-lg-5">
    <div class="col-lg-7">
        <div class="hero-badge mb-3">
            <i class="fa-solid fa-id-card" style="color: var(--brand);"></i>
            Teleférico · Registro ciudadano
        </div>

        <h1 class="display-5 display-md-4 mb-3">
            Preregistro de
            <span style="color: var(--brand);">Tarjeta</span>
        </h1>

        <p class="lead mb-4">
            Captura tus datos para solicitar tu tarjeta antes de la entrega oficial.
        </p>

        <div class="p-3 rounded-3 mb-3" style="background:#f9fafb; border:1px solid var(--border);">
            <div class="small" style="color:var(--muted);">Importante</div>
            <div class="fw-semibold">
                Tu registro se guardará como INACTIVO hasta recibir tu tarjeta física.
            </div>
        </div>

        <div class="small" style="color: var(--muted);">
            <i class="fa-solid fa-circle-info me-2"></i>
            La CURP se valida automáticamente para evitar duplicados.
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-soft p-4">
            <form method="POST" action="{{ route('teleferico.preregistro.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombres</label>
                    <input type="text" name="nombres" class="form-control" required value="{{ old('nombres') }}">
                    @error('nombres')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" required value="{{ old('apellidos') }}">
                    @error('apellidos')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">CURP</label>
                    <input type="text" name="curp" class="form-control" maxlength="18" required value="{{ old('curp') }}">
                    @error('curp')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Celular (opcional)</label>
                    <input type="text" name="celular" class="form-control" value="{{ old('celular') }}">
                    @error('celular')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-brand w-100">
                    <i class="fa-solid fa-paper-plane me-2"></i> Enviar preregistro
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<section class="mt-5">
    <div class="row g-3 g-lg-4">
        <div class="col-lg-4">
            <div class="card-soft p-4 h-100">
                <div class="icon-pill mb-3"><i class="fa-solid fa-file-signature"></i></div>
                <div class="fw-black mb-2">Captura rápida</div>
                <div class="small" style="color:var(--muted);">
                    Solo necesitas tus datos básicos para completar el preregistro.
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-soft p-4 h-100">
                <div class="icon-pill mb-3"><i class="fa-solid fa-database"></i></div>
                <div class="fw-black mb-2">Sin duplicados</div>
                <div class="small" style="color:var(--muted);">
                    La CURP se valida automáticamente en el sistema.
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-soft p-4 h-100">
                <div class="icon-pill mb-3"><i class="fa-solid fa-check-circle"></i></div>
                <div class="fw-black mb-2">Activación en sitio</div>
                <div class="small" style="color:var(--muted);">
                    Tu tarjeta se activa cuando se te entregue físicamente.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
