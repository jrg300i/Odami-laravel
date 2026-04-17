<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Trabajo;
use App\Models\Pago;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteService
{
    public function generarReporteFacturacion($fechaInicio, $fechaFin)
    {
        $facturas = Factura::with(['cliente', 'trabajo'])
                          ->whereBetween('fecha_emision', [$fechaInicio, $fechaFin])
                          ->where('estado', '!=', 'cancelada')
                          ->orderBy('fecha_emision')
                          ->get();

        $totales = [
            'subtotal' => $facturas->sum('subtotal'),
            'iva' => $facturas->sum('total') - $facturas->sum('subtotal'),
            'total' => $facturas->sum('total'),
            'cantidad' => $facturas->count(),
        ];

        $porSerie = $facturas->groupBy('serie')->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'subtotal' => $grupo->sum('subtotal'),
                'iva' => $grupo->sum('total') - $grupo->sum('subtotal'),
                'total' => $grupo->sum('total')
            ];
        });

        $porEstado = $facturas->groupBy('estado')->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'total' => $grupo->sum('total')
            ];
        });

        $porMes = $facturas->groupBy(function($factura) {
            return Carbon::parse($factura->fecha_emision)->format('Y-m');
        })->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'total' => $grupo->sum('total')
            ];
        });

        return [
            'facturas' => $facturas,
            'totales' => $totales,
            'por_serie' => $porSerie,
            'por_estado' => $porEstado,
            'por_mes' => $porMes,
            'periodo' => [
                'inicio' => $fechaInicio,
                'fin' => $fechaFin
            ]
        ];
    }

    public function generarReporteTrabajos($fechaInicio, $fechaFin, $tipo = 'todos')
    {
        $query = Trabajo::with(['cliente', 'fotos', 'materiales'])
                       ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if ($tipo !== 'todos') {
            $query->where('tipo', $tipo);
        }

        $trabajos = $query->orderBy('created_at', 'desc')->get();

        $totales = [
            'cantidad' => $trabajos->count(),
            'costo_estimado' => $trabajos->sum('costo_estimado'),
            'costo_final' => $trabajos->sum('costo_final'),
        ];

        $porEstado = $trabajos->groupBy('estado')->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'costo_estimado' => $grupo->sum('costo_estimado'),
                'costo_final' => $grupo->sum('costo_final'),
            ];
        });

        $porTipo = $trabajos->groupBy('tipo')->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'costo_estimado' => $grupo->sum('costo_estimado'),
                'costo_final' => $grupo->sum('costo_final'),
            ];
        });

        $porMes = $trabajos->groupBy(function($trabajo) {
            return Carbon::parse($trabajo->created_at)->format('Y-m');
        })->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'costo_estimado' => $grupo->sum('costo_estimado'),
                'costo_final' => $grupo->sum('costo_final'),
            ];
        });

        return [
            'trabajos' => $trabajos,
            'totales' => $totales,
            'por_estado' => $porEstado,
            'por_tipo' => $porTipo,
            'por_mes' => $porMes,
            'periodo' => [
                'inicio' => $fechaInicio,
                'fin' => $fechaFin
            ]
        ];
    }

    public function generarReportePagos($fechaInicio, $fechaFin, $metodoPago = 'todos')
    {
        $query = Pago::with(['cliente', 'factura'])
                    ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
                    ->where('estado', 'completado');

        if ($metodoPago !== 'todos') {
            $query->where('metodo_pago', $metodoPago);
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->get();

        $totales = [
            'cantidad' => $pagos->count(),
            'monto_total' => $pagos->sum('monto'),
        ];

        $porMetodo = $pagos->groupBy('metodo_pago')->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'monto_total' => $grupo->sum('monto'),
            ];
        });

        $porMes = $pagos->groupBy(function($pago) {
            return Carbon::parse($pago->fecha_pago)->format('Y-m');
        })->map(function($grupo) {
            return [
                'cantidad' => $grupo->count(),
                'monto_total' => $grupo->sum('monto'),
            ];
        });

        $clientesTop = $pagos->groupBy('cliente_id')->map(function($grupo) {
            return [
                'cliente' => $grupo->first()->cliente,
                'cantidad_pagos' => $grupo->count(),
                'monto_total' => $grupo->sum('monto'),
            ];
        })->sortByDesc('monto_total')->take(10);

        return [
            'pagos' => $pagos,
            'totales' => $totales,
            'por_metodo' => $porMetodo,
            'por_mes' => $porMes,
            'clientes_top' => $clientesTop,
            'periodo' => [
                'inicio' => $fechaInicio,
                'fin' => $fechaFin
            ]
        ];
    }

    public function generarReporteClientes($ordenarPor = 'facturacion')
    {
        $clientes = Cliente::with(['trabajos', 'facturas', 'pagos'])->get();

        $clientes = $clientes->map(function($cliente) {
            return [
                'cliente' => $cliente,
                'estadisticas' => [
                    'total_trabajos' => $cliente->trabajos->count(),
                    'trabajos_completados' => $cliente->trabajos->where('estado', 'completado')->count(),
                    'total_facturado' => $cliente->total_facturado,
                    'total_pagado' => $cliente->total_pagado,
                    'saldo_pendiente' => $cliente->total_facturado - $cliente->total_pagado,
                    'facturas_emitidas' => $cliente->facturas->where('estado', '!=', 'cancelada')->count(),
                ]
            ];
        });

        // Ordenar según el criterio
        switch ($ordenarPor) {
            case 'facturacion':
                $clientes = $clientes->sortByDesc('estadisticas.total_facturado');
                break;
            case 'trabajos':
                $clientes = $clientes->sortByDesc('estadisticas.total_trabajos');
                break;
            case 'pagos':
                $clientes = $clientes->sortByDesc('estadisticas.total_pagado');
                break;
            case 'saldo':
                $clientes = $clientes->sortByDesc('estadisticas.saldo_pendiente');
                break;
        }

        $totales = [
            'total_clientes' => $clientes->count(),
            'total_facturado' => $clientes->sum('estadisticas.total_facturado'),
            'total_pagado' => $clientes->sum('estadisticas.total_pagado'),
            'saldo_total' => $clientes->sum('estadisticas.saldo_pendiente'),
            'total_trabajos' => $clientes->sum('estadisticas.total_trabajos'),
        ];

        return [
            'clientes' => $clientes,
            'totales' => $totales,
            'ordenado_por' => $ordenarPor
        ];
    }

    public function exportarReporteFacturacion($fechaInicio, $fechaFin)
    {
        $reporte = $this->generarReporteFacturacion($fechaInicio, $fechaFin);

        // Implementar exportación a Excel o PDF
        // Por ahora retornamos el reporte para mostrar en vista
        return $reporte;
    }

    public function exportarReporteTrabajos($fechaInicio, $fechaFin, $tipo = 'todos')
    {
        $reporte = $this->generarReporteTrabajos($fechaInicio, $fechaFin, $tipo);

        // Implementar exportación a Excel o PDF
        return $reporte;
    }

    public function exportarReportePagos($fechaInicio, $fechaFin, $metodoPago = 'todos')
    {
        $reporte = $this->generarReportePagos($fechaInicio, $fechaFin, $metodoPago);

        // Implementar exportación a Excel o PDF
        return $reporte;
    }

    public function exportarReporteClientes($ordenarPor = 'facturacion')
    {
        $reporte = $this->generarReporteClientes($ordenarPor);

        // Implementar exportación a Excel o PDF
        return $reporte;
    }
}