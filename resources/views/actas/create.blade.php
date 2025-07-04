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

<!-- Modal para crear Persona -->
<div class="modal fade" id="modalCrearPersona" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCrearPersona" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Campos básicos -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_persona" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre_persona" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="iniciales_persona" class="form-label">Iniciales</label>
                            <input type="text" name="iniciales" id="iniciales_persona" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cargo_persona" class="form-label">Cargo</label>
                            <input type="text" name="cargo" id="cargo_persona" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="empresa_id_persona" class="form-label">Empresa <span class="text-danger">*</span></label>
                            <select name="empresa_id" id="empresa_id_persona" class="form-select" required>
                                <option value="">Seleccione una empresa</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- SELECCIÓN DEL MÉTODO DE FIRMA -->
                    <div class="mb-3">
                        <label class="form-label">¿Cómo desea agregar la firma?</label>
                        <select class="form-select" id="metodo-firma" onchange="toggleFirmaMethod()">
                            <option value="">Seleccione una opción</option>
                            <option value="archivo">Subir imagen</option>
                            <option value="dibujar">Dibujar firma</option>
                        </select>
                    </div>

                    <!-- FIRMA: SUBIR ARCHIVO -->
                    <div class="mb-3" id="firma-archivo-container" style="display: none;">
                        <label class="form-label">Firma (subir imagen)</label>
                        <input type="file" name="firma" id="firma" class="form-control" accept="image/*">
                    </div>

                    <!-- FIRMA: DIBUJAR CON SIGNATURE PAD -->
                    <div class="mb-3" id="firma-dibujar-container" style="display: none;">
                        <label class="form-label">Firma (dibujar)</label>
                        <canvas id="signature-canvas" width="400" height="150" style="border: 1px solid #ccc; width: 100%;"></canvas>

                        <div class="mt-2 d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">🧽 Limpiar</button>
                            <button type="button" class="btn btn-sm btn-success" disabled id="btnEstadoFirma">✍️ Firma pendiente</button>
                        </div>

                        <input type="hidden" name="firma_base64" id="firma_base64">

                        <div class="form-text mt-2">
                            📝 Al firmar con trazo digital, acepto que esta firma tiene validez como representación de mi aprobación del contenido del acta.
                        </div>
                    </div>

                    <!-- Campos adicionales -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tarjeta_profesional" class="form-label">Tarjeta Profesional</label>
                            <input type="text" name="tarjeta_profesional" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_tarjeta" class="form-label">Fecha de expedición de la tarjeta</label>
                            <input type="date" name="fecha_tarjeta" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" name="cedula" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_expedicion_cedula" class="form-label">Fecha expedición cédula</label>
                            <input type="date" name="fecha_expedicion_cedula" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lugar_expedicion_cedula" class="form-label">Lugar expedición cédula</label>
                            <input type="text" name="lugar_expedicion_cedula" class="form-control">
                        </div>
                    </div>

                    <div id="errorCrearPersona" class="text-danger small mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Persona</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const logoPlaceholder = "{{ asset('images/logo-placeholder.png') }}";
let signaturePad;

// ✅ FUNCIONES GLOBALES PARA RESPONSABLES (MOVIDAS FUERA DEL DOMContentLoaded)
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

// 🖋️ FUNCIONES PARA SIGNATURE PAD
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
        estadoBtn.disabled = true;
        estadoBtn.textContent = "✍️ Firma pendiente";
    }
};

