@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">📊 Gestión de Proyectos</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('proyectos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Proyecto
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Proyectos</h5>
                    <h2 class="text-primary">{{ $proyectos->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-info">Empresas Únicas</h5>
                    <h2 class="text-info">{{ $proyectos->pluck('empresa.nombre')->unique()->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-success">Con Actas</h5>
                    <h2 class="text-success">{{ $proyectos->where('actas_count', '>', 0)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Sin Actas</h5>
                    <h2 class="text-warning">{{ $proyectos->where('actas_count', 0)->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Proyectos -->
    <div class="card">
        <div class="card-body">
            @if($proyectos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-project-diagram"></i> Proyecto</th>
                                <th><i class="fas fa-building"></i> Empresa</th>
                                <th><i class="fas fa-file-alt"></i> Actas</th>
                                <th><i class="fas fa-calendar-plus"></i> Creado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyectos as $proyecto)
                            <tr>
                                <td>
                                    <strong>{{ $proyecto->nombre }}</strong>
                                </td>
                                <td>
                                    @if($proyecto->empresa)
                                        <span class="badge bg-info">{{ $proyecto->empresa->nombre }}</span>
                                        @if($proyecto->empresa->nit)
                                            <br><small class="text-muted">NIT: {{ $proyecto->empresa->nit }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Sin empresa asignada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($proyecto->actas_count > 0)
                                        <span class="badge bg-success">{{ $proyecto->actas_count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $proyecto->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <a href="{{ route('proyectos.edit', $proyecto) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($proyecto->actas_count == 0)
                                            <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar el proyecto {{ $proyecto->nombre }}?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-danger" disabled title="No se puede eliminar (tiene actas asociadas)">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay proyectos registrados</h5>
                    <p class="text-muted mb-3">Comience agregando el primer proyecto del sistema.</p>
                    <a href="{{ route('proyectos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Proyecto
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection