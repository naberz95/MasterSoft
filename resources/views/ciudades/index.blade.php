@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">🏙️ Gestión de Ciudades</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('ciudades.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Ciudad
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
                    <h5 class="card-title text-primary">Total Ciudades</h5>
                    <h2 class="text-primary">{{ $ciudades->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-info">Países Únicos</h5>
                    <h2 class="text-info">{{ $ciudades->pluck('pais.nombre')->unique()->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-success">Con Actas</h5>
                    <h2 class="text-success">{{ $ciudades->where('actas_count', '>', 0)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Sin Uso</h5>
                    <h2 class="text-warning">{{ $ciudades->where('actas_count', 0)->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Ciudades -->
    <div class="card">
        <div class="card-body">
            @if($ciudades->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-city"></i> Ciudad</th>
                                <th><i class="fas fa-flag"></i> País</th>
                                <th><i class="fas fa-file-alt"></i> Actas</th>
                                <th><i class="fas fa-calendar-plus"></i> Creada</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ciudades as $ciudad)
                            <tr>
                                <td>
                                    <strong>{{ $ciudad->nombre }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $ciudad->pais->nombre ?? 'Sin país' }}</span>
                                </td>
                                <td>
                                    @if($ciudad->actas_count > 0)
                                        <span class="badge bg-success">{{ $ciudad->actas_count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $ciudad->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        
                                        <a href="{{ route('ciudades.edit', $ciudad) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($ciudad->actas_count == 0)
                                            <form action="{{ route('ciudades.destroy', $ciudad) }}" method="POST" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar la ciudad {{ $ciudad->nombre }}?')" class="d-inline">
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
                    <i class="fas fa-city fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay ciudades registradas</h5>
                    <p class="text-muted mb-3">Comience agregando la primera ciudad del sistema.</p>
                    <a href="{{ route('ciudades.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Ciudad
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection