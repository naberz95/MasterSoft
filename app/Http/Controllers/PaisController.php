<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paises = Pais::withCount(['ciudades'])
                      ->orderBy('nombre')
                      ->get();
        return view('paises.index', compact('paises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paises.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:paises,nombre',
        ]);

        Pais::create($request->only('nombre'));

        return redirect()->route('paises.index')->with('success', 'País creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pais $pais)
    {
        // Método show será implementado en el futuro si se necesita
        return redirect()->route('paises.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pais $pais)
    {
        return view('paises.edit', compact('pais'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pais $pais)
    {
        $request->validate([
            'nombre' => [
                'required',
                Rule::unique('paises')->ignore($pais->id)
            ],
        ]);

        $pais->update($request->only('nombre'));

        return redirect()->route('paises.index')->with('success', 'País actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pais $pais)
    {
        // Verificar que no tenga ciudades asociadas
        if ($pais->ciudades()->count() > 0) {
            return redirect()->route('paises.index')
                ->with('error', 'No se puede eliminar el país porque tiene ciudades asociadas.');
        }
        
        $pais->delete();
        return redirect()->route('paises.index')->with('success', 'País eliminado exitosamente.');
    }
}