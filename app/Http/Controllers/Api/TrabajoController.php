<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FotoTrabajo;
use App\Models\Trabajo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador para gestión de trabajos
 */
final class TrabajoController extends Controller
{
    /**
     * Listar todos los trabajos con información de fotos por etapa
     */
    public function index(): JsonResponse
    {
        $trabajos = Trabajo::with(['cliente', 'facturas', 'creador'])
            ->orderBy('fecha_ingreso', 'desc')
            ->get()
            ->map(function ($trabajo) {
                // Agregar conteo de fotos por tipo
                $trabajo->fotos_conteo = FotoTrabajo::getConteoPorTipo($trabajo->id);
                return $trabajo;
            });

        return response()->json([
            'success' => true,
            'data' => $trabajos,
        ]);
    }

    /**
     * Obtener detalles de un trabajo específico con sus fotos
     */
    public function show($id): JsonResponse
    {
        $trabajo = Trabajo::with(['cliente', 'facturas', 'creador', 'modificador'])->findOrFail($id);

        // Agregar información completa de fotos
        $trabajo->fotos_conteo = FotoTrabajo::getConteoPorTipo($trabajo->id);
        $trabajo->fotos = FotoTrabajo::porTrabajo($trabajo->id)
            ->with('subidor')
            ->orderBy('fecha_subida', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trabajo,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo_trabajo' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'estado' => 'sometimes|in:pendiente,en_proceso,completado,entregado,cancelado',
            'precio_estimado' => 'numeric|min:0',
            'precio_final' => 'nullable|numeric|min:0',
            'anticipo' => 'numeric|min:0',
            'fecha_entrega' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);

        $validated['estado'] = $validated['estado'] ?? 'pendiente';
        $validated['precio_estimado'] = $validated['precio_estimado'] ?? 0;
        $validated['anticipo'] = $validated['anticipo'] ?? 0;
        $validated['creado_por'] = 1;

        $trabajo = Trabajo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Trabajo creado exitosamente',
            'data' => $trabajo
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $trabajo = Trabajo::findOrFail($id);

        $validated = $request->validate([
            'cliente_id' => 'sometimes|required|exists:clientes,id',
            'tipo_trabajo' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|required|string',
            'estado' => 'sometimes|in:pendiente,en_proceso,completado,entregado,cancelado',
            'precio_estimado' => 'sometimes|numeric|min:0',
            'precio_final' => 'nullable|numeric|min:0',
            'anticipo' => 'sometimes|numeric|min:0',
            'fecha_entrega' => 'nullable|date',
            'fecha_completado' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);

        $validated['modificado_por'] = 1;

        if (isset($validated['estado']) && $validated['estado'] === 'completado' && !isset($validated['fecha_completado'])) {
            $validated['fecha_completado'] = now();
        }

        $trabajo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Trabajo actualizado exitosamente',
            'data' => $trabajo->fresh()
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $trabajo = Trabajo::findOrFail($id);
        $trabajo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Trabajo eliminado exitosamente'
        ]);
    }

    /**
     * Listar trabajos por estado
     */
    public function porEstado($estado): JsonResponse
    {
        $trabajos = Trabajo::with(['cliente', 'facturas'])
            ->where('estado', $estado)
            ->orderBy('fecha_ingreso', 'desc')
            ->get()
            ->map(function ($trabajo) {
                $trabajo->fotos_conteo = FotoTrabajo::getConteoPorTipo($trabajo->id);
                return $trabajo;
            });

        return response()->json([
            'success' => true,
            'data' => $trabajos,
        ]);
    }

    /**
     * Obtener materiales usados en un trabajo
     */
    public function materiales($id): JsonResponse
    {
        $trabajo = Trabajo::findOrFail($id);
        
        $materiales = TrabajoMaterial::where('trabajo_id', $id)
            ->with('inventario')
            ->get()
            ->map(function ($trabajoMaterial) {
                return [
                    'id' => $trabajoMaterial->inventario->id,
                    'nombre' => $trabajoMaterial->inventario->nombre,
                    'categoria' => $trabajoMaterial->inventario->categoria,
                    'unidad' => $trabajoMaterial->inventario->unidad,
                    'cantidad_usada' => $trabajoMaterial->cantidad_usada,
                    'observaciones' => $trabajoMaterial->observaciones,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $materiales,
        ]);
    }

    public function dashboard(): JsonResponse
    {
        $stats = [
            'clientes_totales' => \App\Models\Cliente::where('activo', true)->count(),
            'trabajos_pendientes' => Trabajo::where('estado', 'pendiente')->count(),
            'trabajos_en_proceso' => Trabajo::where('estado', 'en_proceso')->count(),
            'trabajos_completados' => Trabajo::where('estado', 'completado')->count(),
            'trabajos_entregados' => Trabajo::where('estado', 'entregado')->count(),
            'ingresos_mes' => Trabajo::where('estado', 'entregado')
                ->whereMonth('fecha_completado', date('m'))
                ->whereYear('fecha_completado', date('Y'))
                ->sum('precio_final'),
            'ingresos_total' => Trabajo::where('estado', 'entregado')->sum('precio_final'),
            'items_inventario' => \App\Models\Inventario::count(),
            'stock_bajo' => \App\Models\Inventario::whereColumn('stock_actual', '<=', 'stock_minimo')->count(),
            'entregas_hoy' => \App\Models\Entrega::whereDate('fecha_entrega', date('Y-m-d'))
                ->where('estado', 'programada')
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
