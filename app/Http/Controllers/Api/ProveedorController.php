<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProveedorController extends Controller
{
    /**
     * Listar proveedores
     */
    public function index(Request $request): JsonResponse
    {
        $query = Proveedor::withCount('inventario');

        // Filtro por activo
        if ($request->has('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        // Búsqueda por nombre
        if ($request->has('nombre')) {
            $query->porNombre($request->get('nombre'));
        }

        $proveedores = $query->orderBy('nombre', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $proveedores,
        ]);
    }

    /**
     * Obtener proveedor específico
     */
    public function show($id): JsonResponse
    {
        $proveedor = Proveedor::withCount('inventario')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $proveedor,
        ]);
    }

    /**
     * Crear proveedor
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'ruc' => 'nullable|string|max:20',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:200',
            'contacto' => 'nullable|string|max:150',
            'telefono_contacto' => 'nullable|string|max:20',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $validated['activo'] = $validated['activo'] ?? true;

        $proveedor = Proveedor::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Proveedor creado exitosamente',
            'data' => $proveedor
        ], 201);
    }

    /**
     * Actualizar proveedor
     */
    public function update(Request $request, $id): JsonResponse
    {
        $proveedor = Proveedor::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'ruc' => 'nullable|string|max:20',
            'telefono' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:200',
            'contacto' => 'nullable|string|max:150',
            'telefono_contacto' => 'nullable|string|max:20',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $proveedor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Proveedor actualizado exitosamente',
            'data' => $proveedor->fresh()
        ]);
    }

    /**
     * Eliminar proveedor
     */
    public function destroy($id): JsonResponse
    {
        $proveedor = Proveedor::findOrFail($id);
        
        // Verificar si tiene items asociados
        if ($proveedor->inventario()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el proveedor porque tiene items asociados'
            ], 400);
        }
        
        $proveedor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proveedor eliminado exitosamente'
        ]);
    }
}
