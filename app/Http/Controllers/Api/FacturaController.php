<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacturaController extends Controller
{
    /**
     * Listar facturas con búsqueda opcional
     */
    public function index(Request $request): JsonResponse
    {
        $query = Factura::with(['trabajo.cliente', 'emisor']);

        // Búsqueda por nombre de cliente
        if ($request->has('cliente')) {
            $cliente = $request->get('cliente');
            $query->whereHas('trabajo.cliente', function ($q) use ($cliente) {
                $q->where('nombre', 'LIKE', "%{$cliente}%")
                  ->orWhere('apellido', 'LIKE', "%{$cliente}%")
                  ->orWhereRaw("CONCAT(nombre, ' ', apellido) LIKE ?", ["%{$cliente}%"]);
            });
        }

        // Búsqueda por número de factura
        if ($request->has('numero')) {
            $query->where('numero_factura', 'LIKE', "%{$request->get('numero')}%");
        }

        // Búsqueda por fecha de entrega
        if ($request->has('fecha_entrega')) {
            $fecha = $request->get('fecha_entrega');
            $query->whereHas('trabajo', function ($q) use ($fecha) {
                $q->whereDate('fecha_entrega', $fecha);
            });
        }

        // Búsqueda por fecha de recibido
        if ($request->has('fecha_recibido')) {
            $fecha = $request->get('fecha_recibido');
            $query->whereHas('trabajo', function ($q) use ($fecha) {
                $q->whereDate('fecha_recibido', $fecha);
            });
        }

        // Filtrar por tipo
        if ($request->has('tipo')) {
            $query->where('tipo', $request->get('tipo'));
        }

        // Filtrar por estado de pago
        if ($request->has('estado_pago')) {
            $query->where('estado_pago', $request->get('estado_pago'));
        }

        $facturas = $query->orderBy('fecha_emision', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $facturas->map(function ($factura) {
                return [
                    'id' => $factura->id,
                    'numero_factura' => $factura->numero_factura,
                    'tipo' => $factura->tipo,
                    'cliente_id' => $factura->cliente_id,
                    'nombre_cliente' => $factura->nombre_cliente,
                    'trabajo' => $factura->tipo_trabajo,
                    'fecha_recibido' => $factura->fecha_recibido,
                    'fecha_entrega' => $factura->fecha_entrega,
                    'subtotal' => $factura->subtotal,
                    'igv' => $factura->igv,
                    'total' => $factura->total,
                    'estado_pago' => $factura->estado_pago,
                    'fecha_emision' => $factura->fecha_emision,
                    'metodo_pago' => $factura->metodo_pago,
                    'observaciones' => $factura->observaciones,
                    'trabajo_detalle' => $factura->trabajo,
                ];
            })
        ]);
    }

    /**
     * Obtener una factura específica
     */
    public function show($id): JsonResponse
    {
        $factura = Factura::with(['trabajo.cliente', 'emisor'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $factura->id,
                'numero_factura' => $factura->numero_factura,
                'tipo' => $factura->tipo,
                'cliente_id' => $factura->cliente_id,
                'nombre_cliente' => $factura->nombre_cliente,
                'trabajo' => $factura->tipo_trabajo,
                'fecha_recibido' => $factura->fecha_recibido,
                'fecha_entrega' => $factura->fecha_entrega,
                'subtotal' => $factura->subtotal,
                'igv' => $factura->igv,
                'total' => $factura->total,
                'estado_pago' => $factura->estado_pago,
                'fecha_emision' => $factura->fecha_emision,
                'metodo_pago' => $factura->metodo_pago,
                'observaciones' => $factura->observaciones,
                'trabajo_detalle' => $factura->trabajo,
            ]
        ]);
    }

    /**
     * Crear una nueva factura
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trabajo_id' => 'required|exists:trabajos,id',
            'numero_factura' => 'required|string|unique:facturas,numero_factura',
            'tipo' => 'sometimes|in:original,copia',
            'subtotal' => 'required|numeric|min:0',
            'igv' => 'numeric|min:0',
            'total' => 'required|numeric|min:0',
            'estado_pago' => 'sometimes|in:pendiente,pagado,parcial,anulado',
            'metodo_pago' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $validated['tipo'] = $validated['tipo'] ?? 'original';
        $validated['estado_pago'] = $validated['estado_pago'] ?? 'pendiente';
        $validated['emitida_por'] = 1;

        $factura = Factura::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Factura creada exitosamente',
            'data' => $factura
        ], 201);
    }

    /**
     * Actualizar una factura
     */
    public function update(Request $request, $id): JsonResponse
    {
        $factura = Factura::findOrFail($id);

        $validated = $request->validate([
            'numero_factura' => 'sometimes|required|string|unique:facturas,numero_factura,' . $id,
            'tipo' => 'sometimes|in:original,copia',
            'subtotal' => 'sometimes|required|numeric|min:0',
            'igv' => 'sometimes|numeric|min:0',
            'total' => 'sometimes|required|numeric|min:0',
            'estado_pago' => 'sometimes|in:pendiente,pagado,parcial,anulado',
            'metodo_pago' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
            'fecha_pago' => 'nullable|date',
        ]);

        if (isset($validated['estado_pago']) && $validated['estado_pago'] === 'pagado' && !isset($validated['fecha_pago'])) {
            $validated['fecha_pago'] = now();
        }

        $factura->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Factura actualizada exitosamente',
            'data' => $factura->fresh()
        ]);
    }

    /**
     * Eliminar una factura
     */
    public function destroy($id): JsonResponse
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return response()->json([
            'success' => true,
            'message' => 'Factura eliminada exitosamente'
        ]);
    }

    /**
     * Obtener facturas pendientes
     */
    public function pendientes(): JsonResponse
    {
        $facturas = Factura::with(['trabajo.cliente', 'emisor'])
            ->where('estado_pago', '!=', 'pagado')
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $facturas
        ]);
    }

    /**
     * Obtener facturas por trabajo
     */
    public function porTrabajo($trabajoId): JsonResponse
    {
        $facturas = Factura::with(['trabajo.cliente', 'emisor'])
            ->where('trabajo_id', $trabajoId)
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $facturas
        ]);
    }

    /**
     * Obtener facturas por cliente
     */
    public function porCliente($clienteId): JsonResponse
    {
        $facturas = Factura::with(['trabajo.cliente', 'emisor'])
            ->whereHas('trabajo', function ($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            })
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $facturas
        ]);
    }

    /**
     * Generar siguiente número de factura
     */
    public function siguienteNumero(): JsonResponse
    {
        $ultimaFactura = Factura::orderBy('numero_factura', 'desc')->first();
        
        if (!$ultimaFactura) {
            $siguiente = 'F001-00000001';
        } else {
            $partes = explode('-', $ultimaFactura->numero_factura);
            if (count($partes) === 2) {
                $serie = $partes[0];
                $correlativo = (int)$partes[1] + 1;
                $siguiente = $serie . '-' . str_pad($correlativo, 8, '0', STR_PAD_LEFT);
            } else {
                $siguiente = 'F001-00000001';
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'siguiente_numero' => $siguiente
            ]
        ]);
    }
}
