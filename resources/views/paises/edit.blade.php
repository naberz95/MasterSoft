@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Editar País: {{ $pais->nombre }}</h1>
        <a href="{{ route('paises.index') }}" class="btn btn-outline-secondary">
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
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Información del País</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('paises.update', $pais) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información actual -->
                        <div class="alert alert-light border mb-3">
                            <h6><i class="fas fa-info-circle"></i> Información Actual:</h6>
                            <ul class="mb-0">
                                <li><strong>País:</strong> {{ $pais->nombre }}</li>
                                <li><strong>Ciudades asociadas:</strong> {{ $pais->ciudades()->count() }}</li>
                                <li><strong>Creado:</strong> {{ $pais->created_at->format('d/m/Y H:i') }}</li>
                                @if($pais->updated_at->ne($pais->created_at))
                                    <li><strong>Última modificación:</strong> {{ $pais->updated_at->format('d/m/Y H:i') }}</li>
                                @endif
                            </ul>
                        </div>

                        <!-- Nombre del País -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-flag"></i> Nombre del País <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $pais->nombre) }}" 
                                   placeholder="Ej: Colombia, México, España..." required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Warning si tiene ciudades asociadas -->
                        @if($pais->ciudades()->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Advertencia:</strong> Este país tiene {{ $pais->ciudades()->count() }} ciudad(es) asociada(s). 
                            Los cambios podrían afectar la información de las ciudades existentes.
                        </div>
                        @endif

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('paises.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar País
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
        // Auto-focus en el campo nombre
        document.getElementById('nombre').focus();
        
        // Capitalizar primera letra del nombre
        document.getElementById('nombre').addEventListener('input', function(e) {
            let valor = e.target.value;
            e.target.value = valor.charAt(0).toUpperCase() + valor.slice(1);
        });
    });
</script>
@endpush