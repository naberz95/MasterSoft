<!-- filepath: c:\laragon\www\ProyectoActas\resources\views\layouts\app.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'MASTERSOFT') }}</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    
    @stack('styles')
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        
        /* SIDEBAR */
        .sidebar {
            background-color: #3c6bd3;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            background-color: #2851b8;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: bold;
            font-size: 1.3rem;
        }
        
        .sidebar-menu {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .sidebar-menu .accordion {
            --bs-accordion-bg: transparent;
            --bs-accordion-border-width: 0;
            --bs-accordion-border-radius: 0;
        }
        
        .accordion-button {
            background-color: transparent !important;
            color: white !important;
            border: none !important;
            box-shadow: none !important;
            padding: 15px 20px;
            font-weight: 500;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }
        
        .accordion-button:not(.collapsed) {
            background-color: rgba(255,255,255,0.1) !important;
            color: white !important;
        }
        
        .accordion-button::after {
            filter: invert(1);
        }
        
        .accordion-body {
            padding: 0;
            background-color: rgba(0,0,0,0.1);
        }
        
        .sidebar-submenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-submenu li a {
            display: block;
            padding: 12px 40px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }
        
        .sidebar-submenu li a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-left: 45px;
        }
        
        .sidebar-submenu li a i {
            width: 20px;
            margin-right: 10px;
        }
        
        /* MAIN CONTENT */
        .main-wrapper {
            margin-left: 250px;
            min-height: 100vh;
        }
        
        .main-content {
            padding: 20px;
            min-height: calc(100vh - 60px);
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
            
            .mobile-toggle {
                display: block !important;
            }
        }
        
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background-color: #3c6bd3;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }
        
        /* USER MENU */
        .user-menu {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #2851b8;
            padding: 15px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-dropdown {
            background-color: transparent;
            border: none;
            color: white;
            width: 100%;
            text-align: left;
            padding: 5px 0;
        }
        
        .user-dropdown:hover {
            background-color: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- BOTÓN MÓVIL -->
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <!-- SIDEBAR -->
        <nav class="sidebar" id="sidebar">
            <!-- HEADER DEL SIDEBAR -->
            <div class="sidebar-header">
                <h4>
                    <i class="fas fa-file-alt me-2"></i>
                    MASTERSOFT
                </h4>
                <small style="color: rgba(255,255,255,0.8);">Sistema de Gestión de Actas</small>
            </div>
            
            <!-- MENÚ PRINCIPAL -->
            <div class="sidebar-menu">
                <div class="accordion" id="sidebarAccordion">
                    <!-- ACTAS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#actasMenu" aria-expanded="true" aria-controls="actasMenu">
                                <i class="fas fa-file-alt me-2"></i>
                                ACTAS
                            </button>
                        </h2>
                        <div id="actasMenu" class="accordion-collapse collapse show" data-bs-parent="#sidebarAccordion">
                            <div class="accordion-body">
                                <ul class="sidebar-submenu">
                                    <li>
                                        <a href="{{ route('actas.index') }}">
                                            <i class="fas fa-list"></i>
                                            Ver Actas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('actas.create') }}">
                                            <i class="fas fa-plus"></i>
                                            Crear Acta
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- GESTIÓN -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#gestionMenu" aria-expanded="false" aria-controls="gestionMenu">
                                <i class="fas fa-cogs me-2"></i>
                                GESTIÓN
                            </button>
                        </h2>
                        <div id="gestionMenu" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                            <div class="accordion-body">
                                <ul class="sidebar-submenu">
                                    <!-- Configuración Básica -->
                                    <li style="padding: 8px 40px; color: rgba(255,255,255,0.6); font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">
                                        Configuración Básica
                                    </li>
                                    <li>
                                        <a href="{{ route('tipos-acta.index') }}">
                                            <i class="fas fa-tags"></i>
                                            Tipos de Acta
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('versions.index') }}">
                                            <i class="fas fa-code-branch"></i>
                                            Versiones
                                        </a>
                                    </li>
                                    
                                    <!-- Ubicaciones -->
                                    <li style="padding: 8px 40px; color: rgba(255,255,255,0.6); font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-top: 10px;">
                                        Ubicaciones
                                    </li>
                                    <li>
                                        <a href="{{ route('paises.index') }}">
                                            <i class="fas fa-globe"></i>
                                            Países
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('ciudades.index') }}">
                                            <i class="fas fa-city"></i>
                                            Ciudades
                                        </a>
                                    </li>
                                    
                                    <!-- Entidades -->
                                    <li style="padding: 8px 40px; color: rgba(255,255,255,0.6); font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-top: 10px;">
                                        Entidades
                                    </li>
                                    <li>
                                        <a href="{{ route('empresas.index') }}">
                                            <i class="fas fa-building"></i>
                                            Empresas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('proyectos.index') }}">
                                            <i class="fas fa-project-diagram"></i>
                                            Proyectos
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('personas.index') }}">
                                            <i class="fas fa-users"></i>
                                            Personas
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- MENÚ DE USUARIO (BOTTOM) -->
            <div class="user-menu">
                @auth
                    <div class="dropdown">
                        <button class="user-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <div class="user-info">
                                <i class="fas fa-user"></i>
                                <div>
                                    <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
                                    <small style="opacity: 0.8;">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <div class="user-info">
                        <i class="fas fa-user-slash"></i>
                        <div>
                            <a href="{{ route('login') }}" class="text-white text-decoration-none">Iniciar Sesión</a>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="main-wrapper">
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Toggle sidebar en móvil
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }
        
        // Cerrar sidebar al hacer clic fuera en móvil
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Highlight active menu
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.href;
            const menuLinks = document.querySelectorAll('.sidebar-submenu a');
            
            menuLinks.forEach(link => {
                if (currentUrl.includes(link.getAttribute('href'))) {
                    link.style.backgroundColor = 'rgba(255,255,255,0.2)';
                    link.style.borderLeft = '3px solid white';
                    
                    // Abrir acordeón padre
                    const accordionCollapse = link.closest('.accordion-collapse');
                    if (accordionCollapse) {
                        accordionCollapse.classList.add('show');
                        const button = document.querySelector(`[data-bs-target="#${accordionCollapse.id}"]`);
                        if (button) {
                            button.classList.remove('collapsed');
                            button.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>