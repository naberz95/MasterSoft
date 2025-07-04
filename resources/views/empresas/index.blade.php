@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">🏢 Gestión de Empresas</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('empresas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Empresa
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
                    <h5 class="card-title text-primary">Total Empresas</h5>
                    <h2 class="text-primary">{{ $empresas->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-info">Con Logo</h5>
                    <h2 class="text-info">{{ $empresas->whereNotNull('logo_empresa')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-success">Con Proyectos</h5>
                    <h2 class="text-success">{{ $empresas->where('proyectos_count', '>', 0)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Con Actas</h5>
                    <h2 class="text-warning">{{ $empresas->where('actas_count', '>', 0)->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Empresas -->
    <div class="card">
        <div class="card-body">
            @if($empresas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-building"></i> Empresa</th>
                                <th><i class="fas fa-id-card"></i> NIT</th>
                                <th><i class="fas fa-envelope"></i> Contacto</th>
                                <th><i class="fas fa-image"></i> Logo</th>
                                <th><i class="fas fa-project-diagram"></i> Proyectos</th>
                                <th><i class="fas fa-file-alt"></i> Actas</th>
                                <th><i class="fas fa-calendar-plus"></i> Creada</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empresas as $empresa)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($empresa->nombre, 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $empresa->nombre }}</strong>
                                            @if($empresa->direccion)
                                                <br><small class="text-muted">{{ Str::limit($empresa->direccion, 30) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($empresa->nit)
                                        <span class="badge bg-secondary">{{ $empresa->nit }}{{ $empresa->digito_verificacion ? '-' . $empresa->digito_verificacion : '' }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($empresa->email)
                                        <div><i class="fas fa-envelope text-primary"></i> {{ $empresa->email }}</div>
                                    @endif
                                    @if($empresa->telefono)
                                        <div><i class="fas fa-phone text-success"></i> {{ $empresa->telefono }}</div>
                                    @endif
                                    @if(!$empresa->email && !$empresa->telefono)
                                        <span class="text-muted">Sin contacto</span>
                                    @endif
                                </td>
                                <td>
                                    @if($empresa->logo_empresa)
                                        <img src="{{ asset('storage/logos/' . $empresa->logo_empresa) }}" 
                                             alt="Logo {{ $empresa->nombre }}" 
                                             class="img-thumbnail" 
                                             style="max-height: 40px; cursor: pointer;"
                                             onclick="verLogo('{{ asset('storage/logos/' . $empresa->logo_empresa) }}', '{{ $empresa->nombre }}')">
                                    @else
                                        <i class="fas fa-image text-muted" title="Sin logo"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($empresa->proyectos_count > 0)
                                        <span class="badge bg-success">{{ $empresa->proyectos_count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($empresa->actas_count > 0)
                                        <span class="badge bg-info">{{ $empresa->actas_count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $empresa->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($empresa->proyectos_count == 0 && $empresa->actas_count == 0)
                                            <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar la empresa {{ $empresa->nombre }}?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-danger" disabled title="No se puede eliminar (tiene proyectos o actas asociadas)">
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
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay empresas registradas</h5>
                    <p class="text-muted mb-3">Comience agregando la primera empresa del sistema.</p>
                    <a href="{{ route('empresas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Empresa
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para ver logo -->
<div class="modal fade" id="modalVerLogo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLogoTitle">Logo de Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="logo-preview" src="" alt="Logo" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function verLogo(logoUrl, nombreEmpresa) {
    document.getElementById('logo-preview').src = logoUrl;
    document.getElementById('modalLogoTitle').textContent = `Logo de ${nombreEmpresa}`;
    new bootstrap.Modal(document.getElementById('modalVerLogo')).show();
}
</script>
@endpush