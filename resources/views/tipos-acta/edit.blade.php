@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Editar Tipo de Acta: {{ $tipo->nombre }}</h1>
        <a href="{{ route('tipos-acta.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Tipo de Acta</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tipos-acta.update', $tipo) }}" method="POST">
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
                                                <li><strong>Nombre:</strong> {{ $tipo->nombre }}</li>
                                                <li><strong>Actas creadas:</strong> {{ $tipo->actas()->count() }}</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li><strong>Creado:</strong> {{ $tipo->created_at->format('d/m/Y H:i') }}</li>
                                                <li><strong>Actualizado:</strong> {{ $tipo->updated_at->format('d/m/Y H:i') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del tipo -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary"><i class="fas fa-info-circle"></i> Información del Tipo</h6>
                                <hr>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-12 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-file-alt"></i> Nombre del Tipo de Acta <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre', $tipo->nombre) }}" 
                                       placeholder="Ej: Acta de Reunión, Acta de Seguimiento" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Warning si tiene actas asociadas -->
                            @if($tipo->actas()->count() > 0)
                            <div class="col-12 mb-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>Advertencia:</strong> Este tipo de acta tiene {{ $tipo->actas()->count() }} acta(s) asociada(s). 
                                    Los cambios podrían afectar la información de las actas existentes.
                                </div>
                            </div>
                            @endif

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('tipos-acta.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Tipo
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
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus en el campo nombre
        document.getElementById('nombre').focus();
    });
</script>
@endpush