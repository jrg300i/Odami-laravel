<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CondicionTrabajo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CondicionController extends Controller
{
    /**
     * Listar condiciones activas
     */
    public function index(): JsonResponse
    {
        $condiciones = CondicionTrabajo::activas()->get();

        return response()->json([
            'success' => true,
            'data' => $condiciones
        ]);
    }

    /**
     * Listar todas las condiciones (activas e inactivas)
     */
    public function all(): JsonResponse
    {
        $condiciones = CondicionTrabajo::orderBy('orden')->get();

        return response()->json([
            'success' => true,
            'data' => $condiciones
        ]);
    }

    /**
     * Crear una nueva condición
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'activa' => 'boolean',
            'orden' => 'integer',
        ]);

        $validated['activa'] = $validated['activa'] ?? true;
        $validated['orden'] = $validated['orden'] ?? 0;

        $condicion = CondicionTrabajo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Condición creada exitosamente',
            'data' => $condicion
        ], 201);
    }

    /**
     * Actualizar una condición
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $condicion = CondicionTrabajo::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|required|string',
            'activa' => 'boolean',
            'orden' => 'integer',
        ]);

        $condicion->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Condición actualizada exitosamente',
            'data' => $condicion
        ]);
    }

    /**
     * Eliminar una condición
     */
    public function destroy(int $id): JsonResponse
    {
        $condicion = CondicionTrabajo::findOrFail($id);
        $condicion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Condición eliminada exitosamente'
        ]);
    }
}
