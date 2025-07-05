@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Acta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

    <style>
        table th, table td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        .section-title {
            background-color: #d0e9f7;
            font-weight: bold;
            text-align: center;
            padding: 6px;
            margin-top: 1.5rem;
        }

        /* 🎯 PESTAÑAS MEJORADAS */
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        /* Pestañas inactivas */
        .nav-tabs .nav-link {
            color: #212529 !important; /* Letras negras */
            background-color: #e9ecef; /* Fondo gris claro */
            border: 1px solid #dee2e6;
            border-bottom: none;
            margin-right: 2px;
            transition: all 0.3s ease;
            font-weight: 500;
            cursor: pointer;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        /* Hover */
        .nav-tabs .nav-link:hover {
            background-color: #d6d8db; /* Gris un poco más oscuro */
            transform: translateY(-2px);
        }

        /* Pestaña activa */
        .nav-tabs .nav-link.active {
            color: #212529 !important; /* Letras negras */
            background-color: #ffffff !important; /* Fondo blanco */
            border-color: #dee2e6 #dee2e6 transparent !important;
            font-weight: 600;
            box-shadow: inset 0 3px 0 #0d6efd; /* Línea superior azul */
        }

        /* Contenido de las pestañas */
        .tab-content {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            min-height: 400px;
            padding: 1rem;
        }

        /* Estados de validación */
        .nav-tabs .nav-link.tab-error {
            color: #842029 !important;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }

        .nav-tabs .nav-link.tab-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-tabs .nav-link {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }
        }
    </style>

</head>
<body>
<div class="container py-4">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('actas.store') }}" id="actaForm">
        @csrf

        <!-- ENCABEZADO -->
        <table class="table text-center mb-4">
            <tr>
                <td style="width: 33%;">
                    <img src="{{ asset('prueba1.png') }}" style="max-height: 70px;">
                </td>
                <td style="width: 34%; font-weight: bold; font-size: 22px;">ACTA</td>
                <td style="width: 33%;">
                    <img id="logo-empresa"
                     src="{{ asset('storage/logos/' . ($empresaPorDefecto->logo_empresa ?? 'logo-placeholder.png')) }}"
                     alt="Logo Empresa" style="max-height: 70px;">
                </td>
            </tr>
            <tr class="small">
                <td>CÓDIGO: FRMGP010</td>
                <td>VIGENTE DESDE: 7/Dic/2023</td>
                <td>VERSIÓN: 6</td>
            </tr>
        </table>

        <!-- NAV TABS -->
        <ul class="nav nav-tabs" id="actaTabs" role="tablist">
            @php
                $tabs = [
                    'datos' => 'Datos Básicos', 
                    'asistentes' => 'Asistentes', 
                    'tab-objetivo' => 'Objetivo', 
                    'tab-desarrollo' => 'Desarrollo', 
                    'compromisos' => 'Compromisos', 
                    'resumen' => 'Resumen Cronológico'
                ];
            @endphp
            @foreach($tabs as $id => $label)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $id }}-tab" 
                            data-bs-toggle="tab" data-bs-target="#{{ $id }}" type="button" role="tab" 
                            data-required-tab="datos">{{ $label }}</button>
                </li>
            @endforeach
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content border p-3" id="actaTabContent">

            <!-- DATOS BÁSICOS -->
            <div class="tab-pane fade show active" id="datos" role="tabpanel">
                <div class="row g-3">
                    <!-- Tipo de Acta -->
                    <div class="col-md-4">
                        <label for="tipo_id" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <div class="d-flex">
                            <select id="tipo_id" name="tipo_id" class="form-select" required>
                                <option value="">Seleccione un tipo</option>
                                @forelse($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" {{ old('tipo_id') == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @empty
                                    <option value="" disabled>No hay tipos disponibles</option>
                                @endforelse
                            </select>
                           
                        </div>
                        @if($tipos->count() == 0)
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                No hay tipos de acta. <a href="{{ route('tipos-acta.create') }}" target="_blank">Crear primer tipo</a>
                            </div>
                        @endif
                    </div>

                    <!-- Número -->
                    <div class="col-md-4">
                        <label for="numero" class="form-label">Número</label>
                        <input id="numero" type="text" class="form-control" name="numero" 
                               value="(se genera automáticamente)" readonly>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input id="fecha" type="date" class="form-control" name="fecha" 
                               value="{{ old('fecha', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Hora Inicio -->
                    <div class="col-md-4">
                        <label for="hora_inicio" class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                        <input id="hora_inicio" type="time" name="hora_inicio" class="form-control" 
                               value="{{ old('hora_inicio') }}" required>
                    </div>

                    <!-- Hora Fin -->
                    <div class="col-md-4">
                        <label for="hora_fin" class="form-label">Hora Fin <span class="text-danger">*</span></label>
                        <input id="hora_fin" type="time" name="hora_fin" class="form-control" 
                               value="{{ old('hora_fin') }}" required>
                    </div>

                    <!-- Lugar -->
                    <div class="col-md-4">
                        <label for="lugar" class="form-label">Lugar <span class="text-danger">*</span></label>
                        <input id="lugar" type="text" name="lugar" class="form-control" 
                               value="{{ old('lugar') }}" placeholder="Indique lugar de la reunión" required>
                    </div>

                    <!-- Ciudad -->
                    <div class="col-md-6">
                        <label for="ciudad_id" class="form-label">Ciudad / País <span class="text-danger">*</span></label>
                        <select id="ciudad_id" name="ciudad_id" class="form-select" required>
                            <option value="">Seleccione una ciudad y país</option>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
                                    {{ $ciudad->nombre }} / {{ $ciudad->pais->nombre ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Empresa -->
                    <div class="col-md-4">
                        <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                        <div class="d-flex">
                            <select name="empresa_id" id="empresa_id" class="form-select" required onchange="updateLogoEmpresa(this.value)">
                                <option value="">Seleccione una empresa</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->id }}"
                                        data-logo="{{ $empresa->logo_empresa ? asset('storage/logos/' . $empresa->logo_empresa) : asset('images/logo-placeholder.png') }}"
                                        {{ (old('empresa_id', $empresaPorDefecto->id ?? '') == $empresa->id) ? 'selected' : '' }}>
                                        {{ $empresa->nombre }} – NIT: {{ $empresa->nit }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-primary ms-2" 
                                    data-bs-toggle="modal" data-bs-target="#modalCrearEmpresa" title="Agregar empresa">➕</button>
                        </div>
                    </div>

                    <!-- Facturable -->
                    <div class="col-md-4">
                        <label for="facturable" class="form-label">Facturable <span class="text-danger">*</span></label>
                        <select id="facturable" name="facturable" class="form-select" required>
                            <option value="1" {{ old('facturable', '1') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('facturable', '1') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Proyecto -->
                    <div class="col-md-8">
                        <label for="proyecto_id" class="form-label">Proyecto <span class="text-danger">*</span></label>
                        <select id="proyecto_id" name="proyecto_id" class="form-select" required>
                            <option value="">Seleccione un proyecto</option>
                            @foreach($proyectos as $proyecto)
                                <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                    {{ $proyecto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- ASISTENTES -->
            <div class="tab-pane fade" id="asistentes" role="tabpanel">
                <table class="table table-bordered" id="asistentes-table">
                    <thead>
                        <tr>
                            <th>Inicial</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Empresa</th>
                            <th>¿Asiste?</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="asistentes-body">
                        <tr>
                            <td><input type="text" name="asistentes[0][iniciales]" class="form-control"></td>
                            <td>
                                <select name="asistentes[0][persona_id]" class="form-select persona-select" required>
                                    <option value="">Seleccione *</option>
                                    @foreach ($personas as $persona)
                                        <option
                                            value="{{ $persona->id }}"
                                            data-iniciales="{{ $persona->iniciales }}"
                                            data-cargo="{{ $persona->cargo }}"
                                            data-empresa="{{ $persona->empresa?->id }}"
                                        >
                                            {{ $persona->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="asistentes[0][cargo]" class="form-control"></td>
                            <td>
                                <select name="asistentes[0][empresa_id]" class="form-select empresa-select">
                                    <option value="">Seleccione empresa</option>
                                    @foreach($empresas as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center"><input type="checkbox" name="asistentes[0][asistio]" value="1" checked></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-primary" id="add-asistente">➕ Agregar Asistente</button>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" 
                            data-bs-target="#modalCrearPersona" title="Agregar Persona">➕ Agregar Persona</button>
                </div>
            </div>

            <!-- OBJETIVO / AGENDA -->
            <div class="tab-pane fade" id="tab-objetivo" role="tabpanel">
                <table class="table table-bordered" style="font-size: 14px;">
                    <thead>
                        <tr class="table-primary text-center">
                            <th colspan="2">OBJETIVO / ASUNTO / AGENDA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 25%;"><strong>0. Asistencia:</strong></td>
                            <td>Validado en tabla asistentes</td>
                        </tr>
                        <tr>
                            <td><strong>1. Desarrollo de la reunión</strong></td>
                            <td>
                                <textarea id="objetivo" name="objetivo" class="form-control" rows="4" 
                                          placeholder="Ej: • Presentar informe para certificación&#10;• Entrega de certificado">{{ old('objetivo') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2. Programación de actividades</strong></td>
                            <td>
                                <textarea id="agenda" name="agenda" class="form-control" rows="2" 
                                          placeholder="Ej: N/A o describa la actividad...">{{ old('agenda') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3. ¿Se define próxima reunión?</strong></td>
                            <td>
                                <select name="define_proxima_reunion" id="define_proxima_reunion" class="form-select">
                                    <option value="no" selected>No</option>
                                    <option value="si">Sí</option>
                                </select>
                                <div id="fecha_proxima_reunion_container" class="mt-2" style="display: none;">
                                    <input type="date" name="proxima_reunion" class="form-control" placeholder="Fecha estimada">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- DESARROLLO Y CONCLUSIONES -->
            <div class="tab-pane fade" id="tab-desarrollo" role="tabpanel">
                <label for="desarrollo" class="form-label">Desarrollo de la reunión</label>
                <textarea id="desarrollo" name="desarrollo" class="form-control mb-3" rows="5" 
                          placeholder="Desarrollo de la reunión...">{{ old('desarrollo') }}</textarea>

                <label for="conclusiones" class="form-label">Conclusiones</label>
                <textarea id="conclusiones" name="conclusiones" class="form-control" rows="4" 
                          placeholder="Conclusiones...">{{ old('conclusiones') }}</textarea>
            </div>

            <!-- COMPROMISOS -->
            <div class="tab-pane fade" id="compromisos" role="tabpanel">
                <!-- CONTENEDOR PARA COMPROMISOS DEL ACTA ANTERIOR (AJAX) -->
                <div id="compromisos-anteriores-container" class="mb-3">
                    {{-- Aquí se insertará la tabla con JS --}}
                </div>

                <table class="table table-bordered" id="compromisos-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripción</th>
                            <th>Responsable</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input name="compromisos[0][descripcion]" class="form-control" required></td>
                            <td>
                                <select name="compromisos[0][persona_id]" class="form-control compromiso-responsable" required>
                                    <option value="">Seleccione *</option>
                                    <!-- opciones se llenan por JS -->
                                </select>
                            </td>
                            <td><input name="compromisos[0][fecha]" type="date" class="form-control" required></td>
                            <td>
                                <select name="compromisos[0][estado]" class="form-control">
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En proceso">En proceso</option>
                                    <option value="Cumplido">Cumplido</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-row">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-sm btn-primary" id="add-compromiso">
                    + Agregar Compromiso
                </button>

                <!-- FIRMAS DE GESTIÓN Y EMPRESA -->
                <div class="mt-5 border-top pt-4">
                    <h5 class="text-primary">Firmas de Representación</h5>

                    <div class="row">
                        <!-- Firma Gestión y Proyectos -->
                        <div class="col-md-6">
                            <h6>Gestión y Proyectos</h6>
                            <div class="mb-2">
                                <label>Persona:</label>
                                <select name="firmante_gp_id" id="select_firmante_gp" class="form-select" required>
                                    <option value="">Seleccione persona</option>
                                    @foreach($personas as $persona)
                                        <option value="{{ $persona->id }}"
                                            data-firma="{{ $persona->firma_path ? asset('storage/' . $persona->firma_path) : '' }}"
                                            data-empresa="{{ $persona->empresa?->nombre }}"
                                            data-tarjeta="{{ $persona->tarjeta_profesional }}"
                                            data-fecha-tarjeta="{{ $persona->fecha_tarjeta }}">
                                            {{ $persona->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>Tarjeta Profesional:</label>
                                    <input type="text" id="tarjeta_gp" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Fecha de Expedición:</label>
                                    <input type="text" id="fecha_tarjeta_gp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label>Empresa:</label>
                                <input type="text" id="empresa_gp" class="form-control" readonly>
                            </div>
                            <div class="mb-2 text-center">
                                <label>Firma:</label><br>
                                <img id="firma_gp_img" src="" alt="Firma" class="d-block mx-auto" style="max-height: 70px;">
                            </div>
                        </div>

                        <!-- Firma Empresa -->
                        <div class="col-md-6">
                            <h6>Empresa</h6>
                            <div class="mb-2">
                                <label>Persona:</label>
                                <select name="firmante_empresa_id" id="select_firmante_empresa" class="form-select" required>
                                    <option value="">Seleccione persona</option>
                                    @foreach($personas as $persona)
                                        <option value="{{ $persona->id }}"
                                            data-firma="{{ $persona->firma_path ? asset('storage/' . $persona->firma_path) : '' }}"
                                            data-empresa="{{ $persona->empresa?->nombre }}"
                                            data-cedula="{{ $persona->cedula }}"
                                            data-fecha-cedula="{{ $persona->fecha_expedicion_cedula }}"
                                            data-lugar-cedula="{{ $persona->lugar_expedicion_cedula }}">
                                            {{ $persona->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>Cédula:</label>
                                    <input type="text" id="cedula_empresa" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Lugar de Expedición:</label>
                                    <input type="text" id="lugar_cedula_empresa" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label>Empresa:</label>
                                <input type="text" id="empresa_empresa" class="form-control" readonly>
                            </div>
                            <div class="mb-2 text-center">
                                <label>Firma:</label><br>
                                <img id="firma_empresa_img" src="" alt="Firma" class="d-block mx-auto" style="max-height: 70px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESUMEN CRONOLÓGICO -->
            <div class="tab-pane fade" id="resumen" role="tabpanel">
                <!-- Resumen cronológico de actas anteriores -->
                <div id="resumen-anterior-container" class="mb-4">
                    <!-- Se llenará dinámicamente -->
                </div>

                <table class="table table-bordered" id="resumen-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Horas</th>
                            <th>Facturable</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input name="resumen[0][fecha]" type="date" class="form-control" required></td>
                            <td><input name="resumen[0][descripcion]" class="form-control" required></td>
                            <td><input name="resumen[0][horas]" type="number" step="0.25" class="form-control" required></td>
                            <td class="text-center"><input type="checkbox" name="resumen[0][facturable]" value="1" checked></td>
                            <td><button type="button" class="btn btn-sm btn-danger remove-row">X</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-primary" id="add-resumen">+ Agregar Registro</button>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Guardar Acta</button>
                    <a href="{{ route('actas.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal para crear Tipo de Acta -->
<div class="modal fade" id="modalCrearTipoActa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCrearTipoActa">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Tipo de Acta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_tipo" class="form-label">Nombre del Tipo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre_tipo" class="form-control" required>
                    </div>
                    <div id="errorCrearTipoActa" class="text-danger"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Tipo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear Empresa - VERSIÓN CORREGIDA -->
<div class="modal fade" id="modalCrearEmpresa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCrearEmpresa" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Información Básica -->
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-12 mb-3">
                            <label for="nombre_empresa" class="form-label">
                                <i class="fas fa-building"></i> Nombre de la Empresa <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre_empresa" 
                                   class="form-control" required 
                                   placeholder="Nombre completo de la empresa">
                        </div>
                    </div>

                    <!-- NIT y Dígito -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nit_empresa" class="form-label">
                                <i class="fas fa-id-card"></i> NIT <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nit" id="nit_empresa" 
                                   class="form-control" required 
                                   placeholder="Número de identificación tributaria">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="digito_verificacion" class="form-label">
                                <i class="fas fa-check-circle"></i> Dígito Verificación
                            </label>
                            <input type="text" name="digito_verificacion" id="digito_verificacion" 
                                   class="form-control" maxlength="1" placeholder="0">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="direccion_empresa" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Dirección
                            </label>
                            <input type="text" name="direccion" id="direccion_empresa" 
                                   class="form-control" 
                                   placeholder="Dirección completa de la empresa">
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono_empresa" class="form-label">
                                <i class="fas fa-phone"></i> Teléfono
                            </label>
                            <input type="text" name="telefono" id="telefono_empresa" 
                                   class="form-control" 
                                   placeholder="+57 300 123 4567">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email_empresa" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" name="email" id="email_empresa" 
                                   class="form-control" 
                                   placeholder="contacto@empresa.com">
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="logo_empresa" class="form-label">
                                <i class="fas fa-image"></i> Logo de la Empresa
                            </label>
                            <input type="file" name="logo_empresa" id="logo_empresa" 
                                   class="form-control" accept="image/*">
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> 
                                Formatos soportados: JPG, PNG, GIF (máx. 2MB)
                            </div>
                        </div>
                    </div>

                    <!-- Preview del logo -->
                    <div class="row" id="logo-preview-container" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Vista previa:</label>
                            <div class="text-center">
                                <img id="logo-preview" src="" alt="Vista previa" 
                                     style="max-height: 100px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Información:</strong> Esta empresa se utilizará para crear proyectos y asociar personas en las actas del sistema.
                            </div>
                        </div>
                    </div>

                    <div id="errorCrearEmpresa" class="text-danger"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <i class="fas fa-save"></i> Guardar Empresa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear Persona - VERSIÓN COMPLETA CORREGIDA -->
<div class="modal fade" id="modalCrearPersona" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCrearPersona" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus"></i> Crear Nueva Persona
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- INFORMACIÓN BÁSICA -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary">
                                <i class="fas fa-user"></i> Información Personal
                            </h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_persona" class="form-label">
                                <i class="fas fa-user"></i> Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre_persona" class="form-control" required
                                   placeholder="Nombre completo de la persona">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="iniciales_persona" class="form-label">
                                <i class="fas fa-id-badge"></i> Iniciales
                            </label>
                            <input type="text" name="iniciales" id="iniciales_persona" class="form-control"
                                   placeholder="Ej: JPS" maxlength="10">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cargo_persona" class="form-label">
                                <i class="fas fa-briefcase"></i> Cargo
                            </label>
                            <input type="text" name="cargo" id="cargo_persona" class="form-control"
                                   placeholder="Cargo o posición">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="empresa_id_persona" class="form-label">
                                <i class="fas fa-building"></i> Empresa <span class="text-danger">*</span>
                            </label>
                            <select name="empresa_id" id="empresa_id_persona" class="form-select" required>
                                <option value="">Seleccione una empresa</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- INFORMACIÓN DE IDENTIFICACIÓN -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary">
                                <i class="fas fa-id-card"></i> Información de Identificación
                            </h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cedula_persona" class="form-label">Cédula</label>
                            <input type="text" name="cedula" id="cedula_persona" class="form-control"
                                   placeholder="Número de cédula">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_expedicion_cedula_persona" class="form-label">Fecha Expedición</label>
                            <input type="date" name="fecha_expedicion_cedula" id="fecha_expedicion_cedula_persona" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lugar_expedicion_cedula_persona" class="form-label">Lugar Expedición</label>
                            <input type="text" name="lugar_expedicion_cedula" id="lugar_expedicion_cedula_persona" class="form-control"
                                   placeholder="Ciudad de expedición">
                        </div>
                    </div>

                    <!-- INFORMACIÓN PROFESIONAL -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary">
                                <i class="fas fa-graduation-cap"></i> Información Profesional
                            </h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tarjeta_profesional_persona" class="form-label">Tarjeta Profesional</label>
                            <input type="text" name="tarjeta_profesional" id="tarjeta_profesional_persona" class="form-control"
                                   placeholder="Número de tarjeta profesional">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_tarjeta_persona" class="form-label">Fecha de Expedición Tarjeta</label>
                            <input type="date" name="fecha_tarjeta" id="fecha_tarjeta_persona" class="form-control">
                        </div>
                    </div>

                    <!-- FIRMA DIGITAL -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary">
                                <i class="fas fa-signature"></i> Firma Digital
                            </h6>
                            <hr>
                        </div>
                    </div>

                    <!-- Método de Firma -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">¿Cómo desea agregar la firma?</label>
                            <select class="form-select" id="metodo-firma-persona" onchange="toggleFirmaMethodPersona()">
                                <option value="">Seleccione una opción</option>
                                <option value="archivo">Subir imagen de firma</option>
                                <option value="dibujar">Dibujar firma digital</option>
                            </select>
                        </div>
                    </div>

                    <!-- Subida de Archivo -->
                    <div class="row" id="firma-archivo-container-persona" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Imagen de la Firma</label>
                            <input type="file" name="firma" id="firma_persona" class="form-control" accept="image/*" onchange="previewFirmaPersona(event)">
                            <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.</div>
                            <img id="firmaPreviewPersona" class="img-thumbnail mt-2" style="max-height: 150px; display: none;">
                        </div>
                    </div>

                    <!-- Canvas para Dibujar -->
                    <div class="row" id="firma-canvas-container-persona" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <label class="form-label">Dibuje su firma aquí:</label>
                                    <canvas id="signature-canvas-persona" class="border" width="600" height="200"></canvas>
                                    <br>
                                    <div class="mt-2 d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignaturePersona()">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" disabled id="btnEstadoFirmaPersona">
                                            ✍️ Firma pendiente
                                        </button>
                                    </div>
                                    <input type="hidden" name="firma_base64" id="firma_base64_persona">
                                    <div class="form-text mt-2">
                                        📝 Al firmar con trazo digital, acepto que esta firma tiene validez como representación de mi aprobación.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="errorCrearPersona" class="text-danger small mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Persona
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const logoPlaceholder = "{{ asset('images/logo-placeholder.png') }}";
let signaturePad; // Para el modal principal (si existe)
let signaturePadPersona = null; // Para el modal de persona

// ✅ FUNCIONES GLOBALES PARA RESPONSABLES
function getAsistentesNombres() {
    return [...document.querySelectorAll('.persona-select')].map(select => {
        const selected = select.selectedOptions[0];
        const value = select.value?.trim();
        const label = selected?.textContent?.trim();
        if (!value || !label || label.toLowerCase().includes("seleccione")) {
            return null;
        }
        return { value, label };
    }).filter(Boolean);
}

function actualizarSelectsDeResponsables() {
    const asistentes = getAsistentesNombres();
    
    // ✅ ACTUALIZAR TODOS LOS SELECTS DE COMPROMISOS
    document.querySelectorAll('.compromiso-responsable').forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Seleccione *</option>';
        
        asistentes.forEach(({ value, label }) => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = label;
            if (value === currentValue) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    });
    
    console.log('✅ Selects de responsables actualizados:', asistentes.length, 'asistentes');
}

// 🚀 FUNCIÓN PARA ACTUALIZAR LOGO EMPRESA
function updateLogoEmpresa(empresaId) {
    const select = document.getElementById("empresa_id");
    const logoImg = document.getElementById("logo-empresa");
    if (!logoImg || !select) return;
    
    const selectedOption = select.options[select.selectedIndex];
    const logoUrl = selectedOption?.getAttribute("data-logo") || logoPlaceholder;
    logoImg.src = logoUrl;
}

// 🖋️ FUNCIONES PARA SIGNATURE PAD PRINCIPAL
window.toggleFirmaMethod = () => {
    const metodo = document.getElementById("metodo-firma").value;
    document.getElementById("firma-archivo-container").style.display = metodo === "archivo" ? "block" : "none";
    document.getElementById("firma-dibujar-container").style.display = metodo === "dibujar" ? "block" : "none";
};

window.clearSignature = () => {
    if (signaturePad) {
        signaturePad.clear();
        document.getElementById("firma_base64").value = "";
        const estadoBtn = document.getElementById("btnEstadoFirma");
        if (estadoBtn) {
            estadoBtn.disabled = true;
            estadoBtn.textContent = "✍️ Firma pendiente";
        }
    }
};

// 🖋️ FUNCIONES ESPECÍFICAS PARA MODAL DE PERSONA
window.toggleFirmaMethodPersona = () => {
    const metodo = document.getElementById("metodo-firma-persona").value;
    const archivoContainer = document.getElementById("firma-archivo-container-persona");
    const canvasContainer = document.getElementById("firma-canvas-container-persona");
    
    // Ocultar ambos contenedores
    archivoContainer.style.display = 'none';
    canvasContainer.style.display = 'none';
    
    if (metodo === "archivo") {
        archivoContainer.style.display = 'block';
    } else if (metodo === "dibujar") {
        canvasContainer.style.display = 'block';
        initSignaturePadPersona();
    }
};

function initSignaturePadPersona() {
    if (signaturePadPersona) return;
    
    const canvas = document.getElementById('signature-canvas-persona');
    if (canvas) {
        signaturePadPersona = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        signaturePadPersona.addEventListener('endStroke', () => {
            document.getElementById('firma_base64_persona').value = signaturePadPersona.toDataURL();
            const btn = document.getElementById("btnEstadoFirmaPersona");
            if (btn) {
                btn.disabled = false;
                btn.textContent = "✅ Firma registrada";
            }
        });
    }
}

window.clearSignaturePersona = () => {
    if (signaturePadPersona) {
        signaturePadPersona.clear();
        document.getElementById("firma_base64_persona").value = "";
        const estadoBtn = document.getElementById("btnEstadoFirmaPersona");
        if (estadoBtn) {
            estadoBtn.disabled = true;
            estadoBtn.textContent = "✍️ Firma pendiente";
        }
    }
};

function previewFirmaPersona(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('firmaPreviewPersona');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

// 🎯 FUNCIONES PARA MODALES - VERSIÓN COMPLETA
function configurarModales() {
    // Modal Tipo de Acta
    const formCrearTipoActa = document.getElementById('formCrearTipoActa');
    if (formCrearTipoActa) {
        formCrearTipoActa.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const errorDiv = document.getElementById('errorCrearTipoActa');
            
            try {
                const response = await fetch('{{ route("tipos-acta.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) throw await response.json();
                const data = await response.json();

                // Actualizar select de tipos
                const tipoSelect = document.getElementById('tipo_id');
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.nombre;
                option.selected = true;
                tipoSelect.appendChild(option);

                // Limpiar y cerrar modal
                this.reset();
                errorDiv.innerText = '';
                
                const modalElement = document.getElementById('modalCrearTipoActa');
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.hide();

                // Mostrar éxito después de cerrar
                setTimeout(() => {
                    Swal.fire('¡Éxito!', 'Tipo de acta creado exitosamente', 'success');
                }, 300);

            } catch (err) {
                let errorText = 'Error al crear tipo de acta.';
                if (err.errors) {
                    errorText = Object.values(err.errors).flat().join('; ');
                }
                errorDiv.innerText = errorText;
            }
        });
    }

    // Modal Empresa - VERSIÓN CORREGIDA
    const formCrearEmpresa = document.getElementById('formCrearEmpresa');
    if (formCrearEmpresa) {
        // Preview del logo
        const logoInput = document.getElementById('logo_empresa');
        const logoPreview = document.getElementById('logo-preview');
        const logoPreviewContainer = document.getElementById('logo-preview-container');

        logoInput?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                logoPreviewContainer.style.display = 'none';
            }
        });

        formCrearEmpresa.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner-border');
            const errorDiv = document.getElementById('errorCrearEmpresa');
            const modalElement = document.getElementById('modalCrearEmpresa');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            
            // Mostrar loading
            submitBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            errorDiv.innerText = '';
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("empresas.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw errorData;
                }

                const data = await response.json();

                // Actualizar todos los selects PRIMERO
                const empresaSelect = document.getElementById('empresa_id');
                if (empresaSelect) {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.text = `${data.nombre} – NIT: ${data.nit || 'N/A'}`;
                    option.setAttribute('data-logo', data.logo_url || logoPlaceholder);
                    option.selected = true;
                    empresaSelect.appendChild(option);
                    updateLogoEmpresa(data.id);
                }

                const empresaPersonaSelect = document.getElementById('empresa_id_persona');
                if (empresaPersonaSelect) {
                    const optionPersona = document.createElement('option');
                    optionPersona.value = data.id;
                    optionPersona.text = data.nombre;
                    empresaPersonaSelect.appendChild(optionPersona);
                }

                document.querySelectorAll('.empresa-select').forEach(select => {
                    const optionAsistente = document.createElement('option');
                    optionAsistente.value = data.id;
                    optionAsistente.text = data.nombre;
                    select.appendChild(optionAsistente);
                });

                // Limpiar formulario
                this.reset();
                if (logoPreviewContainer) logoPreviewContainer.style.display = 'none';
                errorDiv.innerText = '';

                // Cerrar modal ANTES del SweetAlert
                modal.hide();

                // ✅ FORZAR LIMPIEZA DEL BACKDROP
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });
                    
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                    document.body.style.overflow = '';

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: `Empresa "${data.nombre}" creada exitosamente`,
                        timer: 3000,
                        showConfirmButton: true,
                        allowOutsideClick: true,
                        allowEscapeKey: true
                    });
                }, 300);

            } catch (err) {
                let errorText = 'Error al crear empresa.';
                
                if (err.errors) {
                    errorText = Object.values(err.errors).flat().join('; ');
                } else if (err.message) {
                    errorText = err.message;
                }
                
                errorDiv.innerText = errorText;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error al crear empresa',
                    text: errorText,
                    confirmButtonText: 'Intentar de nuevo'
                });
                
            } finally {
                submitBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }

    // Modal Persona - VERSIÓN CORREGIDA CON FIRMAS
    const formCrearPersona = document.getElementById('formCrearPersona');
    if (formCrearPersona) {
        formCrearPersona.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const errorDiv = document.getElementById("errorCrearPersona");
            const modalElement = document.getElementById('modalCrearPersona');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

            // Mostrar loading
            submitBtn.disabled = true;
            errorDiv.innerText = '';

            // Preparar datos de firma para el modal de persona
            const metodo = document.getElementById("metodo-firma-persona")?.value;
            if (metodo === "dibujar" && signaturePadPersona && !signaturePadPersona.isEmpty()) {
                document.getElementById("firma_base64_persona").value = signaturePadPersona.toDataURL("image/png");
            }

            const formData = new FormData(this);

            try {
                const response = await fetch("{{ route('personas.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                });

                if (!response.ok) throw await response.json();
                const data = await response.json();

                console.log('✅ Persona creada:', data);

                // Actualizar selects de personas en asistentes
                document.querySelectorAll('.persona-select').forEach(select => {
                    const option = new Option(data.nombre, data.id, false, false);
                    option.setAttribute('data-iniciales', data.iniciales || '');
                    option.setAttribute('data-cargo', data.cargo || '');
                    option.setAttribute('data-empresa', data.empresa_id || '');
                    select.appendChild(option);
                });

                // Actualizar selects de firmantes
                ['select_firmante_gp', 'select_firmante_empresa'].forEach(selectId => {
                    const select = document.getElementById(selectId);
                    if (select) {
                        const option = new Option(data.nombre, data.id, false, false);
                        option.setAttribute('data-firma', data.firma_url || '');
                        option.setAttribute('data-empresa', data.empresa_nombre || '');
                        option.setAttribute('data-tarjeta', data.tarjeta_profesional || '');
                        option.setAttribute('data-fecha-tarjeta', data.fecha_tarjeta || '');
                        option.setAttribute('data-cedula', data.cedula || '');
                        option.setAttribute('data-fecha-cedula', data.fecha_expedicion_cedula || '');
                        option.setAttribute('data-lugar-cedula', data.lugar_expedicion_cedula || '');
                        select.appendChild(option);
                    }
                });

                // Limpiar formulario
                this.reset();
                errorDiv.innerText = '';

                // Limpiar signature pad específico del modal de persona
                if (signaturePadPersona) {
                    signaturePadPersona.clear();
                    const estadoBtn = document.getElementById("btnEstadoFirmaPersona");
                    if (estadoBtn) {
                        estadoBtn.disabled = true;
                        estadoBtn.textContent = "✍️ Firma pendiente";
                    }
                }

                // Ocultar contenedores de firma
                document.getElementById("firma-archivo-container-persona").style.display = 'none';
                document.getElementById("firma-canvas-container-persona").style.display = 'none';
                document.getElementById("metodo-firma-persona").value = '';
                
                // Ocultar preview de firma
                const preview = document.getElementById('firmaPreviewPersona');
                if (preview) preview.style.display = 'none';

                // Actualizar selects de responsables
                setTimeout(actualizarSelectsDeResponsables, 100);

                // Cerrar modal
                modal.hide();

                // ✅ FORZAR LIMPIEZA DEL BACKDROP
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });
                    
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                    document.body.style.overflow = '';

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: `Persona "${data.nombre}" creada exitosamente`,
                        timer: 3000,
                        showConfirmButton: true,
                        allowOutsideClick: true,
                        allowEscapeKey: true
                    });
                }, 300);

            } catch (err) {
                let errorText = 'Error al crear persona.';
                if (err.errors) {
                    errorText = Object.values(err.errors).flat().join('; ');
                } else if (err.message) {
                    errorText = err.message;
                }
                errorDiv.innerText = errorText;

                Swal.fire({
                    icon: 'error',
                    title: 'Error al crear persona',
                    text: errorText,
                    confirmButtonText: 'Intentar de nuevo'
                });

            } finally {
                submitBtn.disabled = false;
            }
        });
    }
}

