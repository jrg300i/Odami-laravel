<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Trabajo;
use App\Models\Factura;
use App\Models\Inventario;
use App\Models\Entrega;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        // Calcular ingresos del mes (trabajos completados o entregados este mes)
        // Usar precio_final si existe, sino precio_estimado
        // Usar fecha_completado, fecha_entrega, updated_at o fecha_ingreso para el mes
        $ingresosMes = Trabajo::whereIn('estado', ['completado', 'entregado'])
            ->get()
            ->filter(function ($trabajo) {
                // Obtener la fecha más relevante para determinar el mes
                $fecha = $trabajo->fecha_completado 
                      ?? $trabajo->fecha_entrega 
                      ?? $trabajo->updated_at 
                      ?? $trabajo->fecha_ingreso;
                
                return $fecha && date('Y-m', strtotime($fecha)) === date('Y-m');
            })
            ->sum(function ($trabajo) {
                return $trabajo->precio_final ?? $trabajo->precio_estimado ?? 0;
            });

        // Calcular ingresos totales (todos los trabajos completados o entregados)
        $ingresosTotal = Trabajo::whereIn('estado', ['completado', 'entregado'])
            ->get()
            ->sum(function ($trabajo) {
                return $trabajo->precio_final ?? $trabajo->precio_estimado ?? 0;
            });

        $stats = [
            'clientes_totales' => Cliente::where('activo', true)->count(),
            'trabajos_pendientes' => Trabajo::where('estado', 'pendiente')->count(),
            'trabajos_en_proceso' => Trabajo::where('estado', 'en_proceso')->count(),
            'trabajos_completados' => Trabajo::where('estado', 'completado')->count(),
            'trabajos_entregados' => Trabajo::where('estado', 'entregado')->count(),
            'ingresos_mes' => round($ingresosMes, 2),
            'ingresos_total' => round($ingresosTotal, 2),
            'items_inventario' => Inventario::count(),
            'stock_bajo' => Inventario::whereColumn('stock_actual', '<=', 'stock_minimo')->count(),
            'entregas_hoy' => Entrega::whereDate('fecha_entrega', date('Y-m-d'))
                ->where('estado', 'programada')
                ->count(),
            'facturas_pendientes' => Factura::where('estado_pago', '!=', 'pagado')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function trabajosRecientes(): JsonResponse
    {
        $trabajos = Trabajo::with(['cliente', 'fotos'])
            ->orderBy('fecha_ingreso', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($trabajo) {
                return [
                    'id' => $trabajo->id,
                    'tipo_trabajo' => $trabajo->tipo_trabajo,
                    'descripcion' => $trabajo->descripcion,
                    'estado' => $trabajo->estado,
                    'precio_estimado' => $trabajo->precio_estimado,
                    'anticipo' => $trabajo->anticipo,
                    'fecha_ingreso' => $trabajo->fecha_ingreso,
                    'cliente_id' => $trabajo->cliente_id,
                    'cliente_nombre' => $trabajo->cliente?->nombre_completo,
                    'cliente_documento' => $trabajo->cliente?->documento,
                    'facturas_count' => $trabajo->facturas()->count(),
                    'materiales_count' => $trabajo->materiales()->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $trabajos,
        ]);
    }

    public function entregasHoy(): JsonResponse
    {
        $entregas = Entrega::with(['trabajo.cliente'])
            ->whereDate('fecha_entrega', date('Y-m-d'))
            ->orderBy('fecha_entrega', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entregas
        ]);
    }

    public function stockCritico(): JsonResponse
    {
        $items = Inventario::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderByRaw('stock_minimo - stock_actual DESC')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}
