<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Acta;
use App\Models\Empresa;
use App\Models\Proyecto;

class SpaManager extends Component
{
    public $currentPage = 'dashboard';
    public $pageTitle = 'Dashboard';
    
    public $actas = [];
    public $stats = [];
    
    protected $listeners = [
        'navigate' => 'navigateTo'
    ];

    public function mount()
    {
        $this->loadCurrentPageData();
        $this->loadStats();
    }

    public function navigateTo($page)
    {
        $this->currentPage = $page;
        
        $titles = [
            'dashboard' => 'Dashboard',
            'actas' => 'Gestión de Actas',
            'empresas' => 'Gestión de Empresas',
            'personas' => 'Gestión de Personas',
            'proyectos' => 'Gestión de Proyectos'
        ];
        
        $this->pageTitle = $titles[$page] ?? 'Dashboard';
        $this->loadCurrentPageData();
    }

    private function loadCurrentPageData()
    {
        if ($this->currentPage === 'actas') {
            $this->loadActasData();
        }
    }

    private function loadActasData()
    {
        $this->actas = Acta::with([
            'tipoActa', 'ciudad', 'empresa', 'proyecto',
            'firmanteEmpresa', 'firmanteGp'
        ])->orderBy('created_at', 'desc')->get();
    }

    private function loadStats()
    {
        $this->stats = [
            'total_actas' => Acta::count(),
            'actas_mes' => Acta::whereMonth('created_at', now()->month)->count(),
            'total_empresas' => Empresa::count(),
            'total_proyectos' => Proyecto::count(),
        ];
    }

    public function render()
    {
        return view('livewire.spa-manager');
    }
}