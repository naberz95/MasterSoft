@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Nuevo Proyecto</h1>
        <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="mb-0"><i class="fas fa-project-diagram"></i> Información del Proyecto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('proyectos.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Nombre del proyecto -->
                            <div class="col-md-12 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-project-diagram"></i> Nombre del Proyecto <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}" 
                                       placeholder="Ej: Implementación Sistema CRM, Auditoría Financiera..." required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Empresa -->
                            <div class="col-md-12 mb-3">
                                <label for="empresa_id" class="form-label">
                                    <i class="fas fa-building"></i> Empresa <span class="text-danger">*</span>
                                </label>
                                <select name="empresa_id" id="empresa_id"
                                        class="form-select @error('empresa_id') is-invalid @enderror" required>
                                    <option value="">— Seleccione una empresa —</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->nombre }}
                                            @if($empresa->nit)
                                                — NIT: {{ $empresa->nit }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('empresa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información adicional -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Información:</strong> Este proyecto se utilizará para agrupar las actas relacionadas con la empresa seleccionada.
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Crear Proyecto
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