@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Editar Empresa: {{ $empresa->nombre }}</h1>
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
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Información de la Empresa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('empresas.update', $empresa) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Información actual -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-light border">
                                    <h6><i class="fas fa-info-circle"></i> Información Actual:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li><strong>Empresa:</strong> {{ $empresa->nombre }}</li>
                                                <li><strong>NIT:</strong> {{ $empresa->nit ? $empresa->nit . '-' . $empresa->digito_verificacion : 'No definido' }}</li>
                                                <li><strong>Dirección:</strong> {{ $empresa->direccion ?? 'No definida' }}</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li><strong>Teléfono:</strong> {{ $empresa->telefono ?? 'No definido' }}</li>
                                                <li><strong>Email:</strong> {{ $empresa->email ?? 'No definido' }}</li>
                                                <li><strong>Proyectos:</strong> {{ $empresa->proyectos()->count() }}</li>
                                                <li><strong>Actas:</strong> {{ $empresa->actas()->count() }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                       value="{{ old('nombre', $empresa->nombre) }}" 
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
                                       value="{{ old('nit', $empresa->nit) }}" 
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
                                       value="{{ old('digito_verificacion', $empresa->digito_verificacion) }}" 
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
                                       value="{{ old('direccion', $empresa->direccion) }}" 
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
                                       value="{{ old('telefono', $empresa->telefono) }}" 
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
                                       value="{{ old('email', $empresa->email) }}" 
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

                            <!-- Logo Actual -->
                            @if($empresa->logo_empresa)
                            <div class="col-md-12 mb-3 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Logo Actual:</h6>
                                        <img src="{{ asset('storage/logos/' . $empresa->logo_empresa) }}" 
                                             alt="Logo actual" class="img-thumbnail mb-2" style="max-height: 150px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="eliminar_logo" value="1" id="eliminar_logo">
                                            <label class="form-check-label text-danger" for="eliminar_logo">
                                                <i class="fas fa-trash"></i> Eliminar logo actual
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Nuevo Logo -->
                            <div class="col-md-12 mb-3">
                                <label for="logo_empresa" class="form-label">
                                    <i class="fas fa-image"></i> 
                                    @if($empresa->logo_empresa)
                                        Cambiar Logo
                                    @else
                                        Logo de la Empresa
                                    @endif
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

                            <!-- Warning si tiene proyectos o actas asociadas -->
                            @if($empresa->proyectos()->count() > 0 || $empresa->actas()->count() > 0)
                            <div class="col-12 mb-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>Advertencia:</strong> Esta empresa tiene {{ $empresa->proyectos()->count() }} proyecto(s) y {{ $empresa->actas()->count() }} acta(s) asociada(s). 
                                    Los cambios podrían afectar la información de los registros existentes.
                                </div>
                            </div>
                            @endif

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Empresa
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