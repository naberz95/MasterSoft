<?php

namespace App\Http\Controllers;

use App\Models\TipoActa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoActaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = TipoActa::withCount('actas')->orderBy('nombre')->get();
        return view('tipos-acta.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipos-acta.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:tipos_acta,nombre|max:255',
        ]);

        TipoActa::create($request->only('nombre'));

        return redirect()->route('tipos-acta.index')->with('success', 'Tipo de acta creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoActa $tiposActa)
    {
        // No implementar show por requerimientos
        return redirect()->route('tipos-acta.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoActa $tiposActa)
    {
        return view('tipos-acta.edit', ['tipo' => $tiposActa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoActa $tiposActa)
    {
        $request->validate([
            'nombre' => [
                'required',
                'max:255',
                Rule::unique('tipos_acta')->ignore($tiposActa->id)
            ],
        ]);

        $tiposActa->update($request->only('nombre'));

        return redirect()->route('tipos-acta.index')->with('success', 'Tipo de acta actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoActa $tiposActa)
    {
        // Verificar que no tenga actas asociadas
        if ($tiposActa->actas()->count() > 0) {
            return redirect()->route('tipos-acta.index')
                ->with('error', 'No se puede eliminar el tipo de acta porque tiene actas asociadas.');
        }
        
        $tiposActa->delete();
        return redirect()->route('tipos-acta.index')->with('success', 'Tipo de acta eliminado exitosamente.');
    }
}