<?php

namespace App\Http\Controllers;

use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VersionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Version::query();

        // 🔍 FILTROS DE BÚSQUEDA
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_creacion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_creacion', '<=', $request->fecha_hasta);
        }

        // 📊 OBTENER VERSIONES CON PAGINACIÓN
        $versions = $query->ordenadas('desc')
                         ->with('actas')
                         ->paginate(15)
                         ->appends($request->query());

        // 📈 ESTADÍSTICAS
        $estadisticas = [
            'total' => Version::count(),
            'aprobadas' => Version::aprobadas()->count(),
            'revisadas' => Version::revisadas()->count(),
            'pendientes' => Version::pendientes()->count(),
            'ultima_version' => Version::max('version') ?? 0
        ];

        return view('versions.index', compact('versions', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siguienteVersion = Version::siguienteNumero();
        $estados = Version::getEstados();
        
        return view('versions.create', compact('siguienteVersion', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion_cambio' => 'required|string|max:1000',
            'fecha_creacion' => 'required|date',
            'revisado_por' => 'nullable|string|max:255',
            'fecha_revision' => 'nullable|date',
            'aprobado_por' => 'nullable|string|max:255',
            'fecha_aprobado' => 'nullable|date',
            'estado' => 'required|in:Pendiente,Revisado,Aprobado',
            'fecha_aprobacion_documento' => 'nullable|date',
        ], [
            'descripcion_cambio.required' => 'La descripción del cambio es obligatoria',
            'descripcion_cambio.max' => 'La descripción no puede tener más de 1000 caracteres',
            'fecha_creacion.required' => 'La fecha de creación es obligatoria',
            'fecha_creacion.date' => 'La fecha de creación debe ser una fecha válida',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: Pendiente, Revisado o Aprobado',
        ]);

        try {
            DB::beginTransaction();

            $version = Version::create($request->all());

            DB::commit();

            // 🔄 RESPUESTA AJAX O REDIRECT
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Versión creada exitosamente',
                    'data' => [
                        'id' => $version->id,
                        'version' => $version->version,
                        'descripcion_cambio' => $version->descripcion_cambio,
                        'fecha_creacion' => $version->fecha_creacion->format('d/m/Y'),
                        'estado' => $version->estado,
                        'revisado_por' => $version->revisado_por,
                        'fecha_revision' => $version->fecha_revision ? $version->fecha_revision->format('d/m/Y') : '',
                        'aprobado_por' => $version->aprobado_por,
                        'fecha_aprobado' => $version->fecha_aprobado ? $version->fecha_aprobado->format('d/m/Y') : '',
                        'fecha_aprobacion_documento' => $version->fecha_aprobacion_documento ? $version->fecha_aprobacion_documento->format('d/m/Y') : ''
                    ]
                ]);
            }

            return redirect()->route('versions.index')
                           ->with('success', 'Versión creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la versión: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear la versión: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Version $version)
    {
        $estados = Version::getEstados();
        
        return view('versions.edit', compact('version', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Version $version)
    {
        $request->validate([
            'descripcion_cambio' => 'required|string|max:1000',
            'fecha_creacion' => 'required|date',
            'revisado_por' => 'nullable|string|max:255',
            'fecha_revision' => 'nullable|date',
            'aprobado_por' => 'nullable|string|max:255',
            'fecha_aprobado' => 'nullable|date',
            'estado' => 'required|in:Pendiente,Revisado,Aprobado',
            'fecha_aprobacion_documento' => 'nullable|date',
        ], [
            'descripcion_cambio.required' => 'La descripción del cambio es obligatoria',
            'descripcion_cambio.max' => 'La descripción no puede tener más de 1000 caracteres',
            'fecha_creacion.required' => 'La fecha de creación es obligatoria',
            'estado.required' => 'El estado es obligatorio',
        ]);

        try {
            DB::beginTransaction();

            // No permitir cambiar el número de versión una vez creado
            $datosActualizacion = $request->except(['version']);
            
            $version->update($datosActualizacion);

            DB::commit();

            return redirect()->route('versions.index')
                           ->with('success', 'Versión actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar la versión: ' . $e->getMessage());
        }
    }

    /**
     * 🔢 Obtener el siguiente número de versión disponible
     */
    public function getNextNumber()
    {
        try {
            $siguienteNumero = Version::siguienteNumero();
            
            return response()->json([
                'success' => true,
                'next_version' => $siguienteNumero
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el siguiente número de versión',
                'next_version' => 1
            ], 500);
        }
    }

    /**
     * 📊 Obtener estadísticas de versiones
     */
    public function estadisticas()
    {
        try {
            $estadisticas = [
                'total_versiones' => Version::count(),
                'aprobadas' => Version::aprobadas()->count(),
                'revisadas' => Version::revisadas()->count(),
                'pendientes' => Version::pendientes()->count(),
                'ultima_version' => Version::max('version') ?? 0,
                'version_mas_reciente' => Version::masReciente(),
                'versiones_por_mes' => Version::selectRaw('YEAR(fecha_creacion) as año, MONTH(fecha_creacion) as mes, COUNT(*) as total')
                                            ->groupBy('año', 'mes')
                                            ->orderBy('año', 'desc')
                                            ->orderBy('mes', 'desc')
                                            ->limit(12)
                                            ->get(),
                'versiones_con_actas' => Version::has('actas')->count(),
                'versiones_sin_actas' => Version::doesntHave('actas')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * 🔍 Buscar versiones por término
     */
    public function buscar(Request $request)
    {
        $request->validate([
            'termino' => 'required|string|min:2'
        ]);

        try {
            $versiones = Version::buscar($request->termino)
                               ->ordenadas('desc')
                               ->limit(10)
                               ->get()
                               ->map(function ($version) {
                                   return [
                                       'id' => $version->id,
                                       'version' => $version->version,
                                       'descripcion' => $version->descripcion_cambio,
                                       'estado' => $version->estado,
                                       'fecha' => $version->fecha_creacion->format('d/m/Y'),
                                       'texto' => "Versión {$version->version} - {$version->descripcion_corta}"
                                   ];
                               });

            return response()->json([
                'success' => true,
                'data' => $versiones
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda'
            ], 500);
        }
    }

    /**
     * 📋 Obtener versiones para select
     */
    public function paraSelect()
    {
        try {
            $versiones = Version::ordenadas('desc')
                               ->get()
                               ->map(function ($version) {
                                   return [
                                       'id' => $version->id,
                                       'version' => $version->version,
                                       'texto' => "Versión {$version->version} - {$version->descripcion_cambio}",
                                       'estado' => $version->estado,
                                       'data' => [
                                           'version' => $version->version,
                                           'fecha' => $version->fecha_creacion->format('d/m/Y'),
                                           'descripcion' => $version->descripcion_cambio,
                                           'revisado' => $version->revisado_por,
                                           'fecha_revision' => $version->fecha_revision ? $version->fecha_revision->format('d/m/Y') : '',
                                           'aprobado' => $version->aprobado_por,
                                           'fecha_aprobado' => $version->fecha_aprobado ? $version->fecha_aprobado->format('d/m/Y') : '',
                                           'estado' => $version->estado
                                       ]
                                   ];
                               });

            return response()->json([
                'success' => true,
                'data' => $versiones
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar versiones'
            ], 500);
        }
    }
}