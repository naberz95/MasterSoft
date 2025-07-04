@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">📋 Gestión de Tipos de Acta</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('tipos-acta.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Tipo
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
                    <h5 class="card-title text-primary">Total Tipos</h5>
                    <h2 class="text-primary">{{ $tipos->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-success">En Uso</h5>
                    <h2 class="text-success">{{ $tipos->where('actas_count', '>', 0)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Sin Uso</h5>
                    <h2 class="text-warning">{{ $tipos->where('actas_count', 0)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-info">Total Actas</h5>
                    <h2 class="text-info">{{ $tipos->sum('actas_count') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Tipos -->
    <div class="card">
        <div class="card-body">
            @if($tipos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-file-alt"></i> Tipo de Acta</th>
                                <th><i class="fas fa-chart-bar"></i> Actas Creadas</th>
                                <th><i class="fas fa-calendar-plus"></i> Registrado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tipos as $tipo)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($tipo->nombre, 0, 2)) }}
                                        </div>
                                        <strong>{{ $tipo->nombre }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @if($tipo->actas_count > 0)
                                        <span class="badge bg-success">{{ $tipo->actas_count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $tipo->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <a href="{{ route('tipos-acta.edit', $tipo) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($tipo->actas_count == 0)
                                            <form action="{{ route('tipos-acta.destroy', $tipo) }}" method="POST" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar el tipo {{ $tipo->nombre }}?')" class="d-inline">
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
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay tipos de acta registrados</h5>
                    <p class="text-muted mb-3">Comience agregando el primer tipo de acta del sistema.</p>
                    <a href="{{ route('tipos-acta.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Tipo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection