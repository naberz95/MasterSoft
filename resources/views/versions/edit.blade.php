<!-- filepath: resources/views/versions/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- 📋 HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">✏️ Editar Versión {{ $version->version }}</h2>
                    <small class="text-muted">Actualizar información de la versión</small>
                </div>
                <a href="{{ route('versions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>

            <!-- 📊 INFORMACIÓN RÁPIDA -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-left-primary h-100">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Versión Actual
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $version->version }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-info h-100">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Estado Actual
                            </div>
                            <div class="h6 mb-0">
                                {!! $version->estado_badge !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-warning h-100">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Actas Asociadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $version->contarActas() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📝 FORMULARIO -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-edit"></i> 
                        Actualizar Información de la Versión
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('versions.update', $version) }}" method="POST" id="formVersion">
                        @csrf
                        @method('PUT')

                        <!-- ✅ INFORMACIÓN BÁSICA -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Número de Versión</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-secondary text-white">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="text" class="form-control" 
                                           value="Versión {{ $version->version }}" readonly>
                                </div>
                                <small class="text-muted">No se puede modificar</small>
                            </div>

                            <div class="col-md-4">
                                <label for="fecha_creacion" class="form-label fw-bold">
                                    Fecha de Creación <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_creacion" id="fecha_creacion" 
                                       class="form-control @error('fecha_creacion') is-invalid @enderror" 
                                       value="{{ old('fecha_creacion', $version->fecha_creacion->format('Y-m-d')) }}" required>
                                @error('fecha_creacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="estado" class="form-label fw-bold">
                                    Estado <span class="text-danger">*</span>
                                </label>
                                <select name="estado" id="estado" 
                                        class="form-select @error('estado') is-invalid @enderror" required>
                                    @foreach($estados as $key => $value)
                                        <option value="{{ $key }}" {{ old('estado', $version->estado) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ✅ DESCRIPCIÓN DEL CAMBIO -->
                        <div class="mb-4">
                            <label for="descripcion_cambio" class="form-label fw-bold">
                                Descripción del Cambio <span class="text-danger">*</span>
                            </label>
                            <textarea name="descripcion_cambio" id="descripcion_cambio" 
                                      class="form-control @error('descripcion_cambio') is-invalid @enderror" 
                                      rows="4" required maxlength="1000"
                                      placeholder="Describa detalladamente los cambios realizados en esta versión...">{{ old('descripcion_cambio', $version->descripcion_cambio) }}</textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/1000 caracteres
                            </div>
                            @error('descripcion_cambio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- ✅ INFORMACIÓN DE REVISIÓN -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-eye"></i> Información de Revisión
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="revisado_por" class="form-label">Revisado por</label>
                                <input type="text" name="revisado_por" id="revisado_por" 
                                       class="form-control @error('revisado_por') is-invalid @enderror" 
                                       value="{{ old('revisado_por', $version->revisado_por) }}" maxlength="255"
                                       placeholder="Nombre de la persona que revisó">
                                @error('revisado_por')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_revision" class="form-label">Fecha de Revisión</label>
                                <input type="date" name="fecha_revision" id="fecha_revision" 
                                       class="form-control @error('fecha_revision') is-invalid @enderror" 
                                       value="{{ old('fecha_revision', $version->fecha_revision ? $version->fecha_revision->format('Y-m-d') : '') }}">
                                @error('fecha_revision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ✅ INFORMACIÓN DE APROBACIÓN -->
                        <h6 class="text-success mb-3">
                            <i class="fas fa-check-circle"></i> Información de Aprobación
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="aprobado_por" class="form-label">Aprobado por</label>
                                <input type="text" name="aprobado_por" id="aprobado_por" 
                                       class="form-control @error('aprobado_por') is-invalid @enderror" 
                                       value="{{ old('aprobado_por', $version->aprobado_por) }}" maxlength="255"
                                       placeholder="Nombre de la persona que aprobó">
                                @error('aprobado_por')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_aprobado" class="form-label">Fecha de Aprobación</label>
                                <input type="date" name="fecha_aprobado" id="fecha_aprobado" 
                                       class="form-control @error('fecha_aprobado') is-invalid @enderror" 
                                       value="{{ old('fecha_aprobado', $version->fecha_aprobado ? $version->fecha_aprobado->format('Y-m-d') : '') }}">
                                @error('fecha_aprobado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ✅ FECHA DE APROBACIÓN DEL DOCUMENTO -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="fecha_aprobacion_documento" class="form-label">
                                    Fecha de Aprobación del Documento
                                </label>
                                <input type="date" name="fecha_aprobacion_documento" id="fecha_aprobacion_documento" 
                                       class="form-control @error('fecha_aprobacion_documento') is-invalid @enderror" 
                                       value="{{ old('fecha_aprobacion_documento', $version->fecha_aprobacion_documento ? $version->fecha_aprobacion_documento->format('Y-m-d') : '') }}">
                                <small class="text-muted">Fecha en que se aprueba oficialmente el documento</small>
                                @error('fecha_aprobacion_documento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ✅ BOTONES DE ACCIÓN -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('versions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnActualizar">
                                <i class="fas fa-save"></i> Actualizar Versión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const descripcionTextarea = document.getElementById('descripcion_cambio');
    const charCountSpan = document.getElementById('charCount');
    const formVersion = document.getElementById('formVersion');
    const btnActualizar = document.getElementById('btnActualizar');

    // ✅ CONTADOR DE CARACTERES
    function actualizarContador() {
        const length = descripcionTextarea.value.length;
        charCountSpan.textContent = length;
        
        if (length > 900) {
            charCountSpan.className = 'text-warning';
        } else if (length > 950) {
            charCountSpan.className = 'text-danger';
        } else {
            charCountSpan.className = 'text-muted';
        }
    }

    descripcionTextarea.addEventListener('input', actualizarContador);
    actualizarContador(); // Inicializar

    // ✅ VALIDACIÓN AL ENVIAR FORMULARIO
    formVersion.addEventListener('submit', function(e) {
        const descripcion = descripcionTextarea.value.trim();
        
        if (descripcion.length < 10) {
            e.preventDefault();
            alert('La descripción del cambio debe tener al menos 10 caracteres.');
            descripcionTextarea.focus();
            return false;
        }

        // Confirmación para cambios importantes
        if (!confirm('¿Está seguro de que desea actualizar esta versión?')) {
            e.preventDefault();
            return false;
        }

        // Mostrar loading
        btnActualizar.disabled = true;
        btnActualizar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
    });

    // ✅ AUTO-COMPLETAR FECHAS SEGÚN ESTADO
    const estadoSelect = document.getElementById('estado');
    const fechaRevision = document.getElementById('fecha_revision');
    const fechaAprobado = document.getElementById('fecha_aprobado');
    const fechaAprobacionDoc = document.getElementById('fecha_aprobacion_documento');

    estadoSelect.addEventListener('change', function() {
        const estado = this.value;
        const hoy = new Date().toISOString().split('T')[0];

        if (estado === 'Revisado' || estado === 'Aprobado') {
            if (!fechaRevision.value) fechaRevision.value = hoy;
        }

        if (estado === 'Aprobado') {
            if (!fechaAprobado.value) fechaAprobado.value = hoy;
            if (!fechaAprobacionDoc.value) fechaAprobacionDoc.value = hoy;
        }
    });
});
</script>
@endpush