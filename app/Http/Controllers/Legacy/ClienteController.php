<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index() 
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nit' => 'required|unique:clientes',
            'digito_verificacion' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
            'logo_cliente' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nit', 'digito_verificacion', 'nombre', 'direccion']);

        if ($request->hasFile('logo_cliente')) {
            $logo = $request->file('logo_cliente');
            $nombreArchivo = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('logos', $nombreArchivo, 'public');
            $data['logo_cliente'] = $nombreArchivo;
        }

        $cliente = Cliente::create($data);

        if ($request->ajax()) {
            return response()->json([
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'nit' => $cliente->nit,
                'logo_url' => $cliente->logo_cliente ? asset('storage/logos/' . $cliente->logo_cliente) : '',
            ]);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');        
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nit' => 'required|unique:clientes,nit,' . $cliente->id,
            'digito_verificacion' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
            'logo_cliente' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nit', 'digito_verificacion', 'nombre', 'direccion']);

        if ($request->hasFile('logo_cliente')) {
            $logo = $request->file('logo_cliente');
            $nombreArchivo = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('logos', $nombreArchivo, 'public');
            $data['logo_cliente'] = $nombreArchivo;
        }

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
