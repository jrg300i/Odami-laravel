<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppConfigController extends Controller
{
    /**
     * Obtener configuración de la API (público, sin autenticación)
     * 
     * @return JsonResponse
     */
    public function config(): JsonResponse
    {
        try {
            // Verificar si la tabla existe
            if (!Schema::hasTable('app_config')) {
                return response()->json([
                    'success' => true,
                    'config' => [
                        'api_url_local' => 'http://localhost:8000',
                        'api_modo' => 'auto'
                    ],
                    'timestamp' => now()->toIso8601String()
                ]);
            }

            // Obtener configuración de la BD
            $configRows = DB::table('app_config')
                ->select('clave', 'valor')
                ->get();

            $config = [];
            foreach ($configRows as $row) {
                $config[$row->clave] = $row->valor;
            }

            // Valores por defecto si no existen
            if (empty($config)) {
                $config = [
                    'api_url_local' => 'http://localhost:8000',
                    'api_modo' => 'auto'
                ];
            }

            return response()->json([
                'success' => true,
                'config' => $config,
                'timestamp' => now()->toIso8601String()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener configuración',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar configuración de la API
     * 
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function updateConfig(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $clave = $request->input('clave');
            $valor = $request->input('valor');

            if (!$clave || !$valor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Clave y valor son requeridos'
                ], 400);
            }

            // Actualizar o insertar
            DB::table('app_config')->updateOrInsert(
                ['clave' => $clave],
                [
                    'valor' => $valor,
                    'actualizado_en' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada',
                'config' => [
                    'clave' => $clave,
                    'valor' => $valor
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar configuración',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
