@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- ENCABEZADO PRINCIPAL -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" 
         style="background-color: #3c6bd3; color: white; margin: -15px -15px 20px -15px; padding: 20px 30px !important; border-radius: 0;">
        <h1 class="h2 mb-0" style="color: white;">
            <i class="fas fa-file-alt me-2"></i>
            MASTERSOFT
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('actas.index') }}" class="btn btn-light">
                <i class="fas fa-list"></i> Ver Actas
            </a>
            <a href="{{ route('actas.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Crear Acta
            </a>
        </div>
    </div>

    <!-- ESTADÍSTICAS PRINCIPALES -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Total Actas</h6>
                            <h3 class="text-primary mb-0">{{ \App\Models\Acta::count() }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Facturables</h6>
                            <h3 class="text-success mb-0">{{ \App\Models\Acta::where('facturable', true)->count() }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Empresas</h6>
                            <h3 class="text-warning mb-0">{{ \App\Models\Empresa::count() }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Proyectos</h6>
                            <h3 class="text-info mb-0">{{ \App\Models\Proyecto::count() }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-project-diagram fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- ACTAS RECIENTES -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-primary"></i>
                        Actas Recientes
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $actasRecientes = \App\Models\Acta::with(['tipoActa', 'empresa', 'proyecto', 'ciudad'])
                                                          ->orderBy('created_at', 'desc')
                                                          ->take(5)
                                                          ->get();
                    @endphp

                    @if($actasRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Número</th>
                                        <th>Fecha</th>
                                        <th>Empresa</th>
                                        <th>Proyecto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actasRecientes as $acta)
                                    <tr>
                                        <td>
                                            <strong>{{ $acta->numero }}</strong>
                                            <br><small class="text-muted">{{ $acta->tipoActa->nombre ?? '-' }}</small>
                                        </td>
                                        <td>
                                            {{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : '-' }}
                                            @if($acta->ciudad)
                                                <br><small class="text-muted">{{ $acta->ciudad->nombre }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $acta->empresa->nombre ?? '-' }}</td>
                                        <td>{{ $acta->proyecto->nombre ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('actas.show', $acta) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('actas.edit', $acta) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('actas.exportPdf', $acta) }}" class="btn btn-sm btn-outline-danger" title="PDF" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('actas.index') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> Ver Todas las Actas
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No hay actas registradas</h6>
                            <p class="text-muted mb-3">Comience creando la primera acta del sistema.</p>
                            <a href="{{ route('actas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Acta
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- GESTIÓN DEL SISTEMA -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs text-success"></i>
                        Gestión del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <!-- CONFIGURACIÓN BÁSICA -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-database"></i> Configuración Básica
                        </h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('tipos-acta.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-tags"></i> Tipos de Acta
                                <span class="badge bg-primary ms-2">{{ \App\Models\TipoActa::count() }}</span>
                            </a>
                            <a href="{{ route('versions.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-code-branch"></i> Versiones
                                <span class="badge bg-info ms-2">{{ \App\Models\Version::count() }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- UBICACIONES -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-map-marker-alt"></i> Ubicaciones
                        </h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('paises.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-globe"></i> Países
                                <span class="badge bg-secondary ms-2">{{ \App\Models\Pais::count() }}</span>
                            </a>
                            <a href="{{ route('ciudades.index') }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-city"></i> Ciudades
                                <span class="badge bg-warning ms-2">{{ \App\Models\Ciudad::count() }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- ENTIDADES PRINCIPALES -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-building"></i> Entidades Principales
                        </h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('empresas.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-building"></i> Empresas
                                <span class="badge bg-success ms-2">{{ \App\Models\Empresa::count() }}</span>
                            </a>
                            <a href="{{ route('proyectos.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-project-diagram"></i> Proyectos
                                <span class="badge bg-info ms-2">{{ \App\Models\Proyecto::count() }}</span>
                            </a>
                            <a href="{{ route('personas.index') }}" class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-users"></i> Personas
                                <span class="badge bg-dark ms-2">{{ \App\Models\Persona::count() }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- ESTADÍSTICAS ADICIONALES -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-chart-pie"></i> Resumen Rápido
                        </h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <small class="text-muted d-block">Compromisos</small>
                                    <strong class="text-primary">{{ \App\Models\Compromiso::count() }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Pendientes</small>
                                <strong class="text-warning">{{ \App\Models\Compromiso::where('estado', 'Pendiente')->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS Y ESTADÍSTICAS AVANZADAS -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-info"></i>
                        Actas por Mes (Últimos 6 meses)
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $actasPorMes = \App\Models\Acta::selectRaw('MONTH(fecha) as mes, YEAR(fecha) as año, COUNT(*) as total')
                                                        ->where('fecha', '>=', now()->subMonths(6))
                                                        ->groupBy('año', 'mes')
                                                        ->orderBy('año', 'desc')
                                                        ->orderBy('mes', 'desc')
                                                        ->get();
                    @endphp

                    @if($actasPorMes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th class="text-end">Actas</th>
                                        <th>Progreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actasPorMes as $periodo)
                                        @php
                                            $nombreMes = \Carbon\Carbon::create($periodo->año, $periodo->mes)->translatedFormat('F Y');
                                            $maxActas = $actasPorMes->max('total');
                                            $porcentaje = $maxActas > 0 ? ($periodo->total / $maxActas) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $nombreMes }}</td>
                                            <td class="text-end"><strong>{{ $periodo->total }}</strong></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-info" role="progressbar" 
                                                         style="width: {{ $porcentaje }}%" 
                                                         aria-valuenow="{{ $periodo->total }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="{{ $maxActas }}">
                                                        {{ number_format($porcentaje, 0) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay datos suficientes para mostrar estadísticas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks text-warning"></i>
                        Estado de Compromisos
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $compromisosPendientes = \App\Models\Compromiso::where('estado', 'Pendiente')->count();
                        $compromisosEnProceso = \App\Models\Compromiso::where('estado', 'En proceso')->count();
                        $compromisosCumplidos = \App\Models\Compromiso::where('estado', 'Cumplido')->count();
                        $totalCompromisos = $compromisosPendientes + $compromisosEnProceso + $compromisosCumplidos;
                    @endphp

                    @if($totalCompromisos > 0)
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="p-3 border rounded">
                                    <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                    <h4 class="text-warning mb-1">{{ $compromisosPendientes }}</h4>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 border rounded">
                                    <i class="fas fa-spinner text-info fa-2x mb-2"></i>
                                    <h4 class="text-info mb-1">{{ $compromisosEnProceso }}</h4>
                                    <small class="text-muted">En Proceso</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 border rounded">
                                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                    <h4 class="text-success mb-1">{{ $compromisosCumplidos }}</h4>
                                    <small class="text-muted">Cumplidos</small>
                                </div>
                            </div>
                        </div>

                        <!-- Progress bars -->
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <small>Cumplidos</small>
                                <small>{{ number_format(($compromisosCumplidos / $totalCompromisos) * 100, 1) }}%</small>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" style="width: {{ ($compromisosCumplidos / $totalCompromisos) * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <small>En Proceso</small>
                                <small>{{ number_format(($compromisosEnProceso / $totalCompromisos) * 100, 1) }}%</small>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-info" style="width: {{ ($compromisosEnProceso / $totalCompromisos) * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <small>Pendientes</small>
                                <small>{{ number_format(($compromisosPendientes / $totalCompromisos) * 100, 1) }}%</small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: {{ ($compromisosPendientes / $totalCompromisos) * 100 }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay compromisos registrados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-info:hover,
.btn-outline-warning:hover,
.btn-outline-secondary:hover,
.btn-outline-dark:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.progress {
    height: 8px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.7rem;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}
</style>
@endsection