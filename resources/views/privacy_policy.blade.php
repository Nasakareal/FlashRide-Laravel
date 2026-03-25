{{-- resources/views/privacy-policy.blade.php --}}
@extends('layouts.app')

@section('title', 'Política de Privacidad')

@section('page-header')
    <div class="row align-items-center g-4 g-lg-5">
        <div class="col-lg-7">
            <div class="hero-badge mb-3">
                <i class="fa-solid fa-shield-halved" style="color: var(--brand);"></i>
                Información legal · Protección de datos
            </div>

            <h1 class="display-5 display-md-4 mb-3">
                Política de
                <span style="color: var(--brand);">Privacidad</span>
            </h1>

            <p class="lead mb-4">
                Conoce cómo Taxi Seguro recopila, utiliza, almacena y protege la información
                de usuarios, conductores y personal autorizado dentro de la plataforma.
            </p>

            <div class="d-flex flex-wrap gap-2">
                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <i class="fa-solid fa-lock me-2"></i> Uso seguro
                </span>
                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <i class="fa-solid fa-location-dot me-2"></i> Ubicación controlada
                </span>
                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <i class="fa-solid fa-user-shield me-2"></i> Acceso autorizado
                </span>
            </div>

            <div class="mt-3 small" style="color: var(--muted);">
                <i class="fa-solid fa-circle-info me-2"></i>
                Última actualización: {{ now()->format('d/m/Y') }}
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-soft p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-pill">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div>
                        <div class="fw-black" style="font-weight:900;">Resumen</div>
                        <div class="small" style="color:var(--muted);">
                            Documento informativo para App Store y usuarios
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                            <div class="small" style="color:var(--muted);">Datos principales</div>
                            <div class="fw-semibold">Nombre, correo, teléfono, ubicación y datos operativos</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                            <div class="small" style="color:var(--muted);">Uso</div>
                            <div class="fw-semibold">Operación de viajes, seguridad, soporte y trazabilidad</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:#f9fafb; border:1px solid var(--border);">
                            <div class="small" style="color:var(--muted);">Acceso</div>
                            <div class="fw-semibold">Limitado a usuarios y procesos autorizados</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="small" style="color:var(--muted);">
                    <i class="fa-solid fa-envelope me-2"></i>
                    Contacto: contacto@rrb-soluciones.com
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">1. Responsable del tratamiento</h2>
            <p style="color:var(--muted);">
                Taxi Seguro es una plataforma orientada a la administración y seguimiento de viajes,
                conductores, vehículos e incidencias. El responsable del tratamiento de la información
                es el equipo operador y administrador del sistema, encargado del backend, control de acceso
                y soporte técnico.
            </p>
        </div>
    </section>

    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">2. Información que podemos recopilar</h2>
            <p style="color:var(--muted);">
                Dependiendo del tipo de usuario y de las funciones habilitadas, la aplicación puede recopilar:
            </p>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Datos de cuenta</div>
                        <div class="small" style="color:var(--muted);">
                            Nombre, correo electrónico, número telefónico, identificadores internos
                            y datos necesarios para autenticación y operación.
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Datos de autenticación</div>
                        <div class="small" style="color:var(--muted);">
                            Tokens de sesión y registros técnicos relacionados con inicio de sesión
                            y seguridad de acceso.
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Ubicación</div>
                        <div class="small" style="color:var(--muted);">
                            Coordenadas y datos de ubicación cuando el usuario concede permisos y
                            la funcionalidad lo requiere para viajes, seguimiento o seguridad.
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Información operativa</div>
                        <div class="small" style="color:var(--muted);">
                            Viajes, incidencias, vehículos, conductores, reportes, evidencias,
                            archivos o fotografías cuando aplique.
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Datos técnicos</div>
                        <div class="small" style="color:var(--muted);">
                            Tipo de dispositivo, versión de la app, registros de error y datos mínimos
                            necesarios para diagnóstico y estabilidad.
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Notificaciones</div>
                        <div class="small" style="color:var(--muted);">
                            Tokens de dispositivo para envío de alertas, avisos del sistema
                            y comunicaciones operativas si la función está habilitada.
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 rounded-3" style="background: rgba(0,0,0,.03); border:1px solid var(--border);">
                <div class="small" style="color:var(--muted);">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    La plataforma no está diseñada para vender información personal ni usarla con fines publicitarios no autorizados.
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">3. Permisos del dispositivo</h2>

            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Internet</div>
                        <div class="small" style="color:var(--muted);">
                            Necesario para comunicarse con el servidor y sincronizar información.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Ubicación</div>
                        <div class="small" style="color:var(--muted);">
                            Utilizada para mostrar posiciones, asignar viajes, seguimiento y mejorar la experiencia operativa.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#f9fafb; border:1px solid var(--border);">
                        <div class="fw-black mb-2" style="font-weight:900;">Cámara / Archivos</div>
                        <div class="small" style="color:var(--muted);">
                            Requeridos para captura de evidencias, documentos o fotografías cuando corresponda.
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-4 mb-0" style="color:var(--muted);">
                El usuario puede administrar estos permisos desde la configuración de su dispositivo.
                Algunas funciones pueden no estar disponibles si los permisos son denegados.
            </p>
        </div>
    </section>

    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">4. Finalidades del uso de la información</h2>

            <ul class="mb-0" style="color:var(--muted); line-height: 1.9;">
                <li>Permitir autenticación y control de acceso.</li>
                <li>Registrar, administrar y consultar información de viajes, conductores y vehículos.</li>
                <li>Mostrar funcionalidades de ubicación, seguimiento y seguridad durante la operación.</li>
                <li>Generar reportes, historial, trazabilidad y evidencia de eventos.</li>
                <li>Enviar alertas, notificaciones y mensajes relacionados con el servicio.</li>
                <li>Detectar fallas, prevenir accesos no autorizados y mejorar el funcionamiento general de la aplicación.</li>
            </ul>
        </div>
    </section>

    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">5. Compartición de datos</h2>

            <p style="color:var(--muted);">
                No vendemos información personal. Los datos solo podrán ser tratados o compartidos
                cuando sea necesario para la operación del servicio, cumplimiento legal, soporte técnico
                o administración autorizada del sistema.
            </p>

            <ul class="mb-0" style="color:var(--muted); line-height: 1.9;">
                <li>Infraestructura y alojamiento necesarios para operar la plataforma.</li>
                <li>Personal autorizado para administración, soporte o revisión operativa.</li>
                <li>Autoridades competentes cuando exista obligación legal.</li>
            </ul>
        </div>
    </section>

    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">6. Conservación y seguridad de la información</h2>

            <p style="color:var(--muted);">
                La información se almacena en infraestructura controlada y se conserva durante el tiempo
                necesario para la operación, soporte, auditoría, cumplimiento institucional o requerimientos legales.
            </p>

            <p class="mb-0" style="color:var(--muted);">
                Aplicamos medidas razonables de seguridad como autenticación, control de permisos,
                restricciones de acceso y prácticas técnicas orientadas a proteger la información.
                Aun así, ningún sistema es completamente infalible, por lo que se recomienda mantener
                credenciales seguras y no compartir accesos.
            </p>
        </div>
    </section>

    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">7. Menores de edad</h2>
            <p class="mb-0" style="color:var(--muted);">
                Taxi Seguro no está dirigido a menores de edad y no está diseñado como una aplicación infantil.
            </p>
        </div>
    </section>

    <section class="mb-4">
        <div class="card-soft p-4 p-lg-5">
            <h2 class="section-title mb-3">8. Cambios a esta política</h2>
            <p class="mb-0" style="color:var(--muted);">
                Esta Política de Privacidad puede actualizarse para reflejar cambios en la aplicación,
                el backend, la operación o los requisitos legales. La versión vigente estará publicada
                siempre en esta misma URL.
            </p>
        </div>
    </section>

    <section class="py-5 mt-5" style="background: var(--soft);" id="contacto">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="section-title mb-2">Contacto</h2>
                <p class="mb-0" style="color:var(--muted);">
                    Para dudas relacionadas con privacidad, datos o soporte técnico de la plataforma,
                    puedes comunicarte por los siguientes medios.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="mailto:contacto@rrb-soluciones.com" class="btn btn-brand px-4">
                    <i class="fa-solid fa-envelope me-2"></i> contacto@rrb-soluciones.com
                </a>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="card-soft p-4 h-100">
                    <div class="fw-black mb-2" style="font-weight:900;">Correo</div>
                    <div class="small" style="color:var(--muted);">
                        contacto@rrb-soluciones.com
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-soft p-4 h-100">
                    <div class="fw-black mb-2" style="font-weight:900;">Sitio web</div>
                    <div class="small" style="color:var(--muted);">
                        <a href="https://rrb-soluciones.com" target="_blank" rel="noopener">
                            https://rrb-soluciones.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
