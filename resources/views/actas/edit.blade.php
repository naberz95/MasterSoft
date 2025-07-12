@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Editar Acta #{{ $acta->numero }}</h1>
        <a href="{{ route('actas.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('actas.update', $acta) }}">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Información General -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Información General</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Tipo de Acta -->
                            <div class="col-md-6 mb-3">
                                <label for="tipo_id" class="form-label">Tipo de Acta <span class="text-danger">*</span></label>
                                <select name="tipo_id" id="tipo_id" class="form-select" required>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ $acta->tipo_id == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Número -->
                            <div class="col-md-6 mb-3">
                                <label for="numero" class="form-label">Número <span class="text-danger">*</span></label>
                                <input type="text" name="numero" id="numero" class="form-control" value="{{ $acta->numero }}" required>
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-6 mb-3">
                                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('Y-m-d') : '' }}" required>
                            </div>

                            <!-- Hora Inicio -->
                            <div class="col-md-6 mb-3">
                                <label for="hora_inicio" class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ $acta->hora_inicio ? \Carbon\Carbon::parse($acta->hora_inicio)->format('H:i') : '' }}" required>
                            </div>

                            <!-- Hora Fin -->
                            <div class="col-md-6 mb-3">
                                <label for="hora_fin" class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                <input type="time" name="hora_fin" id="hora_fin" class="form-control" value="{{ $acta->hora_fin ? \Carbon\Carbon::parse($acta->hora_fin)->format('H:i') : '' }}" required>
                            </div>

                            <!-- Lugar -->
                            <div class="col-md-6 mb-3">
                                <label for="lugar" class="form-label">Lugar <span class="text-danger">*</span></label>
                                <input type="text" name="lugar" id="lugar" class="form-control" value="{{ $acta->lugar }}" required>
                            </div>

                            <!-- Ciudad -->
                            <div class="col-md-6 mb-3">
                                <label for="ciudad_id" class="form-label">Ciudad <span class="text-danger">*</span></label>
                                <select name="ciudad_id" id="ciudad_id" class="form-select" required>
                                    @foreach($ciudades as $ciudad)
                                        <option value="{{ $ciudad->id }}" {{ $acta->ciudad_id == $ciudad->id ? 'selected' : '' }}>
                                            {{ $ciudad->nombre }} / {{ $ciudad->pais->nombre ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Empresa -->
                            <div class="col-md-6 mb-3">
                                <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                                <select name="empresa_id" id="empresa_id" class="form-select" required onchange="updateLogoEmpresa()">
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" 
                                                data-logo="{{ $empresa->logo_empresa ? asset('storage/logos/' . $empresa->logo_empresa) : asset('images/logo-placeholder.png') }}"
                                                {{ $acta->empresa_id == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->nombre }} – NIT: {{ $empresa->nit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Proyecto -->
                            <div class="col-md-6 mb-3">
                                <label for="proyecto_id" class="form-label">Proyecto <span class="text-danger">*</span></label>
                                <select name="proyecto_id" id="proyecto_id" class="form-select" required>
                                    @foreach($proyectos as $proyecto)
                                        <option value="{{ $proyecto->id }}" {{ $acta->proyecto_id == $proyecto->id ? 'selected' : '' }}>
                                            {{ $proyecto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Facturable -->
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facturable" id="facturable" 
                                           value="1" {{ $acta->facturable ? 'checked' : '' }}>
                                    <label class="form-check-label" for="facturable">
                                        Acta Facturable
                                    </label>
                                </div>
                            </div>

                            <!-- Logo Preview -->
                            <div class="col-md-12 mb-3">
                                <div class="text-center">
                                    <img id="logo-empresa" 
                                         src="{{ $acta->empresa && $acta->empresa->logo_empresa 
                                                  ? asset('storage/logos/' . $acta->empresa->logo_empresa) 
                                                  : asset('images/logo-placeholder.png') }}" 
                                         alt="Logo Empresa" 
                                         class="img-thumbnail" 
                                         style="max-height: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido del Acta -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Contenido del Acta</h5>
                    </div>
                    <div class="card-body">
                        <!-- Objetivo -->
                        <div class="mb-3">
                            <label for="objetivo" class="form-label">Objetivo <span class="text-danger">*</span></label>
                            <textarea name="objetivo" id="objetivo" class="form-control editor" rows="4" required>{{ $acta->objetivo }}</textarea>
                        </div>

                        <!-- Agenda -->
                        <div class="mb-3">
                            <label for="agenda" class="form-label">Agenda <span class="text-danger">*</span></label>
                            <textarea name="agenda" id="agenda" class="form-control editor" rows="6" required>{{ $acta->agenda }}</textarea>
                        </div>

                        <!-- Desarrollo -->
                        <div class="mb-3">
                            <label for="desarrollo" class="form-label">Desarrollo <span class="text-danger">*</span></label>
                            <textarea name="desarrollo" id="desarrollo" class="form-control editor" rows="8" required>{{ $acta->desarrollo }}</textarea>
                        </div>

                        <!-- Conclusiones -->
                        <div class="mb-3">
                            <label for="conclusiones" class="form-label">Conclusiones <span class="text-danger">*</span></label>
                            <textarea name="conclusiones" id="conclusiones" class="form-control editor" rows="6" required>{{ $acta->conclusiones }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Firmantes -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Firmantes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Firmante Empresa -->
                            <div class="col-md-6 mb-3">
                                <label for="firmante_empresa_id" class="form-label">Firmante Empresa <span class="text-danger">*</span></label>
                                <select name="firmante_empresa_id" id="firmante_empresa_id" class="form-select" required>
                                    <option value="">Seleccione firmante</option>
                                    @foreach($personas as $persona)
                                        <option value="{{ $persona->id }}" {{ $acta->firmante_empresa_id == $persona->id ? 'selected' : '' }}>
                                            {{ $persona->nombre }} - {{ $persona->cargo }} ({{ $persona->empresa->nombre ?? 'Sin empresa' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Firmante GP -->
                            <div class="col-md-6 mb-3">
                                <label for="firmante_gp_id" class="form-label">Firmante GP <span class="text-danger">*</span></label>
                                <select name="firmante_gp_id" id="firmante_gp_id" class="form-select" required>
                                    <option value="">Seleccione firmante</option>
                                    @foreach($personas as $persona)
                                        <option value="{{ $persona->id }}" {{ $acta->firmante_gp_id == $persona->id ? 'selected' : '' }}>
                                            {{ $persona->nombre }} - {{ $persona->cargo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asistentes -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Asistentes</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="agregarAsistente()">
                            <i class="fas fa-plus"></i> Agregar Asistente
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="asistentes-container">
                            @foreach($acta->actaPersonas as $i => $actaPersona)
                            <div class="row asistente-row mb-3">
                                <input type="hidden" name="asistentes[{{ $i }}][id]" value="{{ $actaPersona->id }}">
                                <div class="col-md-4">
                                    <select name="asistentes[{{ $i }}][persona_id]" class="form-select">
                                        @foreach($personas as $persona)
                                            <option value="{{ $persona->id }}" {{ $actaPersona->persona_id == $persona->id ? 'selected' : '' }}>
                                                {{ $persona->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="asistentes[{{ $i }}][empresa_id]" class="form-select">
                                        @foreach($empresas as $empresa)
                                            <option value="{{ $empresa->id }}" {{ $actaPersona->empresa_id == $empresa->id ? 'selected' : '' }}>
                                                {{ $empresa->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="asistentes[{{ $i }}][asistio]" 
                                               value="1" {{ $actaPersona->asistio ? 'checked' : '' }}>
                                        <label class="form-check-label">Asistió</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarAsistente(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compromisos -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Compromisos</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="agregarCompromiso()">
                            <i class="fas fa-plus"></i> Agregar Compromiso
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="compromisos-container">
                            @foreach($acta->compromisos as $i => $compromiso)
                            <div class="row compromiso-row mb-3">
                                <input type="hidden" name="compromisos[{{ $i }}][id]" value="{{ $compromiso->id }}">
                                <div class="col-md-5">
                                    <textarea name="compromisos[{{ $i }}][descripcion]" class="form-control" rows="2">{{ $compromiso->descripcion }}</textarea>
                                <div class="col-md-3">
                                    <select name="compromisos[{{ $i }}][persona_id]" class="form-select">
                                        <option value="">Seleccione responsable</option>
                                        @foreach($personas as $persona)
                                            <option value="{{ $persona->id }}" 
                                                    {{ ($compromiso->actaPersona->persona_id ?? null) == $persona->id ? 'selected' : '' }}>
                                                {{ $persona->nombre }} ({{ $persona->empresa->nombre ?? 'Sin empresa' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="compromisos[{{ $i }}][fecha]" class="form-control" value="{{ $compromiso->fecha ? \Carbon\Carbon::parse($compromiso->fecha)->format('Y-m-d') : '' }}" >
                                </div>
                                <div class="col-md-1">
                                    <select name="compromisos[{{ $i }}][estado]" class="form-select">
                                        <option value="Pendiente" {{ $compromiso->estado == 'Pendiente' ? 'selected' : '' }}>🕓</option>
                                        <option value="En proceso" {{ $compromiso->estado == 'En proceso' ? 'selected' : '' }}>⚠️</option>
                                        <option value="Cumplido" {{ $compromiso->estado == 'Cumplido' ? 'selected' : '' }}>✅</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCompromiso(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resúmenes -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Resúmenes de Tiempo</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="agregarResumen()">
                            <i class="fas fa-plus"></i> Agregar Resumen
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="resumenes-container">
                            @foreach($acta->resumenes as $i => $resumen)
                            <div class="row resumen-row mb-3">
                                <input type="hidden" name="resumenes[{{ $i }}][id]" value="{{ $resumen->id }}">
                                <div class="col-md-2">
                                    <input type="date" name="resumenes[{{ $i }}][fecha]" class="form-control" value="{{ $resumen->fecha ? \Carbon\Carbon::parse($resumen->fecha)->format('Y-m-d') : '' }}" required>
                                </div>
                                <div class="col-md-5">
                                    <textarea name="resumenes[{{ $i }}][descripcion]" class="form-control" rows="2">{{ $resumen->descripcion }}</textarea>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="resumenes[{{ $i }}][horas]" class="form-control" step="0.25" value="{{ $resumen->horas }}">
                                </div>
                                <div class="col-md-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="resumenes[{{ $i }}][facturable]" 
                                               value="1" {{ $resumen->facturable ? 'checked' : '' }}>
                                        <label class="form-check-label">Fact.</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarResumen(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próxima Reunión -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Próxima Reunión</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="define_proxima_reunion" id="proxima_si" 
                                           value="si" {{ $acta->proxima_reunion ? 'checked' : '' }}>
                                    <label class="form-check-label" for="proxima_si">
                                        Definir próxima reunión
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="define_proxima_reunion" id="proxima_no" 
                                           value="no" {{ !$acta->proxima_reunion ? 'checked' : '' }}>
                                    <label class="form-check-label" for="proxima_no">
                                        No definir próxima reunión
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="proxima_reunion" id="proxima_reunion" class="form-control"
                                    value="{{ $acta->proxima_reunion ? \Carbon\Carbon::parse($acta->proxima_reunion)->format('Y-m-d') : '' }}"
                                    {{ !$acta->proxima_reunion ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('actas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Acta
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let asistenteIndex = {{ $acta->actaPersonas->count() }};
    let compromisoIndex = {{ $acta->compromisos->count() }};
    let resumenIndex = {{ $acta->resumenes->count() }};

    // Funciones para Logo
    function updateLogoEmpresa() {
        const select = document.getElementById("empresa_id");
        const logoImg = document.getElementById("logo-empresa");
        if (!logoImg || !select) return;
        
        const selectedOption = select.options[select.selectedIndex];
        const logoUrl = selectedOption?.getAttribute("data-logo") || "{{ asset('images/logo-placeholder.png') }}";
        logoImg.src = logoUrl;
    }

    // Funciones para Asistentes
    function agregarAsistente() {
        const container = document.getElementById('asistentes-container');
        const div = document.createElement('div');
        div.className = 'row asistente-row mb-3';
        div.innerHTML = `
            <div class="col-md-4">
                <select name="asistentes[${asistenteIndex}][persona_id]" class="form-select">
                    <option value="">Seleccione persona</option>
                    @foreach($personas as $persona)
                        <option value="{{ $persona->id }}">{{ $persona->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="asistentes[${asistenteIndex}][empresa_id]" class="form-select">
                    <option value="">Seleccione empresa</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="asistentes[${asistenteIndex}][asistio]" value="1">
                    <label class="form-check-label">Asistió</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarAsistente(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        asistenteIndex++;
    }

    function eliminarAsistente(button) {
        button.closest('.asistente-row').remove();
    }

    // Funciones para Compromisos
    // Línea 470 - CORREGIR función agregarCompromiso():
    function agregarCompromiso() {
        const container = document.getElementById('compromisos-container');
        const div = document.createElement('div');
        div.className = 'row compromiso-row mb-3';
        div.innerHTML = `
            <div class="col-md-5">
                <textarea name="compromisos[${compromisoIndex}][descripcion]" class="form-control" rows="2" placeholder="Descripción del compromiso"></textarea>
            </div>
            <div class="col-md-3">
                <select name="compromisos[${compromisoIndex}][persona_id]" class="form-select">
                    <option value="">Seleccione responsable</option>
                    @foreach($personas as $persona)
                        <option value="{{ $persona->id }}">{{ $persona->nombre }} ({{ $persona->empresa->nombre ?? 'Sin empresa' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="compromisos[${compromisoIndex}][fecha]" class="form-control">
            </div>
            <div class="col-md-1">
                <select name="compromisos[${compromisoIndex}][estado]" class="form-select">
                    <option value="Pendiente">🕓</option>
                    <option value="En proceso">⚠️</option>
                    <option value="Cumplido">✅</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCompromiso(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        compromisoIndex++;
    }

    function eliminarCompromiso(button) {
        button.closest('.compromiso-row').remove();
    }

    // Funciones para Resúmenes
    function agregarResumen() {
        const container = document.getElementById('resumenes-container');
        const div = document.createElement('div');
        div.className = 'row resumen-row mb-3';
        div.innerHTML = `
            <div class="col-md-2">
                <input type="date" name="resumenes[${resumenIndex}][fecha]" class="form-control">
            </div>
            <div class="col-md-5">
                <textarea name="resumenes[${resumenIndex}][descripcion]" class="form-control" rows="2" placeholder="Descripción del trabajo"></textarea>
            </div>
            <div class="col-md-2">
                <input type="number" name="resumenes[${resumenIndex}][horas]" class="form-control" step="0.25" placeholder="Horas">
            </div>
            <div class="col-md-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="resumenes[${resumenIndex}][facturable]" value="1">
                    <label class="form-check-label">Fact.</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarResumen(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        resumenIndex++;
    }

    function eliminarResumen(button) {
        button.closest('.resumen-row').remove();
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Próxima reunión
        const radioSi = document.getElementById('proxima_si');
        const radioNo = document.getElementById('proxima_no');
        const inputFecha = document.getElementById('proxima_reunion');

        radioSi.addEventListener('change', function() {
            inputFecha.disabled = !this.checked;
        });

        radioNo.addEventListener('change', function() {
            inputFecha.disabled = this.checked;
            if (this.checked) inputFecha.value = '';
        });

        // Inicializar logo
        updateLogoEmpresa();
    });
</script>
@endpush
@endsection