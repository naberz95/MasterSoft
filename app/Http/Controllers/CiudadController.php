<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CiudadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ciudades = Ciudad::with(['pais'])
                          ->withCount(['actas'])
                          ->orderBy('nombre')
                          ->get();
        return view('ciudades.index', compact('ciudades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paises = Pais::orderBy('nombre')->get();
        return view('ciudades.create', compact('paises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:ciudades,nombre',
            'pais_id' => 'required|exists:paises,id',
        ]);

        Ciudad::create($request->only('nombre', 'pais_id'));

        return redirect()->route('ciudades.index')->with('success', 'Ciudad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ciudad $ciudad)
    {
        $ciudad->load(['pais', 'actas.proyecto', 'actas.empresa']);
        return view('ciudades.show', compact('ciudad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ciudad $ciudad)
    {
        $paises = Pais::orderBy('nombre')->get();
        return view('ciudades.edit', compact('ciudad', 'paises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ciudad $ciudad)
    {
        $request->validate([
            'nombre' => [
                'required',
                Rule::unique('ciudades')->ignore($ciudad->id)
            ],
            'pais_id' => 'required|exists:paises,id',
        ]);

        $ciudad->update($request->only('nombre', 'pais_id'));

        return redirect()->route('ciudades.index')->with('success', 'Ciudad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ciudad $ciudad)
    {
        // Verificar que no tenga actas asociadas
        if ($ciudad->actas()->count() > 0) {
            return redirect()->route('ciudades.index')
                ->with('error', 'No se puede eliminar la ciudad porque tiene actas asociadas.');
        }
        
        $ciudad->delete();
        return redirect()->route('ciudades.index')->with('success', 'Ciudad eliminada exitosamente.');
    }
}