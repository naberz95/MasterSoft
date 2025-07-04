<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyectos = Proyecto::with(['empresa'])
                            ->withCount(['actas'])
                            ->orderBy('nombre')
                            ->get();
        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('proyectos.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:proyectos,nombre',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        Proyecto::create($request->only('nombre', 'empresa_id'));

        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto)
    {
        // No implementar show por requerimientos
        return redirect()->route('proyectos.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyecto $proyecto)
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('proyectos.edit', compact('proyecto', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'nombre' => [
                'required',
                Rule::unique('proyectos')->ignore($proyecto->id)
            ],
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        $proyecto->update($request->only('nombre', 'empresa_id'));

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto)
    {
        // Verificar que no tenga actas asociadas
        if ($proyecto->actas()->count() > 0) {
            return redirect()->route('proyectos.index')
                ->with('error', 'No se puede eliminar el proyecto porque tiene actas asociadas.');
        }
        
        $proyecto->delete();
        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado exitosamente.');
    }
}