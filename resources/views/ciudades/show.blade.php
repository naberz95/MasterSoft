@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-city"></i> {{ $ciudad->nombre }}
            <small class="text-muted">{{ $ciudad->pais->nombre ?? 'Sin país' }}</small>
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('ciudades.edit', $ciudad) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('ciudades.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong><i class="fas fa-city"></i> Ciudad:</strong></td>
                            <td>{{ $ciudad->nombre }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-flag"></i> País:</strong></td>
                            <td>
                                @if($ciudad->pais)
                                    <span class="badge bg-info">{{ $ciudad->pais->nombre }}</span>
                                @else
                                    <span class="text-muted">Sin país asignado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-calendar-plus"></i> Creada:</strong></td>
                            <td>{{ $ciudad->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($ciudad->updated_at->ne($ciudad->created_at))
                        <tr>
                            <td><strong><i class="fas fa-calendar-edit"></i> Actualizada:</strong></td>
                            <td>{{ $ciudad->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-primary">{{ $stats['total_actas'] }}</h3>
                            <small class="text-muted">Actas Registradas</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success">{{ $stats['actas_mes'] }}</h3>
                            <small class="text-muted">Este Mes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actas Asociadas -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt"></i> Actas Registradas en {{ $ciudad->nombre }}
                        <span class="badge bg-light text-dark">{{ $ciudad->actas->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($ciudad->actas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
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
                                    @foreach($ciudad->actas as $acta)
                                    <tr>
                                        <td><strong>{{ $acta->numero }}</strong></td>
                                        <td>{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @if($acta->empresa)
                                                <span class="badge bg-secondary">{{ $acta->empresa->nombre }}</span>
                                            @else
                                                <span class="text-muted">Sin empresa</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($acta->proyecto)
                                                {{ $acta->proyecto->nombre }}
                                            @else
                                                <span class="text-muted">Sin proyecto</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('actas.show', $acta) }}" class="btn btn-sm btn-outline-primary" title="Ver acta">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('actas.edit', $acta) }}" class="btn btn-sm btn-outline-warning" title="Editar acta">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('actas.exportPdf', $acta) }}" class="btn btn-sm btn-outline-danger" title="Descargar PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No hay actas registradas en esta ciudad</h6>
                            <p class="text-muted mb-3">Las actas que se creen usando esta ciudad aparecerán aquí.</p>
                            <a href="{{ route('actas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Acta
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('ciudades.edit', $ciudad) }}" class="btn btn-warning w-100">
                                <i class="fas fa-edit"></i> Editar Ciudad
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('actas.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus"></i> Nueva Acta
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('ciudades.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-list"></i> Lista Ciudades
                            </a>
                        </div>
                        <div class="col-md-3">
                            @if($ciudad->actas->count() == 0)
                                <form action="{{ route('ciudades.destroy', $ciudad) }}" method="POST" 
                                      onsubmit="return confirm('¿Está seguro de eliminar la ciudad {{ $ciudad->nombre }}?')" class="w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-danger w-100" disabled title="No se puede eliminar (tiene actas asociadas)">
                                    <i class="fas fa-ban"></i> No Eliminable
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection