<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\TipoActa;
use App\Models\Ciudad;
use App\Models\Empresa;
use App\Models\Proyecto;
use App\Models\Persona;
use App\Models\Compromiso;
use App\Models\ActaPersona;
use App\Models\Resumen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ConsecutivoActa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\version;

Carbon::setLocale('es');

class ActaController extends Controller
{
    public function index(Request $request)
    {
        $query = Acta::with([
            'tipoActa', 
            'ciudad', 
            'empresa', 
            'proyecto', 
            'firmanteEmpresa', 
            'firmanteGp'
        ]);

        // Filtro por proyecto
        if ($request->filled('proyecto_id')) {
            $query->where('proyecto_id', $request->proyecto_id);
        }

        $actas = $query->orderByDesc('fecha')->get();
        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('actas.index', compact('actas', 'proyectos'));
    }

    public function create()
    {
        $tipos = TipoActa::all();
        $ciudades = Ciudad::with('pais')->get();
        $empresas = Empresa::all();
        $proyectos = Proyecto::all();
        $personas = Persona::with('empresa')->get(); // ✅ CORRECCIÓN 1: Cargar relación empresa

        // ✅ CORRECCIÓN 2: Cargar compromisos con la relación correcta
        $ultimaActa = Acta::with(['compromisos.actaPersona.persona'])
                         ->orderByDesc('fecha')
                         ->orderByDesc('id')
                         ->first();

        $compromisosAnteriores = $ultimaActa?->compromisos ?? collect();

        // Empresa por defecto para mostrar logo
        $empresaPorDefecto = $empresas->first() ?? null;

        return view('actas.create', compact(
            'tipos',
            'ciudades',
            'empresas',
            'proyectos',
            'personas',
            'empresaPorDefecto',
            'ultimaActa',
            'compromisosAnteriores'
        ));
    }

