<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EntregaController extends Controller
{
    public function index(): JsonResponse
    {
        $entregas = Entrega::with(['trabajo.cliente', 'creador'])
            ->orderBy('fecha_entrega', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entregas
        ]);
    }

    public function show($id): JsonResponse
    {
        $entrega = Entrega::with(['trabajo.cliente', 'creador'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $entrega
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trabajo_id' => 'required|exists:trabajos,id',
            'fecha_entrega' => 'required|date',
            'estado' => 'sometimes|in:programada,completada,cancelada',
            'notas' => 'nullable|string',
        ]);

        $validated['estado'] = $validated['estado'] ?? 'programada';
        $validated['creado_por'] = 1;

        $entrega = Entrega::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Entrega programada exitosamente',
            'data' => $entrega
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $entrega = Entrega::findOrFail($id);

        $validated = $request->validate([
            'fecha_entrega' => 'sometimes|required|date',
            'estado' => 'sometimes|in:programada,completada,cancelada',
            'notas' => 'nullable|string',
            'recordatorio_enviado' => 'boolean',
        ]);

        $entrega->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Entrega actualizada exitosamente',
            'data' => $entrega->fresh()
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $entrega = Entrega::findOrFail($id);
        $entrega->delete();

        return response()->json([
            'success' => true,
            'message' => 'Entrega eliminada exitosamente'
        ]);
    }

    public function hoy(): JsonResponse
    {
        $entregas = Entrega::with(['trabajo.cliente'])
            ->whereDate('fecha_entrega', date('Y-m-d'))
            ->orderBy('fecha_entrega', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entregas
        ]);
    }

    public function proximas(): JsonResponse
    {
        $entregas = Entrega::with(['trabajo.cliente'])
            ->where('fecha_entrega', '>=', now())
            ->where('estado', 'programada')
            ->orderBy('fecha_entrega', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entregas
        ]);
    }
}