// 🎯 FUNCIONES PARA MODALES - VERSIÓN MEJORADA
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
                    // Remover cualquier backdrop persistente
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });
                    
                    // Restaurar body
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                    document.body.style.overflow = '';

                    // Mostrar éxito
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
                // Ocultar loading
                submitBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }

    // Modal Persona - VERSIÓN CORREGIDA CON LIMPIEZA DE BACKDROP
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

            const metodo = document.getElementById("metodo-firma")?.value;
            if (metodo === "dibujar" && signaturePad && !signaturePad.isEmpty()) {
                document.getElementById("firma_base64").value = signaturePad.toDataURL("image/png");
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

                // Actualizar selects
                document.querySelectorAll('.persona-select').forEach(select => {
                    const option = new Option(data.nombre, data.id, false, true);
                    option.setAttribute('data-iniciales', data.iniciales || '');
                    option.setAttribute('data-cargo', data.cargo || '');
                    option.setAttribute('data-empresa', data.empresa_id || '');
                    select.appendChild(option);
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                });

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

                // Limpiar signature pad si existe
                if (signaturePad) {
                    signaturePad.clear();
                    const estadoBtn = document.getElementById("btnEstadoFirma");
                    if (estadoBtn) {
                        estadoBtn.disabled = true;
                        estadoBtn.textContent = "✍️ Firma pendiente";
                    }
                }

                // ✅ AHORA SÍ PUEDE LLAMAR LA FUNCIÓN GLOBAL
                actualizarSelectsDeResponsables();

                // ✅ CERRAR MODAL ANTES DEL SWEETALERT
                modal.hide();

                // ✅ FORZAR LIMPIEZA DEL BACKDROP
                setTimeout(() => {
                    // Remover cualquier backdrop persistente
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });
                    
                    // Restaurar body
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                    document.body.style.overflow = '';

                    // Mostrar éxito
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
                // Ocultar loading
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

    // 👥 ASISTENTES
    document.getElementById("add-asistente")?.addEventListener("click", () => {
        const tableBody = document.getElementById("asistentes-body");
        const index = tableBody.rows.length;
        const row = tableBody.rows[0].cloneNode(true);

        [...row.querySelectorAll("input, select")].forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            if (el.type === "checkbox") el.checked = true;
            else if (el.tagName === "SELECT") el.selectedIndex = 0;
            else el.value = "";
        });

        tableBody.appendChild(row);
        const personaSelect = row.querySelector(".persona-select");
        if (personaSelect) {
            personaSelect.addEventListener("change", actualizarSelectsDeResponsables);
            personaSelect.dispatchEvent(new Event("change", { bubbles: true }));
        }
        setTimeout(actualizarSelectsDeResponsables, 10);
    });

    // 📋 COMPROMISOS - CORREGIR FUNCIÓN
    document.getElementById("add-compromiso")?.addEventListener("click", () => {
        const tableBody = document.querySelector("#compromisos-table tbody");
        const index = tableBody.rows.length;
        const row = tableBody.rows[0].cloneNode(true);

        [...row.querySelectorAll("input, select")].forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            if (el.type === "date") el.value = "";
            else if (el.tagName === "SELECT") el.selectedIndex = 0;
            else el.value = "";
        });

        row.querySelector("td:first-child").textContent = index + 1;
        tableBody.appendChild(row);
        
        // ✅ ACTUALIZAR SELECTS DE RESPONSABLES DESPUÉS DE AGREGAR
        setTimeout(actualizarSelectsDeResponsables, 10);
    });

    // ⏰ RESUMEN
    document.getElementById("add-resumen")?.addEventListener("click", () => {
        const tableBody = document.querySelector("#resumen-table tbody");
        const index = tableBody.rows.length;
        const row = tableBody.rows[0].cloneNode(true);

        [...row.querySelectorAll("input")].forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            if (el.type === "checkbox") el.checked = true;
            else el.value = "";
        });

        tableBody.appendChild(row);
    });

    // 🗑️ ELIMINAR FILAS
    document.body.addEventListener("click", e => {
        if (e.target.classList.contains("remove-row")) {
            const row = e.target.closest("tr");
            if (row.parentNode.rows.length > 1) row.remove();
            if (e.target.closest("#compromisos-table")) actualizarSelectsDeResponsables();
        }
    });

    // 🏢 CONFIGURAR MODALES
    configurarModales();

    // 🖋️ CONFIGURAR SIGNATURE PAD
    const canvas = document.getElementById("signature-canvas");
    if (canvas) {
        signaturePad = new SignaturePad(canvas);
        signaturePad.onEnd = () => {
            const btn = document.getElementById("btnEstadoFirma");
            btn.disabled = false;
            btn.textContent = "✅ Firma registrada";
        };
    }

    // 👤 Auto-completar campos de asistentes
    document.body.addEventListener('change', e => {
        if (e.target.classList.contains('persona-select')) {
            const option = e.target.selectedOptions[0];
            const row = e.target.closest('tr');
            if (row) {
                row.querySelector('input[name$="[iniciales]"]').value = option.dataset.iniciales || '';
                row.querySelector('input[name$="[cargo]"]').value = option.dataset.cargo || '';
                row.querySelector('select[name$="[empresa_id]"]').value = option.dataset.empresa || '';
            }
            actualizarSelectsDeResponsables(); // ✅ Ahora funciona correctamente
        }
    });

    // 📊 CARGAR COMPROMISOS ANTERIORES POR PROYECTO
    const selectProyecto = document.getElementById('proyecto_id');
    const compromisosContainer = document.getElementById('compromisos-anteriores-container');
    const resumenAnteriorContainer = document.getElementById('resumen-anterior-container');

    selectProyecto?.addEventListener('change', async function () {
        const proyectoId = this.value;
        compromisosContainer.innerHTML = '';
        resumenAnteriorContainer.innerHTML = '';

        if (!proyectoId) return;

        try {
            // 🔁 Compromisos anteriores
            const compromisosRes = await fetch(`/actas/${proyectoId}/ultima`);
            if (compromisosRes.ok) {
                const compromisosData = await compromisosRes.json();
                if (compromisosData?.compromisos?.length) {
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
                if (resumenData?.length) {
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
    setTimeout(actualizarSelectsDeResponsables, 100);
});
</script>
<!-- filepath: resources/views/actas/create.blade.php -->
<script>
// 💾 AUTO-GUARDADO LOCAL
class AutoGuardado {
    constructor() {
        this.storageKey = 'acta_draft_' + Date.now();
        this.loadExistingDraft();
        this.initAutoSave();
        this.showDraftNotification();
    }

    // 🔍 BUSCAR BORRADORES EXISTENTES
    loadExistingDraft() {
        const drafts = this.getAllDrafts();
        if (drafts.length > 0) {
            // Usar el borrador más reciente
            const latestDraft = drafts[0];
            this.storageKey = latestDraft.key;
            this.loadDraft(latestDraft.data);
        }
    }

    // 📝 OBTENER TODOS LOS BORRADORES
    getAllDrafts() {
        const drafts = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key.startsWith('acta_draft_')) {
                try {
                    const data = JSON.parse(localStorage.getItem(key));
                    drafts.push({
                        key: key,
                        data: data,
                        timestamp: data.timestamp || 0
                    });
                } catch (e) {
                    // Borrar datos corruptos
                    localStorage.removeItem(key);
                }
            }
        }
        
        // Ordenar por timestamp (más reciente primero)
        return drafts.sort((a, b) => b.timestamp - a.timestamp);
    }

    // 💾 GUARDAR BORRADOR
    saveDraft() {
        const formData = this.getFormData();
        formData.timestamp = Date.now();
        formData.url = window.location.href;
        
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(formData));
            this.updateSaveIndicator('✅ Guardado automático');
        } catch (e) {
            console.warn('Error guardando borrador:', e);
            this.updateSaveIndicator('⚠️ Error al guardar');
        }
    }

    // 📋 RECOPILAR DATOS DEL FORMULARIO
    getFormData() {
        const form = document.getElementById('actaForm');
        const data = {
            // Datos básicos
            tipo_id: form.tipo_id?.value || '',
            fecha: form.fecha?.value || '',
            hora_inicio: form.hora_inicio?.value || '',
            hora_fin: form.hora_fin?.value || '',
            lugar: form.lugar?.value || '',
            ciudad_id: form.ciudad_id?.value || '',
            empresa_id: form.empresa_id?.value || '',
            facturable: form.facturable?.value || '',
            proyecto_id: form.proyecto_id?.value || '',
            
            // Objetivo y agenda
            objetivo: this.getEditorContent('objetivo'),
            agenda: this.getEditorContent('agenda'),
            desarrollo: this.getEditorContent('desarrollo'),
            conclusiones: this.getEditorContent('conclusiones'),
            
            // Próxima reunión
            define_proxima_reunion: form.define_proxima_reunion?.value || '',
            proxima_reunion: form.proxima_reunion?.value || '',
            
            // Asistentes
            asistentes: this.getTableData('asistentes-body', ['iniciales', 'persona_id', 'cargo', 'empresa_id', 'asistio']),
            
            // Compromisos
            compromisos: this.getTableData('compromisos-table tbody', ['descripcion', 'persona_id', 'fecha', 'estado']),
            
            // Resumen
            resumen: this.getTableData('resumen-table tbody', ['fecha', 'descripcion', 'horas', 'facturable']),
            
            // Firmas
            firmante_gp_id: form.firmante_gp_id?.value || '',
            firmante_empresa_id: form.firmante_empresa_id?.value || ''
        };

        return data;
    }

    // 📝 OBTENER CONTENIDO DE EDITORES CKEDITOR
    getEditorContent(editorId) {
        const element = document.getElementById(editorId);
        if (!element) return '';
        
        // Si es CKEditor
        if (window.CKEDITOR && CKEDITOR.instances[editorId]) {
            return CKEDITOR.instances[editorId].getData();
        }
        
        // Si es ClassicEditor (CKEditor 5)
        if (element.classList.contains('ck-editor__editable')) {
            return element.innerHTML;
        }
        
        // Fallback a valor normal
        return element.value || '';
    }

    // 📊 OBTENER DATOS DE TABLAS
    getTableData(selector, fields) {
        const tbody = document.querySelector(selector);
        if (!tbody) return [];
        
        const data = [];
        tbody.querySelectorAll('tr').forEach(row => {
            const rowData = {};
            fields.forEach(field => {
                const input = row.querySelector(`[name*="[${field}]"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        rowData[field] = input.checked;
                    } else {
                        rowData[field] = input.value || '';
                    }
                }
            });
            data.push(rowData);
        });
        
        return data;
    }

    // 🔄 CARGAR BORRADOR
    loadDraft(data) {
        if (!data) return;
        
        try {
            // Datos básicos
            this.setFieldValue('tipo_id', data.tipo_id);
            this.setFieldValue('fecha', data.fecha);
            this.setFieldValue('hora_inicio', data.hora_inicio);
            this.setFieldValue('hora_fin', data.hora_fin);
            this.setFieldValue('lugar', data.lugar);
            this.setFieldValue('ciudad_id', data.ciudad_id);
            this.setFieldValue('empresa_id', data.empresa_id);
            this.setFieldValue('facturable', data.facturable);
            this.setFieldValue('proyecto_id', data.proyecto_id);
            
            // Próxima reunión
            this.setFieldValue('define_proxima_reunion', data.define_proxima_reunion);
            this.setFieldValue('proxima_reunion', data.proxima_reunion);
            
            // Firmas
            this.setFieldValue('firmante_gp_id', data.firmante_gp_id);
            this.setFieldValue('firmante_empresa_id', data.firmante_empresa_id);
            
            // Cargar tablas después de un delay para asegurar que el DOM esté listo
            setTimeout(() => {
                this.loadTableData('asistentes', data.asistentes);
                this.loadTableData('compromisos', data.compromisos);
                this.loadTableData('resumen', data.resumen);
                
                // Cargar editores después de otro delay
                setTimeout(() => {
                    this.setEditorContent('objetivo', data.objetivo);
                    this.setEditorContent('agenda', data.agenda);
                    this.setEditorContent('desarrollo', data.desarrollo);
                    this.setEditorContent('conclusiones', data.conclusiones);
                }, 500);
            }, 300);
            
        } catch (e) {
            console.error('Error cargando borrador:', e);
        }
    }

    // 🔧 ESTABLECER VALOR DE CAMPO
    setFieldValue(name, value) {
        const field = document.querySelector(`[name="${name}"]`);
        if (field && value) {
            field.value = value;
            field.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    // 📝 ESTABLECER CONTENIDO DE EDITOR
    setEditorContent(editorId, content) {
        if (!content) return;
        
        const element = document.getElementById(editorId);
        if (!element) return;
        
        // Si es CKEditor 5 ClassicEditor
        if (window.ClassicEditor) {
            // Buscar la instancia del editor
            const editorInstance = Array.from(document.querySelectorAll('.ck-editor')).find(el => 
                el.querySelector(`#${editorId}`)
            );
            
            if (editorInstance && editorInstance.ckeditorInstance) {
                editorInstance.ckeditorInstance.setData(content);
                return;
            }
        }
        
        // Fallback a textarea normal
        element.value = content;
    }

    // 📊 CARGAR DATOS DE TABLA
    loadTableData(tableType, data) {
        if (!data || !Array.isArray(data)) return;
        
        let tbody, addButton, fields;
        
        switch (tableType) {
            case 'asistentes':
                tbody = document.getElementById('asistentes-body');
                addButton = document.getElementById('add-asistente');
                fields = ['iniciales', 'persona_id', 'cargo', 'empresa_id', 'asistio'];
                break;
            case 'compromisos':
                tbody = document.querySelector('#compromisos-table tbody');
                addButton = document.getElementById('add-compromiso');
                fields = ['descripcion', 'persona_id', 'fecha', 'estado'];
                break;
            case 'resumen':
                tbody = document.querySelector('#resumen-table tbody');
                addButton = document.getElementById('add-resumen');
                fields = ['fecha', 'descripcion', 'horas', 'facturable'];
                break;
        }
        
        if (!tbody || !addButton) return;
        
        // Limpiar tabla existente
        tbody.innerHTML = '';
        
        // Agregar filas con datos
        data.forEach((rowData, index) => {
            if (index === 0 && tbody.children.length === 0) {
                // Usar la primera fila como base
                addButton.click();
            } else {
                addButton.click();
            }
            
            const row = tbody.children[index];
            if (row) {
                fields.forEach(field => {
                    const input = row.querySelector(`[name*="[${field}]"]`);
                    if (input && rowData[field] !== undefined) {
                        if (input.type === 'checkbox') {
                            input.checked = rowData[field];
                        } else {
                            input.value = rowData[field];
                        }
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
            }
        });
    }

    // 🔄 INICIALIZAR AUTO-GUARDADO
    initAutoSave() {
        // Crear indicador de guardado
        this.createSaveIndicator();
        
        // Guardar cada 30 segundos
        setInterval(() => {
            this.saveDraft();
        }, 30000);
        
        // Guardar al cambiar cualquier campo
        document.addEventListener('input', () => {
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                this.saveDraft();
            }, 3000);
        });
        
        // Guardar al cambiar selects
        document.addEventListener('change', () => {
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                this.saveDraft();
            }, 1000);
        });
        
        // Guardar antes de cerrar la ventana
        window.addEventListener('beforeunload', () => {
            this.saveDraft();
        });
    }

    // 📍 CREAR INDICADOR DE GUARDADO
    createSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'save-indicator';
        indicator.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 9999;
            display: none;
        `;
        document.body.appendChild(indicator);
    }

    // 🔄 ACTUALIZAR INDICADOR
    updateSaveIndicator(message) {
        const indicator = document.getElementById('save-indicator');
        if (indicator) {
            indicator.textContent = message;
            indicator.style.display = 'block';
            
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 2000);
        }
    }

    // 🔔 MOSTRAR NOTIFICACIÓN DE BORRADOR ENCONTRADO
    showDraftNotification() {
        const drafts = this.getAllDrafts();
        if (drafts.length > 0) {
            const latestDraft = drafts[0];
            const date = new Date(latestDraft.timestamp);
            
            Swal.fire({
                icon: 'question',
                title: '📋 Borrador encontrado',
                html: `
                    <p>Se encontró un borrador de acta guardado automáticamente.</p>
                    <p><strong>Fecha:</strong> ${date.toLocaleString()}</p>
                    <p>¿Deseas recuperar los datos guardados?</p>
                `,
                showCancelButton: true,
                confirmButtonText: '✅ Recuperar datos',
                cancelButtonText: '🗑️ Empezar nuevo',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    this.loadDraft(latestDraft.data);
                    this.updateSaveIndicator('📋 Borrador recuperado');
                } else {
                    this.clearAllDrafts();
                    this.storageKey = 'acta_draft_' + Date.now();
                }
            });
        }
    }

    // 🗑️ LIMPIAR BORRADORES
    clearAllDrafts() {
        const keys = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key.startsWith('acta_draft_')) {
                keys.push(key);
            }
        }
        keys.forEach(key => localStorage.removeItem(key));
    }

    // 🎯 LIMPIAR BORRADOR ACTUAL (al enviar formulario)
    clearCurrentDraft() {
        localStorage.removeItem(this.storageKey);
    }
}

// 🚀 INICIALIZAR AUTO-GUARDADO CUANDO EL DOM ESTÉ LISTO
document.addEventListener('DOMContentLoaded', () => {
    // Esperar un poco para que otros scripts se inicialicen
    setTimeout(() => {
        window.autoGuardado = new AutoGuardado();
    }, 1000);
    
    // Limpiar borrador al enviar el formulario exitosamente
    const form = document.getElementById('actaForm');
    form?.addEventListener('submit', () => {
        // Limpiar después de un delay para asegurar que el envío fue exitoso
        setTimeout(() => {
            if (window.autoGuardado) {
                window.autoGuardado.clearCurrentDraft();
            }
        }, 2000);
    });
});
</script>
</body>
</html>
@endsection