    public function ultimaActaPorProyecto($proyectoId)
    {
        $acta = Acta::with(['compromisos.actaPersona.persona'])
            ->where('proyecto_id', $proyectoId)
            ->orderByDesc('fecha')
            ->orderByDesc('hora_inicio')
            ->first();

        if (!$acta) {
            return response()->json(['compromisos' => []]);
        }

        return response()->json([
            'acta' => [
                'id' => $acta->id,
                'numero' => $acta->numero
            ],
            'compromisos' => $acta->compromisos->map(function ($c) {
                return [
                    'id' => $c->id,
                    'descripcion' => $c->descripcion,
                    'responsable' => $c->actaPersona->persona->nombre ?? 'Sin asignar',
                    'fecha' => $c->fecha,
                    'estado' => $c->estado
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_id' => 'required|exists:tipos_acta,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'lugar' => 'required',
            'ciudad_id' => 'required|exists:ciudades,id',
            'empresa_id' => 'required|exists:empresas,id',
            'proyecto_id' => 'required|exists:proyectos,id',
            'objetivo' => 'required',
            'agenda' => 'required',
            'desarrollo' => 'required',
            'conclusiones' => 'required',
            'firmante_empresa_id' => 'required|exists:personas,id',
            'firmante_gp_id' => 'required|exists:personas,id',
            'facturable' => 'boolean'
        ]);

        // Generar número automático
        $numero = ConsecutivoActa::generarNumeroActa(Carbon::parse($validated['fecha']));
        $validated['numero'] = $numero;

        // Manejar próxima reunión
        if ($request->input('define_proxima_reunion') === 'si' && $request->filled('proxima_reunion')) {
            $validated['proxima_reunion'] = $request->input('proxima_reunion');
        }

        $acta = Acta::create($validated);

        // ✅ Guardar asistentes usando ActaPersona
        if ($request->has('asistentes')) {
            foreach ($request->input('asistentes') as $asistente) {
                if (!empty($asistente['persona_id'])) {
                    ActaPersona::create([
                        'acta_id' => $acta->id,
                        'persona_id' => $asistente['persona_id'],
                        'empresa_id' => $asistente['empresa_id'] ?? null,
                        'asistio' => isset($asistente['asistio']) ? 1 : 0,
                    ]);
                }
            }
        }

        // ✅ COMPROMISOS MEJORADOS - CON EMPRESA DE LA PERSONA
        if ($request->has('compromisos')) {
            foreach ($request->compromisos as $compromiso) {
                if (!empty($compromiso['descripcion']) && !empty($compromiso['persona_id'])) {
                    
                    // 🔍 Buscar o crear ActaPersona para obtener acta_persona_id
                    $actaPersona = ActaPersona::where('acta_id', $acta->id)
                                            ->where('persona_id', $compromiso['persona_id'])
                                            ->first();
                    
                    // Si no existe la relación ActaPersona, la creamos
                    if (!$actaPersona) {
                        // ✅ CORRECCIÓN 3: Obtener la empresa de la persona
                        $persona = Persona::with('empresa')->find($compromiso['persona_id']);
                        
                        $actaPersona = ActaPersona::create([
                            'acta_id' => $acta->id,
                            'persona_id' => $compromiso['persona_id'],
                            'empresa_id' => $persona->empresa_id ?? null, // ✅ Empresa correcta
                            'asistio' => true, // Asumimos que asistió si tiene compromisos
                        ]);
                    }
                    
                    // ✅ Crear compromiso con acta_persona_id
                    Compromiso::create([
                        'acta_id' => $acta->id,
                        'descripcion' => $compromiso['descripcion'],
                        'acta_persona_id' => $actaPersona->id,
                        'fecha' => $compromiso['fecha'] ?? null,
                        'estado' => $compromiso['estado'] ?? 'Pendiente',
                    ]);
                }
            }
        }

        // ✅ Crear resúmenes
        if ($request->has('resumen')) {
            foreach ($request->resumen as $r) {
                if (!empty($r['descripcion'])) {
                    Resumen::create([
                        'acta_id' => $acta->id,
                        'fecha' => $r['fecha'] ?? null,
                        'descripcion' => $r['descripcion'],
                        'horas' => $r['horas'] ?? 0,
                        'facturable' => isset($r['facturable']) ? (bool) $r['facturable'] : false,
                    ]);
                }
            }
        }

        return redirect()->route('actas.index')->with('success', 'Acta creada exitosamente.');
    }

    public function edit(Acta $acta)
    {
        // ✅ CORRECCIÓN 4: Cargar relaciones correctas
        $acta->load([
            'compromisos.actaPersona.persona', // ✅ Cambio: usar actaPersona en lugar de persona directa
            'tipoActa',
            'ciudad',
            'empresa',
            'proyecto',
            'actaPersonas.persona',
            'actaPersonas.empresa',
            'firmanteEmpresa',
            'firmanteGp',
            'resumenes'
        ]);

        $tipos = TipoActa::all();
        $ciudades = Ciudad::all();
        $empresas = Empresa::all();
        $proyectos = Proyecto::all();
        $personas = Persona::all();

        return view('actas.edit', compact('acta', 'tipos', 'ciudades', 'empresas', 'proyectos', 'personas'));
    }

    public function update(Request $request, Acta $acta)
    {
        $validated = $request->validate([
            'tipo_id' => 'required|exists:tipos_acta,id',
            'numero' => 'required|unique:actas,numero,' . $acta->id,
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'lugar' => 'required',
            'ciudad_id' => 'required|exists:ciudades,id',
            'empresa_id' => 'required|exists:empresas,id',
            'proyecto_id' => 'required|exists:proyectos,id',
            'objetivo' => 'required',
            'agenda' => 'required',
            'desarrollo' => 'required',
            'conclusiones' => 'required',
            'firmante_empresa_id' => 'required|exists:personas,id',
            'firmante_gp_id' => 'required|exists:personas,id',
            'facturable' => 'boolean'
        ]);

        // Manejar próxima reunión
        if ($request->input('define_proxima_reunion') === 'si' && $request->filled('proxima_reunion')) {
            $validated['proxima_reunion'] = $request->input('proxima_reunion');
        } else {
            $validated['proxima_reunion'] = null;
        }

        // ✅ Actualizar datos básicos del acta
        $acta->update($validated);

        // ✅ ACTUALIZAR ASISTENTES
        if ($request->has('asistentes')) {
            // Eliminar asistentes existentes
            $acta->actaPersonas()->delete();
            
            // Crear nuevos asistentes
            foreach ($request->input('asistentes') as $asistente) {
                if (!empty($asistente['persona_id'])) {
                    ActaPersona::create([
                        'acta_id' => $acta->id,
                        'persona_id' => $asistente['persona_id'],
                        'empresa_id' => $asistente['empresa_id'] ?? null,
                        'asistio' => isset($asistente['asistio']) ? 1 : 0,
                    ]);
                }
            }
        }

        // ✅ ACTUALIZAR COMPROMISOS
        if ($request->has('compromisos')) {
            // Eliminar compromisos existentes
            $acta->compromisos()->delete();
            
            // Crear nuevos compromisos
            foreach ($request->compromisos as $compromiso) {
                if (!empty($compromiso['descripcion']) && !empty($compromiso['persona_id'])) {
                    
                    // 🔍 Buscar o crear ActaPersona para obtener acta_persona_id
                    $actaPersona = ActaPersona::where('acta_id', $acta->id)
                                            ->where('persona_id', $compromiso['persona_id'])
                                            ->first();
                    
                    // Si no existe la relación ActaPersona, la creamos
                    if (!$actaPersona) {
                        $persona = Persona::with('empresa')->find($compromiso['persona_id']);
                        
                        $actaPersona = ActaPersona::create([
                            'acta_id' => $acta->id,
                            'persona_id' => $compromiso['persona_id'],
                            'empresa_id' => $persona->empresa_id ?? null,
                            'asistio' => true, // Asumimos que asistió si tiene compromisos
                        ]);
                    }
                    
                    // ✅ Crear compromiso con acta_persona_id
                    Compromiso::create([
                        'acta_id' => $acta->id,
                        'descripcion' => $compromiso['descripcion'],
                        'acta_persona_id' => $actaPersona->id,
                        'fecha' => $compromiso['fecha'] ?? null,
                        'estado' => $compromiso['estado'] ?? 'Pendiente',
                    ]);
                }
            }
        }

        // ✅ ACTUALIZAR RESÚMENES
        if ($request->has('resumen')) {
            // Eliminar resúmenes existentes
            $acta->resumenes()->delete();
            
            // Crear nuevos resúmenes
            foreach ($request->resumen as $r) {
                if (!empty($r['descripcion'])) {
                    Resumen::create([
                        'acta_id' => $acta->id,
                        'fecha' => $r['fecha'] ?? null,
                        'descripcion' => $r['descripcion'],
                        'horas' => $r['horas'] ?? 0,
                        'facturable' => isset($r['facturable']) ? (bool) $r['facturable'] : false,
                    ]);
                }
            }
        }

        return redirect()->route('actas.index')->with('success', 'Acta actualizada exitosamente.');
    }

    public function destroy(Acta $acta)
    {
        $acta->delete();
        return redirect()->route('actas.index')->with('success', 'Acta eliminada.');
    }

    public function show(Acta $acta)
    {
        // ✅ CORRECCIÓN 5: Cargar relaciones correctas
        $acta->load([
            'tipoActa',
            'ciudad.pais',
            'empresa',
            'proyecto',
            'firmanteEmpresa',
            'firmanteGp',
            'actaPersonas.persona',
            'actaPersonas.empresa',
            'compromisos.actaPersona.persona', // ✅ Cambio: usar actaPersona
            'resumenes'
        ]);

        return view('actas.show', compact('acta'));
    }

  
    
    public function exportPdf(Acta $acta)
    {
        $acta->load([
            'tipoActa',
            'ciudad.pais',
            'empresa',
            'proyecto',
            'firmanteEmpresa',
            'firmanteGp',
            'actaPersonas.persona',
            'actaPersonas.empresa',
            'compromisos.actaPersona.persona',
            'resumenes',
            'version' // ✅ Cargar la versión asignada (si existe)
        ]);

        // ✅ OBTENER TODOS LOS RESÚMENES DEL PROYECTO (CRONOLÓGICO)
        $resumenesProyecto = Resumen::whereHas('acta', function ($query) use ($acta) {
            $query->where('proyecto_id', $acta->proyecto_id)
                ->where('fecha', '<=', $acta->fecha);
        })
        ->with(['acta' => function ($query) {
            $query->select('id', 'numero', 'fecha');
        }])
        ->orderBy('fecha', 'asc')
        ->get();

        // ✅ SIEMPRE OBTENER LA VERSIÓN MÁS RECIENTE Y TODAS LAS ANTERIORES
        $versionMasReciente = Version::masReciente(); // Última versión creada
        $todasLasVersiones = Version::ordenadas('asc')->get(); // Todas las versiones desde la 1 hasta la más reciente

        // Procesar imágenes en campos de texto
        foreach (['objetivo', 'agenda', 'desarrollo', 'conclusiones'] as $campo) {
            $acta->$campo = preg_replace_callback('/<img[^>]+src="([^"]+)"[^>]*>/i', function ($matches) {
                $src = $matches[1];
                $path = public_path(parse_url($src, PHP_URL_PATH));
                return file_exists($path) ? str_replace($src, $path, $matches[0]) : $matches[0];
            }, $acta->$campo ?? '');
        }

        $logoEmpresa = $acta->empresa && $acta->empresa->logo_empresa
            ? public_path('storage/logos/' . $acta->empresa->logo_empresa)
            : public_path('images/logo-placeholder.png');

        $logoGp = public_path('prueba1.png');

        // ✅ SIEMPRE USAR LA VERSIÓN MÁS RECIENTE EN EL ENCABEZADO
        $config = [
            'codigo' => 'FRMGP010',
            'version' => $versionMasReciente ? $versionMasReciente->version : '6', // ✅ Última versión o fallback
            'vigencia' => '7/Dic/2023',
        ];

        $pdf = PDF::loadView('actas.pdf', compact(
            'acta', 'logoEmpresa', 'logoGp', 'config', 'resumenesProyecto', 
            'todasLasVersiones', 'versionMasReciente'
        ));

        return $pdf->stream('acta_' . $acta->numero . '.pdf');
    }

    public function resumenCronologicoPorProyecto($proyectoId)
    {
        $resumenes = Resumen::whereHas('acta', function ($q) use ($proyectoId) {
            $q->where('proyecto_id', $proyectoId);
        })->orderBy('fecha', 'asc')->get();

        $data = $resumenes->map(function ($r) {
            return [
                'fecha' => $r->fecha ? Carbon::parse($r->fecha)->format('Y-m-d') : null,
                'descripcion' => $r->descripcion,
                'horas' => $r->horas,
                'facturable' => (bool) $r->facturable,
            ];
        });

        return response()->json($data);
    }

    /**
     * ✅ MÉTODO NUEVO: Para upload de imágenes en CKEditor
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/ckeditor', $filename, 'public');
            
            $url = asset('storage/' . $path);
            
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }
        
        return response()->json([
            'uploaded' => false,
            'error' => ['message' => 'No se pudo subir la imagen']
        ]);
    }
}