// 🎯 INICIALIZACIÓN PRINCIPAL
document.addEventListener("DOMContentLoaded", () => {
    const editorFields = ['objetivo', 'agenda', 'desarrollo', 'conclusiones'];
    const selectEmpresa = document.getElementById("empresa_id");
    if (selectEmpresa) updateLogoEmpresa(selectEmpresa.value);

    // 📝 Inicializar CKEditor
    function initEditor(id) {
        const el = document.getElementById(id);
        if (el && !el.classList.contains('ck-editor__editable') && !el.closest('.ck-editor')) {
            ClassicEditor.create(el, {
                ckfinder: {
                    uploadUrl: "{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}"
                }
            }).catch(console.error);
        }
    }

    editorFields.forEach(initEditor);
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', e => {
            const target = e.target.getAttribute('data-bs-target')?.replace('#', '');
            if (editorFields.includes(target)) setTimeout(() => initEditor(target), 100);
        });
    });

    // 📅 Toggle fecha próxima reunión
    const toggleFecha = () => {
        const select = document.getElementById('define_proxima_reunion');
        const container = document.getElementById('fecha_proxima_reunion_container');
        if (select && container) {
            container.style.display = select.value === 'si' ? 'block' : 'none';
        }
    };
    document.getElementById('define_proxima_reunion')?.addEventListener('change', toggleFecha);
    toggleFecha();

    // 🔒 Validación de pestañas
    const tabButtons = document.querySelectorAll('[data-required-tab]');
    tabButtons.forEach(btn => {
        btn.addEventListener('show.bs.tab', function (e) {
            const currentTab = document.querySelector('.tab-pane.active');
            const requiredInputs = currentTab.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;

            requiredInputs.forEach(input => {
                if (!input.value || input.value.trim() === '') {
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos requeridos antes de continuar.',
                    confirmButtonText: 'Entendido'
                });
            }
        });
    });

    // 👥 ASISTENTES - VERSIÓN MEJORADA
    document.getElementById("add-asistente")?.addEventListener("click", () => {
        const tableBody = document.getElementById("asistentes-body");
        if (!tableBody || tableBody.children.length === 0) {
            console.error('No se puede agregar asistente: tabla no encontrada o vacía');
            return;
        }
        
        const index = tableBody.rows.length;
        const templateRow = tableBody.rows[0];
        const row = templateRow.cloneNode(true);

        // Actualizar nombres de campos
        [...row.querySelectorAll("input, select")].forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            }
            if (el.type === "checkbox") el.checked = true;
            else if (el.tagName === "SELECT") el.selectedIndex = 0;
            else el.value = "";
        });

        tableBody.appendChild(row);
        
        // Configurar events para nueva fila
        const personaSelect = row.querySelector(".persona-select");
        if (personaSelect) {
            personaSelect.addEventListener("change", (e) => {
                autoCompletarAsistente(e);
                setTimeout(actualizarSelectsDeResponsables, 10);
            });
        }
        
        setTimeout(actualizarSelectsDeResponsables, 10);
        console.log('✅ Asistente agregado, fila:', index + 1);
    });

    // 📋 COMPROMISOS - VERSIÓN MEJORADA
    document.getElementById("add-compromiso")?.addEventListener("click", () => {
        const tableBody = document.querySelector("#compromisos-table tbody");
        if (!tableBody || tableBody.children.length === 0) {
            console.error('No se puede agregar compromiso: tabla no encontrada o vacía');
            return;
        }
        
        const index = tableBody.rows.length;
        const templateRow = tableBody.rows[0];
        const row = templateRow.cloneNode(true);

        [...row.querySelectorAll("input, select")].forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            }
            if (el.type === "date") el.value = "";
            else if (el.tagName === "SELECT") el.selectedIndex = 0;
            else el.value = "";
        });

        row.querySelector("td:first-child").textContent = index + 1;
        tableBody.appendChild(row);
        
        setTimeout(actualizarSelectsDeResponsables, 10);
    });

    // ⏰ RESUMEN
    document.getElementById("add-resumen")?.addEventListener("click", () => {
        const tableBody = document.querySelector("#resumen-table tbody");
        if (!tableBody || tableBody.children.length === 0) {
            console.error('No se puede agregar resumen: tabla no encontrada o vacía');
            return;
        }
        
        const index = tableBody.rows.length;
        const templateRow = tableBody.rows[0];
        const row = templateRow.cloneNode(true);

        [...row.querySelectorAll("input")].forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            }
            if (el.type === "checkbox") el.checked = true;
            else el.value = "";
        });

        tableBody.appendChild(row);
    });

    // 🗑️ ELIMINAR FILAS
    document.body.addEventListener("click", e => {
        if (e.target.classList.contains("remove-row")) {
            const row = e.target.closest("tr");
            if (row.parentNode.rows.length > 1) {
                row.remove();
                if (e.target.closest("#compromisos-table")) {
                    actualizarSelectsDeResponsables();
                }
            }
        }
    });

    // 🏢 CONFIGURAR MODALES
    configurarModales();

    // 🖋️ CONFIGURAR SIGNATURE PAD PRINCIPAL (si existe)
    const canvas = document.getElementById("signature-canvas");
    if (canvas) {
        signaturePad = new SignaturePad(canvas);
        signaturePad.onEnd = () => {
            const btn = document.getElementById("btnEstadoFirma");
            if (btn) {
                btn.disabled = false;
                btn.textContent = "✅ Firma registrada";
            }
        };
    }

    // 👤 Auto-completar campos de asistentes
    function autoCompletarAsistente(event) {
        const select = event.target;
        const option = select.selectedOptions[0];
        const row = select.closest('tr');
        
        if (row && option) {
            const iniciales = row.querySelector('input[name$="[iniciales]"]');
            const cargo = row.querySelector('input[name$="[cargo]"]');
            const empresa = row.querySelector('select[name$="[empresa_id]"]');
            
            if (iniciales) iniciales.value = option.dataset.iniciales || '';
            if (cargo) cargo.value = option.dataset.cargo || '';
            if (empresa) empresa.value = option.dataset.empresa || '';
            
            console.log('✅ Auto-completado:', option.textContent.trim());
        }
    }

    document.body.addEventListener('change', e => {
        if (e.target.classList.contains('persona-select')) {
            autoCompletarAsistente(e);
            setTimeout(actualizarSelectsDeResponsables, 50);
        }
    });

    // 📊 CARGAR COMPROMISOS ANTERIORES POR PROYECTO
    const selectProyecto = document.getElementById('proyecto_id');
    const compromisosContainer = document.getElementById('compromisos-anteriores-container');
    const resumenAnteriorContainer = document.getElementById('resumen-anterior-container');

    selectProyecto?.addEventListener('change', async function () {
        const proyectoId = this.value;
        if (compromisosContainer) compromisosContainer.innerHTML = '';
        if (resumenAnteriorContainer) resumenAnteriorContainer.innerHTML = '';

        if (!proyectoId) return;

        try {
            // 🔁 Compromisos anteriores
            const compromisosRes = await fetch(`/actas/${proyectoId}/ultima`);
            if (compromisosRes.ok) {
                const compromisosData = await compromisosRes.json();
                if (compromisosData?.compromisos?.length && compromisosContainer) {
                    let html = `<div class="alert alert-info"><strong>Compromisos del acta anterior (Acta N° ${compromisosData.acta.numero})</strong></div>`;
                    html += `<table class="table table-bordered mb-4"><thead><tr><th>Descripción</th><th>Responsable</th><th>Fecha</th><th>Estado</th></tr></thead><tbody>`;
                    compromisosData.compromisos.forEach((comp) => {
                        html += `<tr>
                            <td><input type="text" class="form-control" value="${comp.descripcion}" readonly></td>
                            <td><input type="text" class="form-control" value="${comp.responsable || 'N/A'}" readonly></td>
                            <td><input type="date" class="form-control" value="${comp.fecha || ''}" readonly></td>
                            <td><input type="text" class="form-control" value="${comp.estado}" readonly></td>
                        </tr>`;
                    });
                    html += '</tbody></table>';
                    compromisosContainer.innerHTML = html;
                }
            }

            // 🔁 Resumen cronológico anterior
            const resumenRes = await fetch(`/proyectos/${proyectoId}/resumen-cronologico`);
            if (resumenRes.ok) {
                const resumenData = await resumenRes.json();
                if (resumenData?.length && resumenAnteriorContainer) {
                    let html = `<div class="alert alert-secondary"><strong>Resumen cronológico de actas anteriores</strong></div>`;
                    html += `<table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr><th>Fecha</th><th>Descripción</th><th>Horas</th><th>Facturable</th></tr>
                                </thead><tbody>`;
                    resumenData.forEach(r => {
                        html += `<tr>
                                    <td>${r.fecha}</td>
                                    <td>${r.descripcion}</td>
                                    <td>${r.horas}</td>
                                    <td>${r.facturable ? 'Sí' : 'No'}</td>
                                </tr>`;
                    });
                    html += `</tbody></table>`;
                    resumenAnteriorContainer.innerHTML = html;
                }
            }
        } catch (error) {
            console.error('Error cargando datos anteriores:', error);
        }
    });

    // 🖋️ FIRMAS DE REPRESENTACIÓN
    const updateFirmante = (selectId, fields) => {
        const select = document.getElementById(selectId);
        select?.addEventListener("change", function () {
            const o = this.selectedOptions[0];
            for (const [id, attr] of Object.entries(fields)) {
                const element = document.getElementById(id);
                if (element) {
                    if (id.endsWith("_img")) {
                        element.src = o.getAttribute(attr) || '';
                        element.style.display = o.getAttribute(attr) ? 'block' : 'none';
                    } else {
                        element.value = o.getAttribute(attr) || '';
                    }
                }
            }
        });
    };

    updateFirmante("select_firmante_gp", {
        "tarjeta_gp": "data-tarjeta",
        "fecha_tarjeta_gp": "data-fecha-tarjeta",
        "empresa_gp": "data-empresa",
        "firma_gp_img": "data-firma"
    });

    updateFirmante("select_firmante_empresa", {
        "cedula_empresa": "data-cedula",
        "lugar_cedula_empresa": "data-lugar-cedula",
        "empresa_empresa": "data-empresa",
        "firma_empresa_img": "data-firma"
    });

    // ✅ INICIALIZAR SELECTS DE RESPONSABLES AL FINAL
    setTimeout(() => {
        actualizarSelectsDeResponsables();
        console.log('✅ Sistema inicializado correctamente');
    }, 500);
});
</script>

</body>
</html>
@endsection