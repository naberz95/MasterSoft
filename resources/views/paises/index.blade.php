@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">🌍 Gestión de Países</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('paises.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo País
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
        <div class="col-md-4">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Países</h5>
                    <h2 class="text-primary">{{ $paises->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-success">Con Ciudades</h5>
                    <h2 class="text-success">{{ $paises->where('ciudades_count', '>', 0)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Sin Ciudades</h5>
                    <h2 class="text-warning">{{ $paises->where('ciudades_count', 0)->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Países -->
    <div class="card">
        <div class="card-body">
            @if($paises->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-flag"></i> País</th>
                                <th><i class="fas fa-city"></i> Ciudades</th>
                                <th><i class="fas fa-calendar-plus"></i> Creado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paises as $pais)
                            <tr>
                                <td>
                                    <strong>{{ $pais->nombre }}</strong>
                                </td>
                                <td>
                                    @if($pais->ciudades_count > 0)
                                        <span class="badge bg-success">{{ $pais->ciudades_count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $pais->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <a href="{{ route('paises.edit', $pais) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($pais->ciudades_count == 0)
                                            <form action="{{ route('paises.destroy', $pais) }}" method="POST" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar el país {{ $pais->nombre }}?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-danger" disabled title="No se puede eliminar (tiene ciudades asociadas)">
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
                    <i class="fas fa-globe fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay países registrados</h5>
                    <p class="text-muted mb-3">Comience agregando el primer país del sistema.</p>
                    <a href="{{ route('paises.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer País
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection