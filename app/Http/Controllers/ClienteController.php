<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\ClienteRequest;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::with(['trabajos', 'facturas']);

        // Búsqueda
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filtros
        if ($request->has('activo')) {
            $query->where('activo', $request->activo);
        }

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $clientes = $query->orderBy('created_at', 'desc')
                         ->paginate(15);

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(ClienteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['codigo_cliente'] = $this->generarCodigoCliente();

        $cliente = Cliente::create($data);

        return redirect()->route('clientes.show', $cliente)
                        ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['trabajos', 'facturas.pagos', 'trabajos.fotos']);
        
        $estadisticas = [
            'total_trabajos' => $cliente->trabajos->count(),
            'trabajos_completados' => $cliente->trabajos->where('estado', 'completado')->count(),
            'total_facturado' => $cliente->total_facturado,
            'total_pagado' => $cliente->total_pagado,
        ];

        return view('clientes.show', compact('cliente', 'estadisticas'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.show', $cliente)
                        ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        // Verificar si tiene trabajos o facturas asociadas
        if ($cliente->trabajos()->exists() || $cliente->facturas()->exists()) {
            return redirect()->back()
                            ->with('error', 'No se puede eliminar el cliente porque tiene trabajos o facturas asociadas.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente eliminado exitosamente.');
    }

    private function generarCodigoCliente()
    {
        $ultimoCliente = Cliente::orderBy('id', 'desc')->first();
        $numero = $ultimoCliente ? intval(substr($ultimoCliente->codigo_cliente, 2)) + 1 : 1;
        
        return 'CL' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}