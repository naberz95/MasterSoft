@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Editar Proyecto: {{ $proyecto->nombre }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
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
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Información del Proyecto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('proyectos.update', $proyecto) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Información actual -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-light border">
                                    <h6><i class="fas fa-info-circle"></i> Información Actual:</h6>
                                    <ul class="mb-0">
                                        <li><strong>Proyecto:</strong> {{ $proyecto->nombre }}</li>
                                        <li><strong>Empresa:</strong> {{ $proyecto->empresa->nombre ?? 'No asignada' }}</li>
                                        <li><strong>Actas registradas:</strong> {{ $proyecto->actas()->count() }}</li>
                                        <li><strong>Creado:</strong> {{ $proyecto->created_at->format('d/m/Y H:i') }}</li>
                                        @if($proyecto->updated_at->ne($proyecto->created_at))
                                            <li><strong>Última modificación:</strong> {{ $proyecto->updated_at->format('d/m/Y H:i') }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Nombre del proyecto -->
                            <div class="col-md-12 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-project-diagram"></i> Nombre del Proyecto <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $proyecto->nombre) }}" 
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
                                        <option value="{{ $empresa->id }}" 
                                                {{ (old('empresa_id', $proyecto->empresa_id) == $empresa->id) ? 'selected' : '' }}>
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

                            <!-- Warning si tiene actas asociadas -->
                            @if($proyecto->actas()->count() > 0)
                            <div class="col-12 mb-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>Advertencia:</strong> Este proyecto tiene {{ $proyecto->actas()->count() }} acta(s) asociada(s). 
                                    Los cambios podrían afectar la información de las actas existentes.
                                </div>
                            </div>
                            @endif

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Proyecto
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