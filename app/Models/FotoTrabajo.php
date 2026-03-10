<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo FotoTrabajo
 * 
 * Representa las fotos de trabajos organizadas por etapas:
 * - recepcion: Estado inicial del artículo cuando llega
 * - proceso: Fotos durante el trabajo (múltiples fotos permitidas)
 * - final: Foto del trabajo terminado para entrega
 */
final class FotoTrabajo extends Model
{
    /**
     * Tipos de foto válidos por etapa
     */
    public const TIPO_RECEPCION = 'recepcion';
    public const TIPO_PROCESO = 'proceso';
    public const TIPO_FINAL = 'final';

    public const TIPOS_VALIDOS = [
        self::TIPO_RECEPCION,
        self::TIPO_PROCESO,
        self::TIPO_FINAL,
    ];

    /**
     * Iconos y colores para cada tipo de foto
     */
    public const TIPOS_INFO = [
        self::TIPO_RECEPCION => [
            'icono' => '📥',
            'label' => 'Recepción',
            'color' => '#2196F3',
            'descripcion' => 'Estado inicial del artículo',
        ],
        self::TIPO_PROCESO => [
            'icono' => '🔨',
            'label' => 'Proceso',
            'color' => '#FF9800',
            'descripcion' => 'Durante el trabajo',
        ],
        self::TIPO_FINAL => [
            'icono' => '✨',
            'label' => 'Final',
            'color' => '#4CAF50',
            'descripcion' => 'Trabajo terminado',
        ],
    ];

    protected $table = 'fotos_trabajo';

    protected $primaryKey = 'id';

    protected $fillable = [
        'trabajo_id',
        'foto_url',
        'foto_base64',
        'tipo',
        'descripcion',
        'subido_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_subida' => 'datetime',
        ];
    }

    /**
     * Relación con el trabajo
     */
    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class);
    }

    /**
     * Relación con el usuario que subió la foto
     */
    public function subidor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'subido_por');
    }

    /**
     * Scope para filtrar por tipo de foto
     */
    public function scopePorTipo($query, string $tipo): void
    {
        $query->where('tipo', $tipo);
    }

    /**
     * Scope para filtrar por trabajo
     */
    public function scopePorTrabajo($query, int $trabajoId): void
    {
        $query->where('trabajo_id', $trabajoId);
    }

    /**
     * Verificar si el tipo es válido
     */
    public static function esTipoValido(string $tipo): bool
    {
        return in_array($tipo, self::TIPOS_VALIDOS, true);
    }

    /**
     * Obtener información del tipo de foto
     */
    public static function getTipoInfo(string $tipo): ?array
    {
        return self::TIPOS_INFO[$tipo] ?? null;
    }

    /**
     * Obtener todas las fotos de un trabajo agrupadas por tipo
     */
    public static function getFotosPorTrabajoAgrupadas(int $trabajoId): array
    {
        $fotos = self::porTrabajo($trabajoId)->get();

        return [
            self::TIPO_RECEPCION => $fotos->where('tipo', self::TIPO_RECEPCION)->values(),
            self::TIPO_PROCESO => $fotos->where('tipo', self::TIPO_PROCESO)->values(),
            self::TIPO_FINAL => $fotos->where('tipo', self::TIPO_FINAL)->values(),
        ];
    }

    /**
     * Obtener conteo de fotos por tipo para un trabajo
     */
    public static function getConteoPorTipo(int $trabajoId): array
    {
        $fotos = self::porTrabajo($trabajoId)->get();

        return [
            self::TIPO_RECEPCION => $fotos->where('tipo', self::TIPO_RECEPCION)->count(),
            self::TIPO_PROCESO => $fotos->where('tipo', self::TIPO_PROCESO)->count(),
            self::TIPO_FINAL => $fotos->where('tipo', self::TIPO_FINAL)->count(),
            'total' => $fotos->count(),
        ];
    }
}
