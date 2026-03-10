<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Trabajo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Obtener todas las notificaciones
     */
    public function index(Request $request): JsonResponse
    {
        $soloNoLeidas = $request->boolean('no_leidas', false);

        $query = Notificacion::with(['trabajo.cliente'])
            ->orderBy('created_at', 'desc');

        if ($soloNoLeidas) {
            $query->noLeidas();
        }

        $notificaciones = $query->limit(50)->get();

        return response()->json([
            'success' => true,
            'data' => $notificaciones,
            'conteo' => [
                'total' => Notificacion::count(),
                'no_leidas' => Notificacion::noLeidas()->count(),
                'urgentes' => Notificacion::noLeidas()->porPrioridad('urgent')->count(),
                'importantes' => Notificacion::noLeidas()->porPrioridad('high')->count(),
            ],
        ]);
    }

    /**
     * Obtener una notificación específica
     */
    public function show(int $id): JsonResponse
    {
        $notificacion = Notificacion::with(['trabajo.cliente'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $notificacion,
        ]);
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarLeida(int $id): JsonResponse
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->marcarComoLeida();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas(): JsonResponse
    {
        Notificacion::noLeidas()->update([
            'leida' => true,
            'fecha_leida' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas',
        ]);
    }

    /**
     * Eliminar una notificación
     */
    public function destroy(int $id): JsonResponse
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada',
        ]);
    }

    /**
     * Obtener notificaciones para el dashboard
     */
    public function dashboard(): JsonResponse
    {
        // Entregas próximas (en las próximas 24 horas)
        $entregasProximas = Trabajo::entregaProxima()
            ->with(['cliente', 'notificaciones'])
            ->orderBy('fecha_entrega')
            ->limit(10)
            ->get();

        // Entregas de hoy
        $entregasHoy = Trabajo::entregaHoy()
            ->with(['cliente'])
            ->orderBy('fecha_entrega')
            ->get();

        // Trabajos retrasados
        $retrasados = Trabajo::retrasados()
            ->with(['cliente'])
            ->orderBy('fecha_entrega')
            ->limit(10)
            ->get();

        // Generar notificaciones automáticas para entregas de hoy
        foreach ($entregasHoy as $trabajo) {
            $existeNotificacion = Notificacion::where('tipo', Notificacion::TIPO_ENTREGA_HOY)
                ->where('trabajo_id', $trabajo->id)
                ->exists();

            if (!$existeNotificacion) {
                Notificacion::crearNotificacionEntregaHoy($trabajo);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'entregas_proximas' => $entregasProximas,
                'entregas_hoy' => $entregasHoy,
                'retrasados' => $retrasados,
                'alertas' => [
                    'entregas_proximas_count' => $entregasProximas->count(),
                    'entregas_hoy_count' => $entregasHoy->count(),
                    'retrasados_count' => $retrasados->count(),
                ],
            ],
        ]);
    }

    /**
     * Crear notificación manual
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tipo' => 'required|string',
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'prioridad' => 'nullable|in:low,normal,high,urgent',
            'icono' => 'nullable|string',
            'trabajo_id' => 'nullable|exists:trabajos,id',
            'datos_adicionales' => 'nullable|array',
        ]);

        $notificacion = Notificacion::create([
            'tipo' => $validated['tipo'],
            'titulo' => $validated['titulo'],
            'mensaje' => $validated['mensaje'],
            'prioridad' => $validated['prioridad'] ?? Notificacion::PRIORIDAD_NORMAL,
            'icono' => $validated['icono'] ?? Notificacion::getIconoPorTipo($validated['tipo']),
            'trabajo_id' => $validated['trabajo_id'] ?? null,
            'datos_adicionales' => $validated['datos_adicionales'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notificación creada exitosamente',
            'data' => $notificacion,
        ], 201);
    }
}
