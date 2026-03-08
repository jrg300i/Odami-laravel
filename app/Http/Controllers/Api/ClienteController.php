<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    public function index(): JsonResponse
    {
        $clientes = Cliente::with(['creador', 'modificador'])
            ->orderBy('fecha_registro', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clientes
        ]);
    }

    public function show($id): JsonResponse
    {
        $cliente = Cliente::with(['trabajos', 'creador', 'modificador'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $cliente
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
