@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">📋 Lista de Actas</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('actas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Acta
            </a>
        </div>
    </div>

    <!-- Filtro por Proyecto -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row align-items-end g-3">
                <div class="col-md-6">
                    <label for="proyecto_id" class="form-label">Filtrar por Proyecto</label>
                    <select name="proyecto_id" id="proyecto_id" class="form-select" onchange="this.form.submit()">
                        <option value="">— Ver todos los proyectos —</option>
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" {{ request('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    @if(request('proyecto_id'))
                        <a href="{{ route('actas.index') }}" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Número</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Empresa</th>
                            <th>Proyecto</th>
                            <th>Lugar</th>
                            <th>Firmante Empresa</th>
                            <th>Firmante GP</th>
                            <th>Facturable</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($actas as $acta)
                            <tr>
                                <td>
                                    <strong>{{ $acta->numero }}</strong>
                                </td>
                                <td>{{ $acta->tipoActa->nombre ?? '-' }}</td>
                                <td>
                                    {{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : '-' }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $acta->hora_inicio }} - {{ $acta->hora_fin }}
                                    </small>
                                </td>
                                <td>
                                    {{ $acta->empresa->nombre ?? '-' }}
                                    @if($acta->empresa && $acta->empresa->nit)
                                        <br>
                                        <small class="text-muted">NIT: {{ $acta->empresa->nit }}</small>
                                    @endif
                                </td>
                                <td>{{ $acta->proyecto->nombre ?? '-' }}</td>
                                <td>
                                    {{ $acta->lugar }}
                                    @if($acta->ciudad)
                                        <br>
                                        <small class="text-muted">{{ $acta->ciudad->nombre }}</small>
                                    @endif
                                </td>
                                <td>{{ $acta->firmanteEmpresa->nombre ?? '-' }}</td>
                                <td>{{ $acta->firmanteGp->nombre ?? '-' }}</td>
                                <td>
                                    @if($acta->facturable)
                                        <span class="badge bg-success">Sí</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        
                                        <a href="{{ route('actas.edit', $acta) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('actas.exportPdf', $acta) }}" target="_blank" class="btn btn-sm btn-primary" title="PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <form action="{{ route('actas.destroy', $acta) }}" method="POST" 
                                              onsubmit="return confirm('¿Está seguro de eliminar esta acta?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No se encontraron actas</h5>
                                        <p class="text-muted mb-3">
                                            @if(request('proyecto_id'))
                                                No hay actas para el proyecto seleccionado.
                                            @else
                                                No hay actas registradas en el sistema.
                                            @endif
                                        </p>
                                        <a href="{{ route('actas.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Crear Primera Acta
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    @if($actas->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Actas</h5>
                    <h2 class="text-primary">{{ $actas->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Facturables</h5>
                    <h2 class="text-success">{{ $actas->where('facturable', true)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">No Facturables</h5>
                    <h2 class="text-secondary">{{ $actas->where('facturable', false)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Este Mes</h5>
                    <h2 class="text-info">{{ $actas->filter(function($acta) { return \Carbon\Carbon::parse($acta->fecha)->isCurrentMonth(); })->count() }}</h2>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection