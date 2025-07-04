<!-- filepath: resources/views/versions/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- 📋 HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">➕ Nueva Versión</h2>
                    <small class="text-muted">Crear una nueva versión del documento</small>
                </div>
                <a href="{{ route('versions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>

            <!-- 📝 FORMULARIO -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-code-branch"></i> 
                        Información de la Nueva Versión
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('versions.store') }}" method="POST" id="formVersion">
                        @csrf

                        <!-- ✅ INFORMACIÓN BÁSICA -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Número de Versión</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input type="text" class="form-control" 
                                           value="Versión {{ $siguienteVersion }}" readonly>
                                </div>
                                <small class="text-muted">Se asigna automáticamente</small>
                            </div>

                            <div class="col-md-4">
                                <label for="fecha_creacion" class="form-label fw-bold">
                                    Fecha de Creación <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_creacion" id="fecha_creacion" 
                                       class="form-control @error('fecha_creacion') is-invalid @enderror" 
                                       value="{{ old('fecha_creacion', date('Y-m-d')) }}" required>
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
                                        <option value="{{ $key }}" {{ old('estado', 'Pendiente') == $key ? 'selected' : '' }}>
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
                                      placeholder="Describa detalladamente los cambios realizados en esta versión...">{{ old('descripcion_cambio') }}</textarea>
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
                                       value="{{ old('revisado_por') }}" maxlength="255"
                                       placeholder="Nombre de la persona que revisó">
                                @error('revisado_por')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_revision" class="form-label">Fecha de Revisión</label>
                                <input type="date" name="fecha_revision" id="fecha_revision" 
                                       class="form-control @error('fecha_revision') is-invalid @enderror" 
                                       value="{{ old('fecha_revision') }}">
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
                                       value="{{ old('aprobado_por') }}" maxlength="255"
                                       placeholder="Nombre de la persona que aprobó">
                                @error('aprobado_por')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_aprobado" class="form-label">Fecha de Aprobación</label>
                                <input type="date" name="fecha_aprobado" id="fecha_aprobado" 
                                       class="form-control @error('fecha_aprobado') is-invalid @enderror" 
                                       value="{{ old('fecha_aprobado') }}">
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
                                       value="{{ old('fecha_aprobacion_documento') }}">
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
                            <button type="submit" class="btn btn-primary" id="btnGuardar">
                                <i class="fas fa-save"></i> Guardar Versión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const descripcionTextarea = document.getElementById('descripcion_cambio');
    const charCountSpan = document.getElementById('charCount');
    const formVersion = document.getElementById('formVersion');
    const btnGuardar = document.getElementById('btnGuardar');

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

        // Mostrar loading
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    });

    // ✅ AUTO-COMPLETAR FECHAS SEGÚN ESTADO
    const estadoSelect = document.getElementById('estado');
    const fechaRevision = document.getElementById('fecha_revision');
    const fechaAprobado = document.getElementById('fecha_aprobado');
    const fechaAprobacionDoc = document.getElementById('fecha_aprobacion_documento');
    const revisadoPor = document.getElementById('revisado_por');
    const aprobadoPor = document.getElementById('aprobado_por');

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