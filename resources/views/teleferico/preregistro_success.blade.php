{{-- resources/views/teleferico/preregistro_success.blade.php --}}
@extends('layouts.app')

@section('title', 'Preregistro exitoso')

@section('page-header')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card-soft p-5 text-center">

            <div class="mb-4">
                <i class="fa-solid fa-circle-check" style="font-size:60px; color: var(--brand);"></i>
            </div>

            <h1 class="mb-3">
                Preregistro <span style="color: var(--brand);">Exitoso</span>
            </h1>

            <p class="mb-4" style="color: var(--muted);">
                Tu solicitud fue registrada correctamente.
            </p>

            <div class="p-3 rounded-3 mb-4" style="background:#f9fafb; border:1px solid var(--border);">
                <div class="fw-semibold">
                    Tu tarjeta se encuentra en estatus <strong>INACTIVA</strong>.
                </div>
                <div class="small" style="color:var(--muted);">
                    Se activará al momento de la entrega física.
                </div>
            </div>

            <a href="{{ url('/') }}" class="btn btn-brand px-4">
                <i class="fa-solid fa-house me-2"></i> Volver al inicio
            </a>

        </div>
    </div>
</div>
@endsection

@section('content')
<section class="mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-soft p-4 text-center">

                <div class="fw-black mb-2">¿Qué sigue?</div>

                <div class="small" style="color:var(--muted);">
                    Acude al punto de entrega del teleférico para recibir tu tarjeta.
                    Ahí se validará tu información y se activará en el sistema.
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
