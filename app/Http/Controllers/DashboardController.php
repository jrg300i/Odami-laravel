<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Trabajo;
use App\Models\Factura;
use App\Models\Pago;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Estadísticas según el rol
        if ($user->isAdmin()) {
            $estadisticas = [
                'total_clientes' => Cliente::count(),
                'total_trabajos' => Trabajo::count(),
                'trabajos_en_proceso' => Trabajo::where('estado', 'en_proceso')->count(),
                'trabajos_completados' => Trabajo::where('estado', 'completado')->count(),
                'total_facturas' => Factura::count(),
                'facturas_pendientes' => Factura::where('estado', 'pendiente')->count(),
                'facturas_pagadas' => Factura::where('estado', 'pagada')->count(),
                'ingresos_mes_actual' => Factura::whereMonth('created_at', now()->month)
                    ->where('estado', 'pagada')
                    ->sum('total'),
            ];
        } elseif ($user->isTapicero()) {
            $estadisticas = [
                'mis_trabajos' => Trabajo::where('tapicero_id', $user->id)->count(),
                'trabajos_en_proceso' => Trabajo::where('tapicero_id', $user->id)
                    ->where('estado', 'en_proceso')
                    ->count(),
                'trabajos_completados' => Trabajo::where('tapicero_id', $user->id)
                    ->where('estado', 'completado')
                    ->count(),
                'proximas_entregas' => Trabajo::where('tapicero_id', $user->id)
                    ->where('estado', 'en_proceso')
                    ->whereDate('fecha_entrega_estimada', '>=', now())
                    ->orderBy('fecha_entrega_estimada')
                    ->take(5)
                    ->get(),
            ];
        } else {
            // Para clientes
            $estadisticas = [
                'mis_trabajos' => Trabajo::where('cliente_id', $user->id)->count(),
                'trabajos_en_proceso' => Trabajo::where('cliente_id', $user->id)
                    ->where('estado', 'en_proceso')
                    ->count(),
                'trabajos_completados' => Trabajo::where('cliente_id', $user->id)
                    ->where('estado', 'completado')
                    ->count(),
                'facturas_pendientes' => Factura::where('cliente_id', $user->id)
                    ->where('estado', 'pendiente')
                    ->count(),
            ];
        }

        // Trabajos recientes
        $trabajos_recientes = Trabajo::with('cliente')
            ->latest()
            ->take(5)
            ->get();

        // Últimos pagos
        $ultimos_pagos = Pago::with('factura.cliente')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('estadisticas', 'trabajos_recientes', 'ultimos_pagos'));
    }

    public function estadisticasAvanzadas()
    {
        $this->authorize('is-admin');

        // Estadísticas mensuales
        $ingresos_por_mes = Factura::selectRaw('
            MONTH(created_at) as mes,
            YEAR(created_at) as año,
            SUM(total) as total
        ')
        ->where('estado', 'pagada')
        ->whereYear('created_at', now()->year)
        ->groupBy('año', 'mes')
        ->orderBy('año')
        ->orderBy('mes')
        ->get();

        // Trabajos por tipo
        $trabajos_por_tipo = Trabajo::selectRaw('tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->get();

        // Clientes nuevos por mes
        $clientes_nuevos = Cliente::selectRaw('
            MONTH(created_at) as mes,
            YEAR(created_at) as año,
            COUNT(*) as total
        ')
        ->whereYear('created_at', now()->year)
        ->groupBy('año', 'mes')
        ->orderBy('año')
        ->orderBy('mes')
        ->get();

        return view('admin.system.estadisticas', compact(
            'ingresos_por_mes',
            'trabajos_por_tipo',
            'clientes_nuevos'
        ));
    }
}