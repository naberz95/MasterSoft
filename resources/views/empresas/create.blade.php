@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Nueva Empresa</h1>
        <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary">
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
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Información de la Empresa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Información Básica -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary"><i class="fas fa-info-circle"></i> Información Básica</h6>
                                <hr>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-12 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-building"></i> Nombre de la Empresa <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre') }}" 
                                       placeholder="Nombre completo de la empresa" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIT y Dígito Verificación -->
                            <div class="col-md-6 mb-3">
                                <label for="nit" class="form-label">
                                    <i class="fas fa-id-card"></i> NIT
                                </label>
                                <input type="text" name="nit" id="nit" 
                                       class="form-control @error('nit') is-invalid @enderror" 
                                       value="{{ old('nit') }}" 
                                       placeholder="123456789">
                                @error('nit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="digito_verificacion" class="form-label">
                                    <i class="fas fa-check"></i> Dígito Verificación
                                </label>
                                <input type="text" name="digito_verificacion" id="digito_verificacion" 
                                       class="form-control @error('digito_verificacion') is-invalid @enderror" 
                                       value="{{ old('digito_verificacion') }}" 
                                       maxlength="1" placeholder="0">
                                @error('digito_verificacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-12 mb-3">
                                <label for="direccion" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Dirección
                                </label>
                                <input type="text" name="direccion" id="direccion" 
                                       class="form-control @error('direccion') is-invalid @enderror" 
                                       value="{{ old('direccion') }}" 
                                       placeholder="Dirección completa de la empresa">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información de Contacto -->
                            <div class="col-12 mb-4 mt-4">
                                <h6 class="text-primary"><i class="fas fa-address-book"></i> Información de Contacto</h6>
                                <hr>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="text" name="telefono" id="telefono" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       value="{{ old('telefono') }}" 
                                       placeholder="+57 300 123 4567">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" name="email" id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="contacto@empresa.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Logo -->
                            <div class="col-12 mb-4 mt-4">
                                <h6 class="text-primary"><i class="fas fa-image"></i> Logo de la Empresa</h6>
                                <hr>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="logo_empresa" class="form-label">
                                    <i class="fas fa-image"></i> Logo
                                </label>
                                <input type="file" name="logo_empresa" id="logo_empresa" 
                                       class="form-control @error('logo_empresa') is-invalid @enderror" 
                                       accept="image/*" onchange="previewLogo(event)">
                                <div class="form-text">Formatos permitidos: JPG, PNG, GIF, SVG. Tamaño máximo: 2MB.</div>
                                <img id="logoPreview" class="img-thumbnail mt-2" style="max-height: 150px; display: none;">
                                @error('logo_empresa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información adicional -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Información:</strong> Esta empresa se utilizará para crear proyectos y asociar personas en las actas del sistema.
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Crear Empresa
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
<script>
    function previewLogo(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('logoPreview');
        
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