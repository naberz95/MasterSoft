<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta #{{ $acta->numero }}</title>

<style>
    body { 
        font-family: Arial, sans-serif; 
        font-size: 12px; 
        margin: 20px; 
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        table-layout: fixed; /* ✅ Importante para evitar desbordes */
    }

    th, td { 
        border: 1px solid #99bcd6; /* ✅ Borde más suave y profesional */
        padding: 6px; 
        vertical-align: top; 
        word-wrap: break-word;
        overflow: hidden;
    }

    /* ✅ TABLA DE ENCABEZADO */
    .header-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 20px;
        table-layout: fixed;
    }

    .header-table td:nth-child(1),
    .header-table td:nth-child(2),
    .header-table td:nth-child(3) {
        width: 33.33%;
    }

    .header-table td { 
        text-align: center; 
        border: 1px solid #99bcd6;
        padding: 8px;
        vertical-align: middle;
        font-size: 11px;
        font-weight: bold;
        overflow: hidden;
    }

    .header-table tr:first-child td {
        height: 80px;
    }

    .header-table tr:last-child td {
        height: 30px;
        font-size: 10px;
    }

    .header-table .logo { 
        max-height: 50px;
        max-width: 90%;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    .logo { 
        max-height: 60px; 
        max-width: 100%;
    }

    h2 { 
        margin: 0; 
        font-size: 18px;
        font-weight: bold;
    }

    .section-title {
        background-color: #f0f0f0;
        font-weight: bold;
        padding: 5px;
        margin-top: 20px;
        margin-bottom: 5px;
        font-size: 14px;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    .ck-content img {
        max-width: 100%;
        height: auto;
        max-height: 250px;
        display: block;
        margin: 10px auto;
    }

    /* ✅ ESTILOS PARA LA TABLA DE ACTA */
    table.acta {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        table-layout: fixed;
    }

    table.acta th,
    table.acta td {
        border: 1px solid #000;
        padding: 4px 6px;
        vertical-align: middle;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    table.acta th {
        background-color: #e6e6e6;
        font-weight: bold;
        font-size: 10px;
        text-align: center;
    }

    /* ✅ CHECKBOXES PARA FACTURABLE */
.checkbox {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 1px solid #000;
    text-align: center;
    line-height: 16px;
    font-weight: bold;
    font-size: 12px;
    margin: 0 auto 2px auto;
}





    /* ✅ NÚMERO EN ESQUINA SUPERIOR DERECHA */
    .numero-acta {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .numero-acta span {
        color: #007bff;
    }

    /* ✅ OTRAS TABLAS (opcional) */
    table:not(.acta):not(.header-table) th {
        background-color: #f0f0f0;
        font-weight: bold;
        text-align: left;
        font-size: 12px;
    }
    .facturable-label {
    min-width: 80px;
    white-space: normal;
}

</style>



</head>
<body>

<!-- ENCABEZADO -->
<table class="header-table">
    <!-- ✅ PRIMERA FILA: LOGOS Y TÍTULO -->
    <tr>
        <td>
            <img src="{{ public_path('prueba1.png') }}" class="logo" alt="Logo Gestión y Proyectos">
        </td>
        <td>
            <h2>ACTA</h2>
        </td>
        <td>
            @if($acta->empresa && $acta->empresa->logo_empresa)
                <img src="{{ storage_path('app/public/logos/' . $acta->empresa->logo_empresa) }}" class="logo" alt="Logo Empresa">
            @else
                LOGO EMPRESA
            @endif
        </td>
    </tr>
    <!-- ✅ SEGUNDA FILA: CÓDIGO, VIGENCIA Y VERSIÓN -->
    <tr>
        <td>
            CÓDIGO: {{ $config['codigo'] ?? 'FRMGP010' }}
        </td>
        <td>
            VIGENTE DESDE: {{ $config['vigencia'] ?? '7/Dic/2023' }}
        </td>
        <td>
            VERSIÓN: {{ $config['version'] ?? '6' }}
        </td>
    </tr>
</table>

<!-- DATOS BÁSICOS -->
<div class="section-title">Datos Básicos</div>
<div style="text-align: right; font-weight: bold; font-size: 13px; margin-bottom: 5px;">
    NÚMERO: <span style="color: #007bff;">{{ $acta->numero }}</span>
</div>
<table class="acta">
<tr>
    <th>TIPO</th>
    <td style="width: 15%;">{{ $acta->tipoActa->nombre ?? '-' }}</td>

    <th>FECHA</th>
    <td colspan="3" style="width: 32%;">{{ \Carbon\Carbon::parse($acta->fecha)->translatedFormat('l, d \d\e F \d\e Y') }}</td>

    <th>INICIO</th>
    <td style="width: 10%;">{{ \Carbon\Carbon::parse($acta->hora_inicio)->format('H:i') }}</td>

    <th>FINAL</th>
    <td style="width: 10%;">{{ \Carbon\Carbon::parse($acta->hora_fin)->format('H:i') }}</td>
</tr>


    <tr>
        <th>LUGAR</th>
        <td colspan="4">{{ $acta->lugar }}</td>

        <th colspan="2">CIUDAD/PAÍS</th>
        <td colspan="3">{{ $acta->ciudad->nombre ?? '' }} / {{ $acta->ciudad->pais->nombre ?? '' }}</td>
    </tr>

    <tr> 
        <th style="width: 10%;">CLIENTE NIT</th>
        <td style="width: 10%;">{{ $acta->empresa->nit ?? '' }}</td>

        <td colspan="4" style="width: 40%;">{{ $acta->empresa->nombre ?? '' }}</td>

        <th colspan="2" style="width: 20%;">FACTURABLE</th>

        <td style="width: 10%; text-align: center; vertical-align: middle; font-weight: {{ $acta->facturable ? 'bold' : 'normal' }};">
            {{ $acta->facturable ? 'X SI' : 'SI' }}
        </td>

        <td style="width: 10%; text-align: center; vertical-align: middle; font-weight: {{ !$acta->facturable ? 'bold' : 'normal' }};">
            {{ !$acta->facturable ? 'X NO' : 'NO' }}
        </td>
    </tr>


    <tr>
        <th>PROYECTO</th>
        <td colspan="9">{{ $acta->proyecto->nombre ?? '' }}</td>
    </tr>
</table>

<!-- ASISTENTES -->
<div class="section-title">ASISTENTES / INVITADOS</div>
<table>
    <thead>
        <tr style="background-color: #d0e9f7;">
            <th>Inicial</th>
            <th>Nombre</th>
            <th>Cargo / Rol</th>
            <th>Empresa</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($acta->actaPersonas as $actaPersona)
            <tr>
                <td>{{ $actaPersona->persona->iniciales ?? '-' }}</td>
                <td>{{ $actaPersona->persona->nombre }}</td>
                <td>{{ $actaPersona->persona->cargo ?? '-' }}</td>
                <td>{{ $actaPersona->empresa->nombre ?? '-' }}</td>
                <td>{{ $actaPersona->asistio ? 'Asiste' : 'No Asiste' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- OBJETIVO / AGENDA -->
<div class="section-title">OBJETIVO / ASUNTO / AGENDA</div>

<table class="table table-bordered" style="width:100%; border-collapse: collapse; border: 1px solid #000;">

    <tr>
        <td class="ck-content">
            <strong>1. Desarrollo de la reunión:<strong><br>
            {!! $acta->objetivo !!}
        </td>
    </tr>
    <tr>
        <td class="ck-content">
            <strong>2. Programación de actividades:<strong><br>
            {!! $acta->agenda ?? 'N/A' !!}
        </td>
    </tr>
    <tr>
        <td>
            <strong>3. Próxima reunión:</strong><br>
            {{ $acta->proxima_reunion ? \Carbon\Carbon::parse($acta->proxima_reunion)->format('Y-m-d') : 'N/A' }}
        </td>
    </tr>
</table>


<!-- DESARROLLO Y CONCLUSIONES -->
<div class="section-title">DESARROLLO Y CONCLUSIONES</div>

<table class="table table-bordered" style="width:100%; border-collapse: collapse; border: 1px solid #000;">
    <tr>
        <td class="ck-content">
            <strong>1. Desarrollo:</strong><br>
            {!! $acta->desarrollo !!}
        </td>
    </tr>
    <tr>
        <td class="ck-content">
            <strong>2. Conclusiones:</strong><br>
            {!! $acta->conclusiones !!}
        </td>
    </tr>
</table>


<div class="section-title">COMPROMISOS</div>
<table>
    <thead style="background-color: #d0e9f7;">
        <tr>
            <th style="width: 5%;">#</th>
            <th>Descripción</th>
            <th style="width: 20%;">Resp.</th>
            <th style="width: 15%;">Fecha</th>
            <th style="width: 15%;">Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($acta->compromisos as $index => $comp)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $comp->descripcion }}</td>
                <td>{{ $comp->actaPersona->persona->nombre ?? '—' }}</td> {{-- ✅ CORRECCIÓN --}}
                <td>{{ $comp->fecha ? \Carbon\Carbon::parse($comp->fecha)->format('Y-m-d') : '' }}</td>
                <td>{{ $comp->estado }}</td>
            </tr>
        @empty
            <tr>
                <td>1</td>
                <td colspan="4">No se definen compromisos al ser un acta de cierre del servicio</td>
            </tr>
        @endforelse
    </tbody>
</table>


<div class="section-title">Firmas Representativas</div>
<table style="margin-top: 30px; text-align: center; width: 100%;">
    <tr>
        <!-- Firma Gestión y Proyectos -->
        <td style="width: 50%;">
            <div style="color: gray;">Firma</div>
            @if ($acta->firmanteGp && $acta->firmanteGp->firma_path && file_exists(public_path('storage/' . $acta->firmanteGp->firma_path)))
                <img src="{{ public_path('storage/' . $acta->firmanteGp->firma_path) }}" style="max-height: 70px;"><br>
            @endif
            {{ $acta->firmanteGp->nombre ?? '' }}<br>
            TP {{ $acta->firmanteGp->tarjeta_profesional ?? 'N/A' }}
            @if ($acta->firmanteGp->fecha_tarjeta)
                del {{ \Carbon\Carbon::parse($acta->firmanteGp->fecha_tarjeta)->format('d/M/Y') }}
            @endif
            <br>
            <strong>{{ $acta->firmanteGp->empresa->nombre ?? '' }}</strong>
        </td>

        <!-- Firma Empresa -->
        <td style="width: 50%;">
            <div style="color: gray;">Firma</div>
            @if ($acta->firmanteEmpresa && $acta->firmanteEmpresa->firma_path && file_exists(public_path('storage/' . $acta->firmanteEmpresa->firma_path)))
                <img src="{{ public_path('storage/' . $acta->firmanteEmpresa->firma_path) }}" style="max-height: 70px;"><br>
            @endif
            {{ $acta->firmanteEmpresa->nombre ?? '' }}<br>
            CC. {{ $acta->firmanteEmpresa->cedula ?? '' }}
            @if ($acta->firmanteEmpresa->fecha_expedicion_cedula)
                de {{ $acta->firmanteEmpresa->lugar_expedicion_cedula ?? '' }}
            @endif
            <br>
            <strong>{{ $acta->firmanteEmpresa->empresa->nombre ?? '' }}</strong>
        </td>
    </tr>
</table>

<!--Resumen Cronologico-->
<div class="section-title">Resumen cronológico</div>
<table>
    <thead>
        <tr style="background-color: #d0e9f7; text-align: center;">
            <th style="width: 15%;">Fecha</th>
            <th>Descripción</th>
            <th style="width: 10%;">Hrs</th>
            <th style="width: 10%;">Fac.</th>
            <th style="width: 15%;">Acta N°</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($resumenesProyecto) && $resumenesProyecto->count() > 0)
            @foreach($resumenesProyecto as $r)
                <tr>
                    <td style="text-align: center;">
                        {{ $r->fecha ? \Carbon\Carbon::parse($r->fecha)->format('d/M/y') : '-' }}
                    </td>
                    <td>{{ $r->descripcion }}</td>
                    <td style="text-align: center;">{{ $r->horas ?? 0 }}</td>
                    <td style="text-align: center;">{{ $r->facturable ? 'Sí' : 'No' }}</td>
                    <td style="text-align: center;">
                        {{ $r->acta->numero ?? '-' }}
                        @if($r->acta->id == $acta->id)
                            <strong>(Actual)</strong>
                        @endif
                    </td>
                </tr>
            @endforeach
            
        @else
            <tr>
                <td colspan="5" style="text-align: center;">
                    Sin registros de resumen cronológico para este proyecto
                </td>
            </tr>
        @endif
    </tbody>
</table>

<!-- ✅ SECCIÓN 1: CONTROL DE VERSIONES DEL DOCUMENTO -->
<div class="section-title">Control de versiones del documento</div>
<table>
    <thead>
        <tr style="background-color: #d0e9f7; text-align: center;">
            <th style="width: 10%;">Versión</th>
            <th style="width: 20%;">Fecha de creación</th>
            <th style="width: 70%;">Descripción del cambio</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($todasLasVersiones) && $todasLasVersiones->count() > 0)
            {{-- ✅ MOSTRAR TODAS LAS VERSIONES SIN RESALTADO --}}
            @foreach($todasLasVersiones as $version)
                <tr>
                    <td style="text-align: center;">
                        <strong>{{ $version->version }}</strong>
                        {{-- ✅ QUITADO: texto "(Actual)" --}}
                    </td>
                    <td style="text-align: center;">
                        {{ $version->fecha_creacion->format('d/M/Y') }}
                    </td>
                    <td>
                        {{ $version->descripcion_cambio }}
                        @if($version->version == 1)
                            <br><small style="color: #6c757d;"><em>Versión inicial del documento</em></small>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            {{-- ✅ VERSIONES POR DEFECTO SI NO HAY SISTEMA DE VERSIONES --}}
            <tr>
                <td style="text-align: center;"><strong>1</strong></td>
                <td style="text-align: center;">1/Feb/2014</td>
                <td>Versión inicial</td>
            </tr>
            <tr>
                <td style="text-align: center;"><strong>2</strong></td>
                <td style="text-align: center;">5/Feb/2015</td>
                <td>Actualización de estilo</td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <strong>6</strong>
                    {{-- ✅ QUITADO: texto "(Actual)" y color de fondo --}}
                </td>
                <td style="text-align: center;">7/Dic/2023</td>
                <td>Versión actual del formato</td>
            </tr>
        @endif
    </tbody>
</table>

<!-- ✅ SECCIÓN 2: CONTROL DE REVISIÓN Y APROBACIÓN DEL DOCUMENTO -->
<div class="section-title">Control de revisión y aprobación del documento</div>
<table>
    <thead>
        <tr style="background-color: #d0e9f7; text-align: center;">
            <th style="width: 25%;">Revisado por</th>
            <th style="width: 25%;">Aprobado por</th>
            <th style="width: 25%;">Estado / Firma</th>
            <th style="width: 25%;">Fecha</th>
        </tr>
    </thead>
    <tbody>
        @if($versionMasReciente && $versionMasReciente->revisado_por)
            {{-- ✅ DATOS DINÁMICOS DE LA VERSIÓN MÁS RECIENTE --}}
            <tr style="text-align: center;">
                <td>
                    {{ $versionMasReciente->revisado_por }}
                    @if($versionMasReciente->fecha_revision)
                        <br><b>{{ $versionMasReciente->fecha_revision->format('d/m/Y') }}</b>
                    @endif
                </td>
                <td>
                    {{ $versionMasReciente->aprobado_por ?? 'Pendiente' }}
                    @if($versionMasReciente->fecha_aprobado)
                        <br><b>{{ $versionMasReciente->fecha_aprobado->format('d/m/Y') }}</b>
                    @endif
                </td>
                <td>
                    @php
                        $estadoBadge = '';
                        switch($versionMasReciente->estado) {
                            case 'Pendiente':
                                $estadoBadge = 'Pendiente';
                                break;
                            case 'Revisado':
                                $estadoBadge = 'Revisado';
                                break;
                            case 'Aprobado':
                                $estadoBadge = 'Aprobado';
                                break;
                            default:
                                $estadoBadge = $versionMasReciente->estado;
                        }
                    @endphp
                    {{ $estadoBadge }}
                </td>
                <td>
                    @if($versionMasReciente->fecha_aprobacion_documento)
                        {{ $versionMasReciente->fecha_aprobacion_documento->format('d/M/Y') }}
                    @elseif($versionMasReciente->fecha_aprobado)
                        {{ $versionMasReciente->fecha_aprobado->format('d/M/Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @else
            {{-- ✅ DATOS ESTÁTICOS POR DEFECTO --}}
            <tr style="text-align: center;">
                <td>
                    Mauricio Mendoza
                    <br><b>5/07/2025</b>
                </td>
                <td>
                    Martha L. Villán M.
                    <br><b>5/07/2025</b>
                </td>
                <td>Aprobado</td>
                <td>7/Dic/23</td>
            </tr>
        @endif
    </tbody>
</table>

</body>
</html>