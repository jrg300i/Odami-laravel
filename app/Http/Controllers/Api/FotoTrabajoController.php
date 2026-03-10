<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFotoTrabajoRequest;
use App\Http\Requests\UploadFotoTrabajoRequest;
use App\Models\FotoTrabajo;
use App\Models\Trabajo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador para gestión de fotos de trabajos
 *
 * Permite subir, visualizar y eliminar fotos de trabajos organizadas por etapas:
 * - Recepción: Estado inicial del artículo
 * - Proceso: Durante el trabajo
 * - Final: Trabajo terminado
 */
final class FotoTrabajoController extends Controller
{
    /**
     * Disco de almacenamiento para fotos
     */
    private const DISCO = 'photos';

    /**
     * Obtener todas las fotos de un trabajo específico agrupadas por tipo
     */
    public function index(Request $request, int $trabajoId): JsonResponse
    {
        $trabajo = Trabajo::findOrFail($trabajoId);

        $fotos = FotoTrabajo::porTrabajo($trabajoId)
            ->with('subidor')
            ->orderBy('fecha_subida', 'desc')
            ->get();

        $fotosPorTipo = FotoTrabajo::getFotosPorTrabajoAgrupadas($trabajoId);
        $conteo = FotoTrabajo::getConteoPorTipo($trabajoId);

        return response()->json([
            'success' => true,
            'data' => [
                'trabajo' => [
                    'id' => $trabajo->id,
                    'tipo_trabajo' => $trabajo->tipo_trabajo,
                    'cliente' => $trabajo->cliente->nombre ?? 'N/A',
                ],
                'fotos' => $fotos,
                'fotos_por_tipo' => [
                    'recepcion' => $fotosPorTipo[FotoTrabajo::TIPO_RECEPCION],
                    'proceso' => $fotosPorTipo[FotoTrabajo::TIPO_PROCESO],
                    'final' => $fotosPorTipo[FotoTrabajo::TIPO_FINAL],
                ],
                'conteo' => $conteo,
                'info_tipos' => FotoTrabajo::TIPOS_INFO,
            ],
        ]);
    }

