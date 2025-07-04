@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Nueva Ciudad</h1>
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
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Información de la Ciudad</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('ciudades.store') }}" method="POST">
                        @csrf
                        
                        <!-- Nombre de la Ciudad -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Nombre de la Ciudad <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" 
                                   placeholder="Nombre de la ciudad" required>
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
                                    <option value="{{ $pais->id }}" {{ old('pais_id') == $pais->id ? 'selected' : '' }}>
                                        {{ $pais->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pais_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Información:</strong> Esta ciudad se utilizará para ubicar las actas en el sistema.
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('ciudades.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Ciudad
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
    });
</script>
@endpush