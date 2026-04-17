<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Trabajo;
use App\Models\Pago;
use App\Models\Cliente;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function facturacion(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));

        $reporte = $this->reporteService->generarReporteFacturacion($fechaInicio, $fechaFin);

        return view('reportes.facturacion', compact('reporte', 'fechaInicio', 'fechaFin'));
    }

    public function trabajos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $tipo = $request->get('tipo', 'todos');

        $reporte = $this->reporteService->generarReporteTrabajos($fechaInicio, $fechaFin, $tipo);

        return view('reportes.trabajos', compact('reporte', 'fechaInicio', 'fechaFin', 'tipo'));
    }

    public function pagos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $metodoPago = $request->get('metodo_pago', 'todos');

        $reporte = $this->reporteService->generarReportePagos($fechaInicio, $fechaFin, $metodoPago);

        return view('reportes.pagos', compact('reporte', 'fechaInicio', 'fechaFin', 'metodoPago'));
    }

    public function clientes(Request $request)
    {
        $ordenarPor = $request->get('ordenar_por', 'facturacion');

        $reporte = $this->reporteService->generarReporteClientes($ordenarPor);

        return view('reportes.clientes', compact('reporte', 'ordenarPor'));
    }

    public function exportarFacturacion(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));

        return $this->reporteService->exportarReporteFacturacion($fechaInicio, $fechaFin);
    }

    public function exportarTrabajos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $tipo = $request->get('tipo', 'todos');

        return $this->reporteService->exportarReporteTrabajos($fechaInicio, $fechaFin, $tipo);
    }

    public function exportarPagos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $metodoPago = $request->get('metodo_pago', 'todos');

        return $this->reporteService->exportarReportePagos($fechaInicio, $fechaFin, $metodoPago);
    }

    public function exportarClientes(Request $request)
    {
        $ordenarPor = $request->get('ordenar_por', 'facturacion');

        return $this->reporteService->exportarReporteClientes($ordenarPor);
    }
}