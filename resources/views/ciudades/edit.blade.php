@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Editar Ciudad: {{ $ciudad->nombre }}</h1>
        <a href="{{ route('ciudades.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Información de la Ciudad</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('ciudades.update', $ciudad) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información actual -->
                        <div class="alert alert-light border mb-3">
                            <h6><i class="fas fa-info-circle"></i> Información Actual:</h6>
                            <ul class="mb-0">
                                <li><strong>Ciudad:</strong> {{ $ciudad->nombre }}</li>
                                <li><strong>País:</strong> {{ $ciudad->pais->nombre }}</li>
                                <li><strong>Actas registradas:</strong> {{ $ciudad->actas()->count() }}</li>
                                <li><strong>Creada:</strong> {{ $ciudad->created_at->format('d/m/Y H:i') }}</li>
                            </ul>
                        </div>

                        <!-- Nombre de la Ciudad -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Nombre de la Ciudad <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $ciudad->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- País -->
                        <div class="mb-3">
                            <label for="pais_id" class="form-label">
                                <i class="fas fa-flag"></i> País <span class="text-danger">*</span>
                            </label>
                            <select name="pais_id" id="pais_id" 
                                    class="form-select @error('pais_id') is-invalid @enderror" required>
                                <option value="">— Seleccione un país —</option>
                                @foreach($paises as $pais)
                                    <option value="{{ $pais->id }}" 
                                            {{ (old('pais_id', $ciudad->pais_id) == $pais->id) ? 'selected' : '' }}>
                                        {{ $pais->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pais_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Warning si tiene actas asociadas -->
                        @if($ciudad->actas()->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Advertencia:</strong> Esta ciudad tiene {{ $ciudad->actas()->count() }} acta(s) asociada(s). 
                            Los cambios podrían afectar la información de los registros existentes.
                        </div>
                        @endif

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('ciudades.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Ciudad
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
        document.getElementById('nombre').focus();
    });
</script>
@endpush