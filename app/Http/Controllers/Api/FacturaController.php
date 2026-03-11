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
        $query = Factura::with(['trabajo.cliente', 'emisor', 'condiciones']);

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
                    'condiciones' => $factura->condiciones,
                ];
            })
        ]);
    }

    /**
     * Obtener una factura específica
     */
    public function show($id): JsonResponse
    {
        $factura = Factura::with(['trabajo.cliente', 'emisor', 'condiciones'])->findOrFail($id);

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
                'condiciones' => $factura->condiciones,
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
            // Datos del cliente
            'cliente_nombre' => 'nullable|string|max:100',
            'cliente_apellido' => 'nullable|string|max:100',
            'cliente_documento' => 'nullable|string|max:20',
            'cliente_direccion' => 'nullable|string|max:200',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_email' => 'nullable|email|max:100',
            // Datos del trabajo
            'trabajo_tipo' => 'nullable|string|max:100',
            'trabajo_descripcion' => 'nullable|string',
            'trabajo_fecha_recibido' => 'nullable|date',
            'trabajo_fecha_entrega' => 'nullable|date',
            // Datos legales
            'empresa_ruc' => 'nullable|string|max:20',
            'empresa_razon_social' => 'nullable|string|max:200',
            'empresa_direccion' => 'nullable|string|max:200',
            'empresa_telefono' => 'nullable|string|max:20',
            'empresa_email' => 'nullable|email|max:100',
            'representante_nombre' => 'nullable|string|max:100',
            'representante_dni' => 'nullable|string|max:20',
            'representante_cargo' => 'nullable|string|max:100',
            'firma_base64' => 'nullable|string',
            'sello_base64' => 'nullable|string',
            'notas_legales' => 'nullable|string',
            // Condiciones
            'condiciones' => 'nullable|array',
            'condiciones.*' => 'exists:condiciones_trabajo,id',
        ]);

        $validated['tipo'] = $validated['tipo'] ?? 'original';
        $validated['estado_pago'] = $validated['estado_pago'] ?? 'pendiente';
        $validated['emitida_por'] = 1;

        // Si no se envían datos del cliente, obtenerlos del trabajo
        if (empty($validated['cliente_nombre']) && $request->has('trabajo_id')) {
            $trabajo = \App\Models\Trabajo::with('cliente')->find($request->trabajo_id);
            if ($trabajo && $trabajo->cliente) {
                $validated['cliente_nombre'] = $trabajo->cliente->nombre;
                $validated['cliente_apellido'] = $trabajo->cliente->apellido;
                $validated['cliente_documento'] = $trabajo->cliente->documento ?? null;
                $validated['cliente_direccion'] = $trabajo->cliente->direccion;
                $validated['cliente_telefono'] = $trabajo->cliente->telefono;
                $validated['cliente_email'] = $trabajo->cliente->email;
            }
        }

        // Si no se envían datos del trabajo, obtenerlos
        if (empty($validated['trabajo_tipo']) && $request->has('trabajo_id')) {
            $trabajo = \App\Models\Trabajo::find($request->trabajo_id);
            if ($trabajo) {
                $validated['trabajo_tipo'] = $trabajo->tipo_trabajo;
                $validated['trabajo_descripcion'] = $trabajo->descripcion;
                $validated['trabajo_fecha_recibido'] = $trabajo->fecha_recibido;
                $validated['trabajo_fecha_entrega'] = $trabajo->fecha_entrega;
            }
        }

        $condiciones = $validated['condiciones'] ?? [];
        unset($validated['condiciones']);

        $factura = Factura::create($validated);

        // Sincronizar condiciones
        if (!empty($condiciones)) {
            $factura->condiciones()->syncWithPivotValues(
                $condiciones,
                ['orden' => \DB::raw('ROW_NUMBER() OVER (ORDER BY id)')]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Factura creada exitosamente',
            'data' => $factura->load('condiciones')
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
            // Datos del cliente
            'cliente_nombre' => 'nullable|string|max:100',
            'cliente_apellido' => 'nullable|string|max:100',
            'cliente_documento' => 'nullable|string|max:20',
            'cliente_direccion' => 'nullable|string|max:200',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_email' => 'nullable|email|max:100',
            // Datos del trabajo
            'trabajo_tipo' => 'nullable|string|max:100',
            'trabajo_descripcion' => 'nullable|string',
            'trabajo_fecha_recibido' => 'nullable|date',
            'trabajo_fecha_entrega' => 'nullable|date',
            // Datos legales
            'empresa_ruc' => 'nullable|string|max:20',
            'empresa_razon_social' => 'nullable|string|max:200',
            'empresa_direccion' => 'nullable|string|max:200',
            'empresa_telefono' => 'nullable|string|max:20',
            'empresa_email' => 'nullable|email|max:100',
            'representante_nombre' => 'nullable|string|max:100',
            'representante_dni' => 'nullable|string|max:20',
            'representante_cargo' => 'nullable|string|max:100',
            'firma_base64' => 'nullable|string',
            'sello_base64' => 'nullable|string',
            'notas_legales' => 'nullable|string',
            // Condiciones
            'condiciones' => 'nullable|array',
            'condiciones.*' => 'exists:condiciones_trabajo,id',
        ]);

        if (isset($validated['estado_pago']) && $validated['estado_pago'] === 'pagado' && !isset($validated['fecha_pago'])) {
            $validated['fecha_pago'] = now();
        }

        $condiciones = $validated['condiciones'] ?? [];
        unset($validated['condiciones']);

        $factura->update($validated);

        // Sincronizar condiciones
        $factura->condiciones()->sync($condiciones);

        return response()->json([
            'success' => true,
            'message' => 'Factura actualizada exitosamente',
            'data' => $factura->load('condiciones')
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
