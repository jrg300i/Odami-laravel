<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Trabajo;
use App\Models\Factura;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $estadisticas = [
            'total_clientes' => Cliente::count(),
            'trabajos_activos' => Trabajo::whereIn('estado', ['presupuesto', 'en_proceso'])->count(),
            'facturas_emitidas' => Factura::where('estado', 'emitida')->count(),
            'ingresos_mes' => Pago::where('estado', 'completado')
                ->whereMonth('fecha_pago', now()->month)
                ->sum('monto'),
        ];

        $trabajos_recientes = Trabajo::with('cliente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $facturas_pendientes = Factura::with('cliente')
            ->where('estado', 'emitida')
            ->where('fecha_vencimiento', '<', now()->addDays(7))
            ->orderBy('fecha_vencimiento')
            ->take(5)
            ->get();

        $ingresos_por_mes = Pago::select(
                DB::raw('EXTRACT(MONTH FROM fecha_pago) as mes'),
                DB::raw('SUM(monto) as total')
            )
            ->where('estado', 'completado')
            ->whereYear('fecha_pago', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return view('admin.dashboard', compact(
            'estadisticas',
            'trabajos_recientes',
            'facturas_pendientes',
            'ingresos_por_mes'
        ));
    }
}