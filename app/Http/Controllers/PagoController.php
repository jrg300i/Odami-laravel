<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Factura;
use App\Models\Cliente;
use App\Http\Requests\PagoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with(['factura', 'cliente']);

        // Búsqueda
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('referencia', 'ILIKE', "%{$request->search}%")
                  ->orWhereHas('cliente', function($q) use ($request) {
                      $q->where('nombre', 'ILIKE', "%{$request->search}%")
                        ->orWhere('apellido', 'ILIKE', "%{$request->search}%");
                  })
                  ->orWhereHas('factura', function($q) use ($request) {
                      $q->where('numero_completo', 'ILIKE', "%{$request->search}%");
                  });
            });
        }

        // Filtros
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')
                      ->paginate(15);

        $estadisticas = [
            'total' => Pago::count(),
            'completados' => Pago::completados()->sum('monto'),
            'pendientes' => Pago::where('estado', 'pendiente')->sum('monto'),
        ];

        return view('pagos.index', compact('pagos', 'estadisticas'));
    }

    public function create()
    {
        $facturas = Factura::where('estado', 'emitida')
                          ->whereDoesntHave('pagos', function($query) {
                              $query->where('estado', 'completado');
                          }, '>=', DB::raw('facturas.total'))
                          ->get();
        $clientes = Cliente::activo()->get();

        return view('pagos.create', compact('facturas', 'clientes'));
    }

    public function store(PagoRequest $request)
    {
        $data = $request->validated();

        // Procesar comprobante si se subió
        if ($request->hasFile('comprobante')) {
            $comprobante = $request->file('comprobante');
            $ruta = $comprobante->store('comprobantes/pagos', 'public');
            $data['comprobante_path'] = $ruta;
        }

        $pago = Pago::create($data);

        // Si el pago se marca como completado, actualizar estado
        if ($data['estado'] == 'completado') {
            $pago->marcarComoCompletado();
        }

        return redirect()->route('pagos.show', $pago)
                        ->with('success', 'Pago registrado exitosamente.');
    }

    public function show(Pago $pago)
    {
        $pago->load(['factura', 'cliente', 'factura.trabajo']);
        
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        if ($pago->estado == 'completado') {
            return redirect()->back()
                            ->with('error', 'No se puede editar un pago completado.');
        }

        $facturas = Factura::where('estado', 'emitida')->get();
        $clientes = Cliente::activo()->get();

        return view('pagos.edit', compact('pago', 'facturas', 'clientes'));
    }

    public function update(PagoRequest $request, Pago $pago)
    {
        if ($pago->estado == 'completado') {
            return redirect()->back()
                            ->with('error', 'No se puede editar un pago completado.');
        }

        $data = $request->validated();

        // Procesar nuevo comprobante si se subió
        if ($request->hasFile('comprobante')) {
            // Eliminar comprobante anterior si existe
            if ($pago->comprobante_path) {
                Storage::delete('public/' . $pago->comprobante_path);
            }

            $comprobante = $request->file('comprobante');
            $ruta = $comprobante->store('comprobantes/pagos', 'public');
            $data['comprobante_path'] = $ruta;
        }

        $pago->update($data);

        // Si el pago se marca como completado, actualizar estado
        if ($data['estado'] == 'completado') {
            $pago->marcarComoCompletado();
        }

        return redirect()->route('pagos.show', $pago)
                        ->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy(Pago $pago)
    {
        if ($pago->estado == 'completado') {
            return redirect()->back()
                            ->with('error', 'No se puede eliminar un pago completado.');
        }

        // Eliminar comprobante si existe
        if ($pago->comprobante_path) {
            Storage::delete('public/' . $pago->comprobante_path);
        }

        $pago->delete();

        return redirect()->route('pagos.index')
                        ->with('success', 'Pago eliminado exitosamente.');
    }

    public function marcarCompletado(Pago $pago)
    {
        if ($pago->estado == 'completado') {
            return redirect()->back()
                            ->with('error', 'El pago ya está completado.');
        }

        $pago->marcarComoCompletado();

        return redirect()->back()
                        ->with('success', 'Pago marcado como completado.');
    }

    public function descargarComprobante(Pago $pago)
    {
        if (!$pago->comprobante_path) {
            return redirect()->back()
                            ->with('error', 'No hay comprobante disponible para descargar.');
        }

        return Storage::download('public/' . $pago->comprobante_path);
    }
}