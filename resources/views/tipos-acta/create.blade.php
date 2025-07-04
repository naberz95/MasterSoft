@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Crear Nuevo Tipo de Acta</h1>
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
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus"></i> Crear Nuevo Tipo de Acta</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tipos-acta.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
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
                                       value="{{ old('nombre') }}" 
                                       placeholder="Ej: Acta de Reunión, Acta de Seguimiento" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-lightbulb"></i> 
                                    <strong>Sugerencias:</strong> Acta de Reunión, Acta de Seguimiento, Acta de Entrega, Acta de Kick-off, Acta de Cierre
                                </div>
                            </div>

                            <!-- Ejemplos -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-light">
                                    <h6><i class="fas fa-lightbulb"></i> Tipos comunes de acta:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>Acta de Reunión</li>
                                                <li>Acta de Seguimiento</li>
                                                <li>Acta de Avance</li>
                                                <li>Acta de Entrega</li>
                                                <li>Acta de Kick-off</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>Acta de Cierre</li>
                                                <li>Acta de Comité</li>
                                                <li>Acta de Revisión</li>
                                                <li>Acta de Aprobación</li>
                                                <li>Acta de Capacitación</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('tipos-acta.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Crear Tipo de Acta
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