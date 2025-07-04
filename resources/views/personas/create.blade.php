@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Nueva Persona</h1>
        <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle"></i> Por favor corrija los siguientes errores:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Información de la Persona</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Información Básica -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary"><i class="fas fa-info-circle"></i> Información Básica</h6>
                                <hr>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user"></i> Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre') }}" 
                                       placeholder="Nombre completo de la persona" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Iniciales -->
                            <div class="col-md-3 mb-3">
                                <label for="iniciales" class="form-label">
                                    <i class="fas fa-id-badge"></i> Iniciales
                                </label>
                                <input type="text" name="iniciales" id="iniciales" 
                                       class="form-control @error('iniciales') is-invalid @enderror" 
                                       value="{{ old('iniciales') }}" 
                                       placeholder="Ej: JPS" maxlength="10">
                                @error('iniciales')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cargo -->
                            <div class="col-md-3 mb-3">
                                <label for="cargo" class="form-label">
                                    <i class="fas fa-briefcase"></i> Cargo
                                </label>
                                <input type="text" name="cargo" id="cargo" 
                                       class="form-control @error('cargo') is-invalid @enderror" 
                                       value="{{ old('cargo') }}" 
                                       placeholder="Cargo o posición">
                                @error('cargo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Empresa -->
                            <div class="col-md-6 mb-3">
                                <label for="empresa_id" class="form-label">
                                    <i class="fas fa-building"></i> Empresa <span class="text-danger">*</span>
                                </label>
                                <select name="empresa_id" id="empresa_id" 
                                        class="form-select @error('empresa_id') is-invalid @enderror" required>
                                    <option value="">— Seleccione una empresa —</option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('empresa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información de Identificación -->
                            <div class="col-12 mb-4 mt-4">
                                <h6 class="text-primary"><i class="fas fa-id-card"></i> Información de Identificación</h6>
                                <hr>
                            </div>

                            <!-- Cédula -->
                            <div class="col-md-4 mb-3">
                                <label for="cedula" class="form-label">
                                    <i class="fas fa-id-card"></i> Cédula
                                </label>
                                <input type="text" name="cedula" id="cedula" 
                                       class="form-control @error('cedula') is-invalid @enderror" 
                                       value="{{ old('cedula') }}" 
                                       placeholder="Número de cédula">
                                @error('cedula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha Expedición Cédula -->
                            <div class="col-md-4 mb-3">
                                <label for="fecha_expedicion_cedula" class="form-label">
                                    <i class="fas fa-calendar"></i> Fecha Expedición
                                </label>
                                <input type="date" name="fecha_expedicion_cedula" id="fecha_expedicion_cedula" 
                                       class="form-control @error('fecha_expedicion_cedula') is-invalid @enderror" 
                                       value="{{ old('fecha_expedicion_cedula') }}">
                                @error('fecha_expedicion_cedula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lugar Expedición Cédula -->
                            <div class="col-md-4 mb-3">
                                <label for="lugar_expedicion_cedula" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Lugar Expedición
                                </label>
                                <input type="text" name="lugar_expedicion_cedula" id="lugar_expedicion_cedula" 
                                       class="form-control @error('lugar_expedicion_cedula') is-invalid @enderror" 
                                       value="{{ old('lugar_expedicion_cedula') }}" 
                                       placeholder="Ciudad de expedición">
                                @error('lugar_expedicion_cedula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información Profesional -->
                            <div class="col-12 mb-4 mt-4">
                                <h6 class="text-primary"><i class="fas fa-graduation-cap"></i> Información Profesional (Opcional)</h6>
                                <hr>
                            </div>

                            <!-- Tarjeta Profesional -->
                            <div class="col-md-6 mb-3">
                                <label for="tarjeta_profesional" class="form-label">
                                    <i class="fas fa-graduation-cap"></i> Tarjeta Profesional
                                </label>
                                <input type="text" name="tarjeta_profesional" id="tarjeta_profesional" 
                                       class="form-control @error('tarjeta_profesional') is-invalid @enderror" 
                                       value="{{ old('tarjeta_profesional') }}" 
                                       placeholder="Número de tarjeta profesional">
                                @error('tarjeta_profesional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha Tarjeta -->
                            <div class="col-md-6 mb-3">
                                <label for="fecha_tarjeta" class="form-label">
                                    <i class="fas fa-calendar"></i> Fecha Tarjeta
                                </label>
                                <input type="date" name="fecha_tarjeta" id="fecha_tarjeta" 
                                       class="form-control @error('fecha_tarjeta') is-invalid @enderror" 
                                       value="{{ old('fecha_tarjeta') }}">
                                @error('fecha_tarjeta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Firma Digital -->
                            <div class="col-12 mb-4 mt-4">
                                <h6 class="text-primary"><i class="fas fa-signature"></i> Firma Digital</h6>
                                <hr>
                            </div>

                            <!-- Método de Firma -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">¿Cómo desea agregar la firma?</label>
                                <select class="form-select" id="metodo-firma" onchange="toggleFirmaMethod()">
                                    <option value="">Seleccione una opción</option>
                                    <option value="archivo">Subir imagen de firma</option>
                                    <option value="dibujar">Dibujar firma digital</option>
                                </select>
                            </div>

                            <!-- Subida de Archivo -->
                            <div class="col-md-12 mb-3" id="firma-archivo-container" style="display: none;">
                                <label class="form-label">Imagen de la Firma</label>
                                <input type="file" name="firma" class="form-control" accept="image/*" onchange="previewFirma(event)">
                                <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.</div>
                                <img id="firmaPreview" class="img-thumbnail mt-2" style="max-height: 150px; display: none;">
                            </div>

                            <!-- Canvas para Dibujar -->
                            <div class="col-md-12 mb-3" id="firma-canvas-container" style="display: none;">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <canvas id="signature-pad" class="border" width="600" height="200"></canvas>
                                        <br>
                                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature()">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                        <input type="hidden" name="firma_base64" id="firma_base64">
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Crear Persona
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePad = null;

    function toggleFirmaMethod() {
        const metodo = document.getElementById('metodo-firma').value;
        const archivoContainer = document.getElementById('firma-archivo-container');
        const canvasContainer = document.getElementById('firma-canvas-container');

        // Ocultar ambos contenedores
        archivoContainer.style.display = 'none';
        canvasContainer.style.display = 'none';

        if (metodo === 'archivo') {
            archivoContainer.style.display = 'block';
        } else if (metodo === 'dibujar') {
            canvasContainer.style.display = 'block';
            initSignaturePad();
        }
    }

    function initSignaturePad() {
        if (signaturePad) return;
        
        const canvas = document.getElementById('signature-pad');
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Actualizar campo oculto cuando se dibuje
        signaturePad.addEventListener('endStroke', () => {
            document.getElementById('firma_base64').value = signaturePad.toDataURL();
        });
    }

    function clearSignature() {
        if (signaturePad) {
            signaturePad.clear();
            document.getElementById('firma_base64').value = '';
        }
    }

    function previewFirma(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('firmaPreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus en el campo nombre
        document.getElementById('nombre').focus();
    });
</script>
@endpush