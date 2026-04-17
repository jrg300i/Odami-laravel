<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Trabajo;
use App\Models\ControlFactura;
use App\Models\Clausula;
use App\Http\Requests\FacturaRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with(['cliente', 'trabajo']);

        // Búsqueda
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero_completo', 'ILIKE', "%{$request->search}%")
                  ->orWhere('concepto', 'ILIKE', "%{$request->search}%")
                  ->orWhereHas('cliente', function($q) use ($request) {
                      $q->where('nombre', 'ILIKE', "%{$request->search}%")
                        ->orWhere('apellido', 'ILIKE', "%{$request->search}%");
                  });
            });
        }

        // Filtros
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('serie')) {
            $query->where('serie', $request->serie);
        }

        $facturas = $query->orderBy('fecha_emision', 'desc')
                         ->paginate(15);

        $estadisticas = [
            'total' => Factura::count(),
            'emitidas' => Factura::where('estado', 'emitida')->count(),
            'pagadas' => Factura::where('estado', 'pagada')->count(),
            'vencidas' => Factura::where('estado', 'emitida')
                                ->where('fecha_vencimiento', '<', now())
                                ->count(),
        ];

        return view('facturas.index', compact('facturas', 'estadisticas'));
    }

    public function create()
    {
        $clientes = Cliente::activo()->get();
        $trabajos = Trabajo::whereIn('estado', ['en_proceso', 'completado'])
                          ->whereDoesntHave('facturas', function($query) {
                              $query->where('estado', '!=', 'cancelada');
                          })
                          ->get();
        $series = ControlFactura::where('activo', true)->get();
        $clausulas = Clausula::activas()->ordenadas()->get();

        return view('facturas.create', compact('clientes', 'trabajos', 'series', 'clausulas'));
    }

    public function store(FacturaRequest $request)
    {
        $data = $request->validated();
        
        // Obtener siguiente número de factura
        $controlFactura = ControlFactura::where('serie', $data['serie'])->first();
        $numero = $controlFactura->obtenerSiguienteNumero();
        $data['numero'] = $numero;
        $data['numero_completo'] = $controlFactura->generarNumeroCompleto($numero);
        $data['fecha_emision'] = now();

        // Calcular totales desde líneas
        $subtotal = 0;
        if ($request->has('lineas')) {
            foreach ($request->lineas as $linea) {
                if (!empty($linea['descripcion']) && isset($linea['cantidad'], $linea['precio'])) {
                    $subtotal += $linea['cantidad'] * $linea['precio'];
                }
            }
        }
        $data['subtotal'] = $subtotal;
        $data['total'] = $data['subtotal'] * (1 + ($data['iva'] ?? 21) / 100);

        $factura = Factura::create($data);

        // Agregar líneas de factura si se proporcionaron
        if ($request->has('lineas')) {
            foreach ($request->lineas as $linea) {
                if (!empty($linea['descripcion']) && $linea['cantidad'] > 0) {
                    $factura->agregarLinea(
                        $linea['descripcion'],
                        $linea['cantidad'],
                        $linea['precio'],
                        $data['iva']
                    );
                }
            }
        }

        return redirect()->route('facturas.show', $factura)
                        ->with('success', 'Factura creada exitosamente.');
    }

    public function show(Factura $factura)
    {
        $factura->load(['cliente', 'trabajo', 'pagos']);
        $clausulas = $factura->incluir_clausulas ? Clausula::activas()->ordenadas()->get() : collect();

        return view('facturas.show', compact('factura', 'clausulas'));
    }

    public function edit(Factura $factura)
    {
        if ($factura->estado != 'borrador') {
            return redirect()->back()
                            ->with('error', 'Solo se pueden editar facturas en estado borrador.');
        }

        $clientes = Cliente::activo()->get();
        $trabajos = Trabajo::whereIn('estado', ['en_proceso', 'completado'])->get();
        $series = ControlFactura::where('activo', true)->get();
        $clausulas = Clausula::activas()->ordenadas()->get();

        return view('facturas.edit', compact('factura', 'clientes', 'trabajos', 'series', 'clausulas'));
    }

    public function update(FacturaRequest $request, Factura $factura)
    {
        if ($factura->estado != 'borrador') {
            return redirect()->back()
                            ->with('error', 'Solo se pueden editar facturas en estado borrador.');
        }

        $factura->update($request->validated());

        // Actualizar líneas de factura
        if ($request->has('lineas')) {
            $factura->update(['lineas' => []]); // Limpiar líneas existentes
            foreach ($request->lineas as $linea) {
                if (!empty($linea['descripcion']) && $linea['cantidad'] > 0) {
                    $factura->agregarLinea(
                        $linea['descripcion'],
                        $linea['cantidad'],
                        $linea['precio'],
                        $factura->iva
                    );
                }
            }
        }

        return redirect()->route('facturas.show', $factura)
                        ->with('success', 'Factura actualizada exitosamente.');
    }

    public function destroy(Factura $factura)
    {
        if ($factura->estado != 'borrador') {
            return redirect()->back()
                            ->with('error', 'Solo se pueden eliminar facturas en estado borrador.');
        }

        $factura->delete();

        return redirect()->route('facturas.index')
                        ->with('success', 'Factura eliminada exitosamente.');
    }

    public function emitir(Factura $factura)
    {
        if ($factura->estado != 'borrador') {
            return redirect()->back()
                            ->with('error', 'La factura ya ha sido emitida.');
        }

        $factura->update(['estado' => 'emitida']);

        return redirect()->back()
                        ->with('success', 'Factura emitida exitosamente.');
    }

    public function cancelar(Factura $factura)
    {
        if ($factura->estado == 'cancelada') {
            return redirect()->back()
                            ->with('error', 'La factura ya está cancelada.');
        }

        $factura->update(['estado' => 'cancelada']);

        return redirect()->back()
                        ->with('success', 'Factura cancelada exitosamente.');
    }

    public function pdf(Factura $factura)
    {
        $factura->load(['cliente', 'trabajo']);
        $clausulas = $factura->incluir_clausulas ? Clausula::activas()->ordenadas()->get() : collect();

        $pdf = PDF::loadView('facturas.pdf.factura', compact('factura', 'clausulas'));
        
        return $pdf->download("factura-{$factura->numero_completo}.pdf");
    }

    public function enviarEmail(Factura $factura)
    {
        // Implementar envío de email
        // Por ahora solo redirigir con mensaje
        return redirect()->back()
                        ->with('success', 'Función de envío por email en desarrollo.');
    }
}