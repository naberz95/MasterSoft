<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = Persona::with('empresa')->get();
        return view('personas.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('personas.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'iniciales' => 'nullable|string|max:10',
            'cargo' => 'nullable|string|max:100',
            'empresa_id' => 'required|exists:empresas,id',
            'firma' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tarjeta_profesional' => 'nullable|string|max:100',
            'fecha_tarjeta' => 'nullable|date',
            'cedula' => 'nullable|string|max:50',
            'fecha_expedicion_cedula' => 'nullable|date',
            'lugar_expedicion_cedula' => 'nullable|string|max:100',
            'firma_base64' => 'nullable|string',
        ]);

        $data = $request->only([
            'nombre',
            'iniciales',
            'cargo',
            'empresa_id',
            'tarjeta_profesional',
            'fecha_tarjeta',
            'cedula',
            'fecha_expedicion_cedula',
            'lugar_expedicion_cedula',
        ]);

        // Firma por archivo o Signature Pad
        if ($request->hasFile('firma')) {
            $data['firma_path'] = $request->file('firma')->store('firma_personas', 'public');
        } elseif ($request->filled('firma_base64')) {
            $imageData = $request->input('firma_base64');
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'firma_' . time() . '.png';
            
            Storage::disk('public')->put("firma_personas/$imageName", base64_decode($image));
            $data['firma_path'] = "firma_personas/$imageName";
        }

        $persona = Persona::create($data);

        // Respuesta para AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'id' => $persona->id,
                'nombre' => $persona->nombre,
                'iniciales' => $persona->iniciales,
                'cargo' => $persona->cargo,
                'empresa_id' => $persona->empresa_id,
                'tarjeta_profesional' => $persona->tarjeta_profesional,
                'fecha_tarjeta' => $persona->fecha_tarjeta,
                'cedula' => $persona->cedula,
                'fecha_expedicion_cedula' => $persona->fecha_expedicion_cedula,
                'lugar_expedicion_cedula' => $persona->lugar_expedicion_cedula,
                'firma_path' => $persona->firma_path,
                'firma_url' => $persona->firma_path ? asset('storage/' . $persona->firma_path) : null,
            ]);
        }

        return redirect()->route('personas.index')->with('success', 'Persona creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        // Cargar relaciones necesarias con eager loading
        $persona->load([
            'empresa',                    // Empresa a la que pertenece
            'actaPersonas.acta',         // Actas en las que ha participado
            'actaPersonas.compromisos'   // Compromisos asignados
        ]);

        // Estadísticas útiles para la vista
        $stats = [
            'total_actas' => $persona->actaPersonas()->count(),
            'actas_asistidas' => $persona->actaPersonas()->where('asistio', true)->count(),
            'compromisos_pendientes' => $persona->actaPersonas()
                ->join('compromisos', 'acta_persona.id', '=', 'compromisos.acta_persona_id')
                ->where('compromisos.estado', 'Pendiente')
                ->count(),
            'compromisos_cumplidos' => $persona->actaPersonas()
                ->join('compromisos', 'acta_persona.id', '=', 'compromisos.acta_persona_id')
                ->where('compromisos.estado', 'Cumplido')
                ->count(),
        ];

        // NOTA: Como no usamos vista show según requerimientos, redirigir al index
        return redirect()->route('personas.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('personas.edit', compact('persona', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persona $persona)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'iniciales' => 'nullable|max:10',
            'cargo' => 'nullable|max:255',
            'empresa_id' => 'required|exists:empresas,id',
            'firma' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tarjeta_profesional' => 'nullable|string|max:100',
            'fecha_tarjeta' => 'nullable|date',
            'cedula' => 'nullable|string|max:50',
            'fecha_expedicion_cedula' => 'nullable|date',
            'lugar_expedicion_cedula' => 'nullable|string|max:100',
            'firma_base64' => 'nullable|string',
            'eliminar_firma' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'nombre',
            'iniciales',
            'cargo',
            'empresa_id',
            'tarjeta_profesional',
            'fecha_tarjeta',
            'cedula',
            'fecha_expedicion_cedula',
            'lugar_expedicion_cedula',
        ]);

        // 🗑 Eliminar firma si se marcó el checkbox
        if ($request->filled('eliminar_firma')) {
            if ($persona->firma_path && Storage::disk('public')->exists($persona->firma_path)) {
                Storage::disk('public')->delete($persona->firma_path);
            }
            $data['firma_path'] = null;
        }

        // 🖼 Subida de nueva firma como archivo
        elseif ($request->hasFile('firma')) {
            if ($persona->firma_path && Storage::disk('public')->exists($persona->firma_path)) {
                Storage::disk('public')->delete($persona->firma_path);
            }
            $data['firma_path'] = $request->file('firma')->store('firma_personas', 'public');
        }

        // ✍️ Firma dibujada con SignaturePad (base64)
        elseif ($request->filled('firma_base64')) {
            if ($persona->firma_path && Storage::disk('public')->exists($persona->firma_path)) {
                Storage::disk('public')->delete($persona->firma_path);
            }

            $imageData = $request->input('firma_base64');
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'firma_' . time() . '.png';
            Storage::disk('public')->put("firma_personas/$imageName", base64_decode($image));
            $data['firma_path'] = "firma_personas/$imageName";
        }

        $persona->update($data);

        return redirect()->route('personas.index')->with('success', 'Persona actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        // 1. Verificar que no tenga actas asociadas
        $actasCount = $persona->actaPersonas()->count();
        
        if ($actasCount > 0) {
            return redirect()->route('personas.index')
                ->with('error', "No se puede eliminar la persona porque tiene {$actasCount} acta(s) asociada(s).");
        }

        // 2. Verificar que no sea firmante en ninguna acta
        $actasFirmanteEmpresa = \App\Models\Acta::where('firmante_empresa_id', $persona->id)->count();
        $actasFirmanteGp = \App\Models\Acta::where('firmante_gp_id', $persona->id)->count();
        
        if ($actasFirmanteEmpresa > 0 || $actasFirmanteGp > 0) {
            $totalFirmas = $actasFirmanteEmpresa + $actasFirmanteGp;
            return redirect()->route('personas.index')
                ->with('error', "No se puede eliminar la persona porque es firmante en {$totalFirmas} acta(s).");
        }

        // 3. Eliminar archivo de firma si existe
        if ($persona->firma_path) {
            // Verificar si el archivo existe en storage antes de eliminarlo
            if (Storage::disk('public')->exists($persona->firma_path)) {
                Storage::disk('public')->delete($persona->firma_path);
            }
        }

        // 4. Eliminar la persona
        $nombrePersona = $persona->nombre;
        $persona->delete();
        
        return redirect()->route('personas.index')
            ->with('success', "Persona '{$nombrePersona}' eliminada exitosamente.");
    }
}