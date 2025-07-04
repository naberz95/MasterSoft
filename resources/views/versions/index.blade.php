<!-- filepath: resources/views/versions/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- 📊 HEADER CON ESTADÍSTICAS -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">📋 Gestión de Versiones</h2>
                    <small class="text-muted">Control de versiones del documento</small>
                </div>
                <a href="{{ route('versions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Versión
                </a>
            </div>

            <!-- 📈 TARJETAS DE ESTADÍSTICAS -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Versiones</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-code-branch fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aprobadas</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['aprobadas'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Revisadas</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['revisadas'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-eye fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['pendientes'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🔍 FILTROS DE BÚSQUEDA -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('versions.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="buscar" class="form-label">Buscar</label>
                            <input type="text" name="buscar" id="buscar" class="form-control" 
                                   value="{{ request('buscar') }}" placeholder="Descripción, revisor, aprobador...">
                        </div>
                        <div class="col-md-2">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select">
                                <option value="">Todos</option>
                                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Revisado" {{ request('estado') == 'Revisado' ? 'selected' : '' }}>Revisado</option>
                                <option value="Aprobado" {{ request('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="fecha_desde" class="form-label">Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                                   value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="fecha_hasta" class="form-label">Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                                   value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('versions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 📋 TABLA DE VERSIONES -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Lista de Versiones 
                        <span class="badge bg-secondary">{{ $versions->total() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($versions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Versión</th>
                                        <th>Fecha Creación</th>
                                        <th>Descripción del Cambio</th>
                                        <th>Revisado por</th>
                                        <th>Aprobado por</th>
                                        <th>Estado</th>
                                        <th>Actas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($versions as $version)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $version->version }}</span>
                                        </td>
                                        <td>{{ $version->fecha_creacion->format('d/m/Y') }}</td>
                                        <td>
                                            <div title="{{ $version->descripcion_cambio }}">
                                                {{ $version->descripcion_corta }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $version->revisado_por ?? '-' }}
                                            @if($version->fecha_revision)
                                                <br><small class="text-muted">{{ $version->fecha_revision->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $version->aprobado_por ?? '-' }}
                                            @if($version->fecha_aprobado)
                                                <br><small class="text-muted">{{ $version->fecha_aprobado->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td>{!! $version->estado_badge !!}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $version->contarActas() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('versions.edit', $version) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- 📄 PAGINACIÓN -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Mostrando {{ $versions->firstItem() }} a {{ $versions->lastItem() }} 
                                    de {{ $versions->total() }} versiones
                                </small>
                            </div>
                            <div>
                                {{ $versions->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-code-branch fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No hay versiones registradas</h5>
                            <p class="text-gray-500 mb-4">Comienza creando la primera versión del documento.</p>
                            <a href="{{ route('versions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Versión
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <div class="toast show" role="alert">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">¡Éxito!</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <div class="toast show" role="alert">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide toasts
    setTimeout(() => {
        document.querySelectorAll('.toast').forEach(toast => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        });
    }, 5000);
});
</script>
@endpush