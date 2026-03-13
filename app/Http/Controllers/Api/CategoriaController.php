<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    /**
     * Listar categorías
     */
    public function index(Request $request): JsonResponse
    {
        $query = Categoria::withCount('inventario');

        // Filtro por activo
        if ($request->has('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        // Búsqueda por nombre
        if ($request->has('nombre')) {
            $query->where('nombre', 'LIKE', "%{$request->get('nombre')}%");
        }

        $categorias = $query->orderBy('orden')->orderBy('nombre')->get();

        return response()->json([
            'success' => true,
            'data' => $categorias,
        ]);
    }

    /**
     * Obtener categoría específica
     */
    public function show($id): JsonResponse
    {
        $categoria = Categoria::withCount('inventario')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $categoria,
        ]);
    }

    /**
     * Crear categoría
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'icono' => 'nullable|string|max:50',
            'activo' => 'boolean',
            'orden' => 'integer',
        ]);

        $validated['color'] = $validated['color'] ?? 'bg-blue-500';
        $validated['icono'] = $validated['icono'] ?? 'fa-box';
        $validated['activo'] = $validated['activo'] ?? true;
        $validated['orden'] = $validated['orden'] ?? 0;

        $categoria = Categoria::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente',
            'data' => $categoria
        ], 201);
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, $id): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'icono' => 'nullable|string|max:50',
            'activo' => 'boolean',
            'orden' => 'integer',
        ]);

        $categoria->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente',
            'data' => $categoria->fresh()
        ]);
    }

    /**
     * Eliminar categoría
     */
    public function destroy($id): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);

        // Verificar si tiene items asociados
        if ($categoria->inventario()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la categoría porque tiene items asociados'
            ], 400);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }
}