    /**
     * Subir una nueva foto para un trabajo (desde base64 - cámara web)
     */
    public function store(StoreFotoTrabajoRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Extraer el base64 puro (sin el prefijo data:image/...)
        $base64String = explode(',', $validated['foto_base64'])[1] ?? $validated['foto_base64'];

        // Generar URL única para la foto
        $fotoUrl = 'fotos/' . uniqid() . '_' . time() . '.jpg';

        $foto = FotoTrabajo::create([
            'trabajo_id' => $validated['trabajo_id'],
            'foto_url' => $fotoUrl,
            'foto_base64' => $validated['foto_base64'], // Guardamos completo con el prefijo
            'tipo' => $validated['tipo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'subido_por' => 1, // TODO: Obtener del usuario autenticado
        ]);

        // Cargar relaciones para la respuesta
        $foto->load(['trabajo.cliente', 'subidor']);

        return response()->json([
            'success' => true,
            'message' => 'Foto subida exitosamente desde cámara',
            'data' => $foto,
        ], 201);
    }

    /**
     * Subir una nueva foto para un trabajo (desde archivo - almacenamiento interno)
     *
     * @param UploadFotoTrabajoRequest $request
     * @return JsonResponse
     */
    public function upload(UploadFotoTrabajoRequest $request): JsonResponse
    {
        $validated = $request->validated();

        /** @var \Illuminate\Http\UploadedFile $fotoFile */
        $fotoFile = $validated['foto'];

        // Generar nombre único para el archivo
        $nombreArchivo = uniqid() . '_' . time() . '.' . $fotoFile->getClientOriginalExtension();

        // Guardar archivo en el disco configurado
        $rutaArchivo = $fotoFile->storeAs(
            'fotos', // carpeta dentro del disco
            $nombreArchivo,
            ['disk' => self::DISCO]
        );

        // Leer el archivo y convertir a base64 para guardar en BD
        $fotoBase64 = base64_encode(Storage::disk(self::DISCO)->get($rutaArchivo));
        $mimeType = $fotoFile->getMimeType();
        $fotoBase64Completo = 'data:' . $mimeType . ';base64,' . $fotoBase64;

        $foto = FotoTrabajo::create([
            'trabajo_id' => $validated['trabajo_id'],
            'foto_url' => $rutaArchivo,
            'foto_base64' => $fotoBase64Completo,
            'tipo' => $validated['tipo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'subido_por' => 1, // TODO: Obtener del usuario autenticado
        ]);

        // Cargar relaciones para la respuesta
        $foto->load(['trabajo.cliente', 'subidor']);

        return response()->json([
            'success' => true,
            'message' => 'Foto subida exitosamente desde archivo',
            'data' => $foto,
        ], 201);
    }

    /**
     * Subir múltiples fotos de una vez
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'trabajo_id' => 'required|integer|exists:trabajos,id',
            'tipo' => 'required|in:recepcion,proceso,final',
            'fotos' => 'required|array',
            'fotos.*' => 'file|image|mimes:jpeg,jpg,png,webp|max:5120',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $trabajoId = (int) $request->trabajo_id;
        $tipo = $request->tipo;
        $descripcion = $request->descripcion;
        $fotosSubidas = [];
        $errores = [];

        foreach ($request->file('fotos') as $index => $fotoFile) {
            try {
                // Generar nombre único
                $nombreArchivo = uniqid() . '_' . time() . '_' . $index . '.' . $fotoFile->getClientOriginalExtension();

                // Guardar archivo
                $rutaArchivo = $fotoFile->storeAs(
                    'fotos',
                    $nombreArchivo,
                    ['disk' => self::DISCO]
                );

                // Convertir a base64
                $fotoBase64 = base64_encode(Storage::disk(self::DISCO)->get($rutaArchivo));
                $mimeType = $fotoFile->getMimeType();
                $fotoBase64Completo = 'data:' . $mimeType . ';base64,' . $fotoBase64;

                // Crear registro en BD
                $foto = FotoTrabajo::create([
                    'trabajo_id' => $trabajoId,
                    'foto_url' => $rutaArchivo,
                    'foto_base64' => $fotoBase64Completo,
                    'tipo' => $tipo,
                    'descripcion' => $descripcion,
                    'subido_por' => 1,
                ]);

                $fotosSubidas[] = $foto;
            } catch (\Exception $e) {
                $errores[] = [
                    'indice' => $index,
                    'nombre' => $fotoFile->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ];
            }
        }

        $statusCode = count($errores) > 0 ? 207 : 201; // 207 Multi-Status si hay errores parciales

        return response()->json([
            'success' => count($fotosSubidas) > 0,
            'message' => sprintf(
                '%d foto(s) subida(s) exitosamente. %d error(s).',
                count($fotosSubidas),
                count($errores)
            ),
            'data' => [
                'fotos_subidas' => $fotosSubidas,
                'errores' => $errores,
            ],
        ], $statusCode);
    }

    /**
     * Obtener los detalles de una foto específica
     */
    public function show(int $id): JsonResponse
    {
        $foto = FotoTrabajo::with(['trabajo.cliente', 'subidor'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $foto,
        ]);
    }

    /**
     * Eliminar una foto (solo admin)
     */
    public function destroy(int $id): JsonResponse
    {
        $foto = FotoTrabajo::findOrFail($id);
        
        // TODO: Verificar que el usuario sea admin
        // if (!auth()->user()->esAdmin()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'No tienes permisos para eliminar fotos'
        //     ], 403);
        // }

        $foto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto eliminada exitosamente',
        ]);
    }

    /**
     * Obtener estadísticas de fotos por trabajo
     */
    public function estadsticas(): JsonResponse
    {
        $totalFotos = FotoTrabajo::count();
        $fotosPorTipo = [];

        foreach (FotoTrabajo::TIPOS_VALIDOS as $tipo) {
            $fotosPorTipo[$tipo] = FotoTrabajo::porTipo($tipo)->count();
        }

        $trabajosConFotos = FotoTrabajo::select('trabajo_id')
            ->distinct()
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_fotos' => $totalFotos,
                'fotos_por_tipo' => $fotosPorTipo,
                'trabajos_con_fotos' => $trabajosConFotos,
                'info_tipos' => FotoTrabajo::TIPOS_INFO,
            ],
        ]);
    }
}
