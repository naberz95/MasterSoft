<div>
    <h1>🎉 SPA Funcionando!</h1>
    <p>Página actual: <strong>{{ $currentPage }}</strong></p>
    <p>Título: <strong>{{ $pageTitle }}</strong></p>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <button wire:click="navigateTo('dashboard')" class="btn btn-primary">
                Dashboard
            </button>
        </div>
        <div class="col-md-4">
            <button wire:click="navigateTo('actas')" class="btn btn-success">
                Actas
            </button>
        </div>
        <div class="col-md-4">
            <button wire:click="navigateTo('empresas')" class="btn btn-info">
                Empresas
            </button>
        </div>
    </div>
    
    <div class="mt-4">
        @if($currentPage === 'dashboard')
            <div class="alert alert-primary">
                <h4>📊 Dashboard</h4>
                <p>Esta es la página principal del sistema.</p>
            </div>
        @elseif($currentPage === 'actas')
            <div class="alert alert-success">
                <h4>📋 Actas</h4>
                <p>Gestión de actas del sistema.</p>
            </div>
        @elseif($currentPage === 'empresas')
            <div class="alert alert-info">
                <h4>🏢 Empresas</h4>
                <p>Gestión de empresas del sistema.</p>
            </div>
        @endif
    </div>
</div>