<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    /**
     * Listar clientes con búsqueda opcional
     */
    public function index(Request $request): JsonResponse
    {
        $query = Cliente::with(['creador', 'modificador']);

        // Búsqueda por nombre, apellido o documento
        if ($request->has('busqueda')) {
            $busqueda = $request->get('busqueda');
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                  ->orWhere('documento', 'LIKE', "%{$busqueda}%")
                  ->orWhereRaw("CONCAT(nombre, ' ', apellido) LIKE ?", ["%{$busqueda}%"]);
            });
        }

        // Búsqueda específica por documento (cédula)
        if ($request->has('documento')) {
            $query->where('documento', 'LIKE', "%{$request->get('documento')}%");
        }

        // Filtrar por estado activo
        if ($request->has('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        $clientes = $query->orderBy('apellido', 'asc')
            ->orderBy('nombre', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clientes
        ]);
    }

    /**
     * Obtener cliente con sus trabajos y facturas
     */
    public function show($id): JsonResponse
    {
        $cliente = Cliente::with([
            'trabajos' => function ($q) {
                $q->with('fotos')->orderBy('fecha_ingreso', 'desc');
            },
            'trabajos.fotos',
            'creador',
            'modificador'
        ])->findOrFail($id);

        // Obtener facturas del cliente
        $facturas = \App\Models\Factura::whereHas('trabajo', function ($q) use ($id) {
                $q->where('cliente_id', $id);
            })
            ->with(['trabajo', 'condiciones'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        // Contar trabajos por estado
        $trabajosPorEstado = $cliente->trabajos->groupBy('estado')->map->count();

        return response()->json([
            'success' => true,
            'data' => [
                ...$cliente->toArray(),
                'facturas' => $facturas,
                'trabajos_por_estado' => $trabajosPorEstado,
                'total_trabajos' => $cliente->trabajos->count(),
                'total_facturas' => $facturas->count(),
            ]
        ]);
    }

    /**
     * Obtener trabajos de un cliente
     */
    public function trabajos($id): JsonResponse
    {
        $cliente = Cliente::findOrFail($id);

        $trabajos = $cliente->trabajos()
            ->with(['fotos', 'facturas', 'creador'])
            ->orderBy('fecha_ingreso', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'cliente' => $cliente,
                'trabajos' => $trabajos,
                'total' => $trabajos->count(),
                'por_estado' => $trabajos->groupBy('estado')->map->count(),
            ]
        ]);
    }

    /**
     * Obtener facturas de un cliente
     */
    public function facturas($id): JsonResponse
    {
        $cliente = Cliente::findOrFail($id);

        $facturas = \App\Models\Factura::whereHas('trabajo', function ($q) use ($id) {
                $q->where('cliente_id', $id);
            })
            ->with(['trabajo', 'condiciones', 'emisor'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'cliente' => $cliente,
                'facturas' => $facturas,
                'total' => $facturas->count(),
                'total_pagado' => $facturas->where('estado_pago', 'pagado')->sum('total'),
                'total_pendiente' => $facturas->whereIn('estado_pago', ['pendiente', 'parcial'])->sum('total'),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:150',
            'direccion' => 'required|string',
            'activo' => 'boolean',
        ]);

        $validated['activo'] = $validated['activo'] ?? true;
        $validated['creado_por'] = 1; // Default admin

        $cliente = Cliente::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cliente creado exitosamente',
            'data' => $cliente
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'apellido' => 'sometimes|required|string|max:100',
            'telefono' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:150',
            'direccion' => 'sometimes|required|string',
            'activo' => 'boolean',
        ]);

        $validated['modificado_por'] = 1;
        $validated['fecha_modificacion'] = now();

        $cliente->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cliente actualizado exitosamente',
            'data' => $cliente
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado exitosamente'
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        $clientes = Cliente::where('nombre', 'ILIKE', "%{$query}%")
            ->orWhere('apellido', 'ILIKE', "%{$query}%")
            ->orWhere('telefono', 'ILIKE', "%{$query}%")
            ->orWhere('email', 'ILIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clientes
        ]);
    }
}
