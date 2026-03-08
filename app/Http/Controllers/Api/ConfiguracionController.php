<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConfiguracionController extends Controller
{
    public function index(): JsonResponse
    {
        $configuracion = Configuracion::all();

        return response()->json([
            'success' => true,
            'data' => $configuracion
        ]);
    }

    public function show($clave): JsonResponse
    {
        $config = Configuracion::where('clave', $clave)->first();

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Configuración no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    public function update(Request $request, $clave): JsonResponse
    {
        $validated = $request->validate([
            'valor' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        $config = Configuracion::updateOrCreate(
            ['clave' => $clave],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Configuración actualizada exitosamente',
            'data' => $config
        ]);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'configuracion' => 'required|array',
        ]);

        foreach ($validated['configuracion'] as $clave => $valor) {
            Configuracion::updateOrCreate(
                ['clave' => $clave],
                ['valor' => $valor]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Configuración actualizada exitosamente'
        ]);
    }
}
