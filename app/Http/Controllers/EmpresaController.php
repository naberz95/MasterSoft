<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::withCount(['proyectos', 'actas'])
                           ->orderBy('nombre')
                           ->get();
        return view('empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   
    public function store(Request $request)
        {
            $request->validate([
                'nombre' => 'required|unique:empresas,nombre|max:255',
                'nit' => 'required|unique:empresas,nit|max:50',
                'digito_verificacion' => 'nullable|max:1',
                'direccion' => 'nullable|max:255',
                'telefono' => 'nullable|max:20',
                'email' => 'nullable|email|max:255',
                'logo_empresa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $data = $request->only([
                'nombre', 
                'nit', 
                'digito_verificacion', 
                'direccion', 
                'telefono', 
                'email'
            ]);

            // 🖼️ Manejar subida del logo
            if ($request->hasFile('logo_empresa')) {
                $logo = $request->file('logo_empresa');
                $nombreArchivo = time() . '_' . $logo->getClientOriginalName();
                $path = $logo->storeAs('logos', $nombreArchivo, 'public');
                $data['logo_empresa'] = $nombreArchivo;
            }

            $empresa = Empresa::create($data);

            // ✅ RESPUESTA DIFERENCIADA PARA AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Empresa creada exitosamente',
                    'id' => $empresa->id,
                    'nombre' => $empresa->nombre,
                    'nit' => $empresa->nit,
                    'digito_verificacion' => $empresa->digito_verificacion,
                    'direccion' => $empresa->direccion,
                    'telefono' => $empresa->telefono,
                    'email' => $empresa->email,
                    'logo_url' => $empresa->logo_empresa 
                        ? asset('storage/logos/' . $empresa->logo_empresa) 
                        : asset('images/logo-placeholder.png'),
                ], 201);
            }

            // ✅ RESPUESTA NORMAL PARA FORMULARIOS REGULARES
            return redirect()->route('empresas.index')
                ->with('success', 'Empresa creada exitosamente.');
        }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        // Implementar si se necesita
        return redirect()->route('empresas.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre' => [
                'required',
                Rule::unique('empresas')->ignore($empresa->id)
            ],
            'nit' => [
                'nullable',
                Rule::unique('empresas')->ignore($empresa->id)
            ],
            'logo_empresa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('nombre', 'nit', 'digito_verificacion', 'direccion', 'telefono', 'email');

        // Manejar eliminación de logo
        if ($request->has('eliminar_logo') && $empresa->logo_empresa) {
            if (Storage::disk('public')->exists('logos/' . $empresa->logo_empresa)) {
                Storage::disk('public')->delete('logos/' . $empresa->logo_empresa);
            }
            $data['logo_empresa'] = null;
        }

        // Manejar nuevo logo
        if ($request->hasFile('logo_empresa')) {
            // Eliminar logo anterior si existe
            if ($empresa->logo_empresa && Storage::disk('public')->exists('logos/' . $empresa->logo_empresa)) {
                Storage::disk('public')->delete('logos/' . $empresa->logo_empresa);
            }

            $logo = $request->file('logo_empresa');
            $nombreArchivo = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('logos', $nombreArchivo, 'public');
            $data['logo_empresa'] = $nombreArchivo;
        }

        $empresa->update($data);

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        // Verificar que no tenga proyectos o actas asociadas
        $proyectosCount = $empresa->proyectos()->count();
        $actasCount = $empresa->actas()->count();
        
        if ($proyectosCount > 0 || $actasCount > 0) {
            return redirect()->route('empresas.index')
                ->with('error', "No se puede eliminar la empresa porque tiene {$proyectosCount} proyecto(s) y {$actasCount} acta(s) asociados.");
        }
        
        // Eliminar logo si existe
        if ($empresa->logo_empresa && Storage::disk('public')->exists('logos/' . $empresa->logo_empresa)) {
            Storage::disk('public')->delete('logos/' . $empresa->logo_empresa);
        }
        
        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada exitosamente.');
    }
}