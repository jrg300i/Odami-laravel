<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacturaController extends Controller
{
    public function index(): JsonResponse
    {
        $facturas = Factura::with(['trabajo.cliente', 'emisor'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $facturas
        ]);
    }

    public function show($id): JsonResponse
    {
        $factura = Factura::with(['trabajo.cliente', 'emisor'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $factura
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trabajo_id' => 'required|exists:trabajos,id',
            'subtotal' => 'required|numeric|min:0',
            'igv' => 'numeric|min:0',
            'total' => 'required|numeric|min:0',
            'estado_pago' => 'sometimes|in:pendiente,pagado,parcial,anulado',
            'metodo_pago' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $validated['estado_pago'] = $validated['estado_pago'] ?? 'pendiente';
        $validated['emitida_por'] = 1;

        $factura = Factura::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Factura creada exitosamente',
            'data' => $factura
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $factura = Factura::findOrFail($id);

        $validated = $request->validate([
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

    public function destroy($id): JsonResponse
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return response()->json([
            'success' => true,
            'message' => 'Factura eliminada exitosamente'
        ]);
    }

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
}
