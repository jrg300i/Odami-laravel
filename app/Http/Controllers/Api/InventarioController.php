<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\InventarioMovimiento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventarioController extends Controller
{
    public function index(): JsonResponse
    {
        $inventario = Inventario::with(['creador', 'modificador'])
            ->orderBy('nombre', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $inventario
        ]);
    }

    public function show($id): JsonResponse
    {
        $item = Inventario::with(['movimientos', 'creador', 'modificador'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'categoria' => 'required|in:telas,espumas,hilos,botones,accesorios,otros',
            'stock_actual' => 'integer|min:0',
            'stock_minimo' => 'integer|min:0',
            'stock_maximo' => 'nullable|integer',
            'unidad' => 'sometimes|string|max:20',
            'precio_unitario' => 'numeric|min:0',
            'proveedor' => 'nullable|string|max:150',
            'contacto_proveedor' => 'nullable|string|max:100',
            'ubicacion' => 'nullable|string|max:50',
        ]);

        $validated['stock_actual'] = $validated['stock_actual'] ?? 0;
        $validated['stock_minimo'] = $validated['stock_minimo'] ?? 5;
        $validated['unidad'] = $validated['unidad'] ?? 'unidad';
        $validated['precio_unitario'] = $validated['precio_unitario'] ?? 0;
        $validated['creado_por'] = 1;

        $item = Inventario::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item de inventario creado exitosamente',
            'data' => $item
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $item = Inventario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'categoria' => 'sometimes|required|in:telas,espumas,hilos,botones,accesorios,otros',
            'stock_actual' => 'sometimes|integer|min:0',
            'stock_minimo' => 'sometimes|integer|min:0',
            'stock_maximo' => 'nullable|integer',
            'unidad' => 'sometimes|string|max:20',
            'precio_unitario' => 'sometimes|numeric|min:0',
            'proveedor' => 'nullable|string|max:150',
            'contacto_proveedor' => 'nullable|string|max:100',
            'ubicacion' => 'nullable|string|max:50',
        ]);

        $validated['modificado_por'] = 1;
        $validated['fecha_actualizacion'] = now();

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item de inventario actualizado exitosamente',
            'data' => $item->fresh()
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $item = Inventario::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item de inventario eliminado exitosamente'
        ]);
    }

    public function stockBajo(): JsonResponse
    {
        $items = Inventario::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderByRaw('stock_minimo - stock_actual DESC')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function movimientos($itemId): JsonResponse
    {
        $movimientos = InventarioMovimiento::with(['item', 'trabajo', 'realizador'])
            ->where('item_id', $itemId)
            ->orderBy('fecha_movimiento', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $movimientos
        ]);
    }

    public function registrarMovimiento(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:inventario,id',
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer',
            'motivo' => 'required|string',
            'trabajo_id' => 'nullable|exists:trabajos,id',
        ]);

        $item = Inventario::findOrFail($validated['item_id']);
        $stockAnterior = $item->stock_actual;

        if ($validated['tipo_movimiento'] === 'entrada') {
            $item->stock_actual += $validated['cantidad'];
        } elseif ($validated['tipo_movimiento'] === 'salida') {
            if ($item->stock_actual < $validated['cantidad']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente'
                ], 400);
            }
            $item->stock_actual -= $validated['cantidad'];
        } else {
            $item->stock_actual = $validated['cantidad'];
        }

        $item->save();

        $movimiento = InventarioMovimiento::create([
            'item_id' => $validated['item_id'],
            'tipo_movimiento' => $validated['tipo_movimiento'],
            'cantidad' => $validated['cantidad'],
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $item->stock_actual,
            'motivo' => $validated['motivo'],
            'trabajo_id' => $validated['trabajo_id'] ?? null,
            'realizado_por' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Movimiento registrado exitosamente',
            'data' => $movimiento
        ], 201);
    }

    public function porCategoria($categoria): JsonResponse
    {
        $items = Inventario::where('categoria', $categoria)
            ->orderBy('nombre', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}
