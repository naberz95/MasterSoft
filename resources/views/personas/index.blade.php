@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">👥 Gestión de Personas</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('personas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Persona
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
                    <h5 class="card-title text-primary">Total Personas</h5>
                    <h2 class="text-primary">{{ $personas->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-info">Con Firma Digital</h5>
                    <h2 class="text-info">{{ $personas->whereNotNull('firma_path')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-success">Empresas Únicas</h5>
                    <h2 class="text-success">{{ $personas->pluck('empresa.nombre')->unique()->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Sin Empresa</h5>
                    <h2 class="text-warning">{{ $personas->whereNull('empresa_id')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Personas -->
    <div class="card">
        <div class="card-body">
            @if($personas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-user"></i> Persona</th>
                                <th><i class="fas fa-id-badge"></i> Iniciales</th>
                                <th><i class="fas fa-briefcase"></i> Cargo</th>
                                <th><i class="fas fa-building"></i> Empresa</th>
                                <th><i class="fas fa-signature"></i> Firma</th>
                                <th><i class="fas fa-calendar-plus"></i> Registrado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personas as $persona)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($persona->nombre, 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $persona->nombre }}</strong>
                                            @if($persona->cedula)
                                                <br><small class="text-muted">CC: {{ $persona->cedula }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($persona->iniciales)
                                        <span class="badge bg-secondary">{{ $persona->iniciales }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $persona->cargo ?? 'N/A' }}</td>
                                <td>
                                    @if($persona->empresa)
                                        <span class="badge bg-info">{{ $persona->empresa->nombre }}</span>
                                    @else
                                        <span class="text-muted">Sin empresa</span>
                                    @endif
                                </td>
                                <td>
                                    @if($persona->firma_path)
                                        <i class="fas fa-check-circle text-success" title="Firma registrada"></i>
                                        <button class="btn btn-sm btn-outline-info" onclick="verFirma('{{ asset('storage/' . $persona->firma_path) }}', '{{ $persona->nombre }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <i class="fas fa-times-circle text-danger" title="Sin firma"></i>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $persona->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <a href="{{ route('personas.edit', $persona) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('personas.destroy', $persona) }}" method="POST" 
                                              onsubmit="return confirm('¿Está seguro de eliminar a {{ $persona->nombre }}?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay personas registradas</h5>
                    <p class="text-muted mb-3">Comience agregando la primera persona del sistema.</p>
                    <a href="{{ route('personas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Persona
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para ver firma -->
<div class="modal fade" id="modalVerFirma" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFirmaTitle">Firma Digital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="firma-preview" src="" alt="Firma" class="img-fluid border" style="max-height: 300px;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function verFirma(firmaUrl, nombrePersona) {
    document.getElementById('firma-preview').src = firmaUrl;
    document.getElementById('modalFirmaTitle').textContent = `Firma de ${nombrePersona}`;
    new bootstrap.Modal(document.getElementById('modalVerFirma')).show();
}
</script>
@endpush