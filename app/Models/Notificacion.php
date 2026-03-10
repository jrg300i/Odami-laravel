<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'tipo',
        'titulo',
        'mensaje',
        'prioridad',
        'icono',
        'datos_adicionales',
        'trabajo_id',
        'usuario_id',
        'leida',
        'fecha_leida',
    ];

    protected $casts = [
        'datos_adicionales' => 'array',
        'leida' => 'boolean',
        'fecha_leida' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Constantes de tipos de notificación
     */
    const TIPO_ENTREGA_PROXIMA = 'entrega_proxima';
    const TIPO_ENTREGA_HOY = 'entrega_hoy';
    const TIPO_ENTREGA_RETRASADA = 'entrega_retrasada';
    const TIPO_TRABAJO_CREADO = 'trabajo_creado';
    const TIPO_TRABAJO_COMPLETADO = 'trabajo_completado';
    const TIPO_STOCK_BAJO = 'stock_bajo';

    /**
     * Constantes de prioridad
     */
    const PRIORIDAD_BAJA = 'low';
    const PRIORIDAD_NORMAL = 'normal';
    const PRIORIDAD_ALTA = 'high';
    const PRIORIDAD_URGENTE = 'urgent';

    /**
     * Iconos por tipo
     */
    public static function getIconoPorTipo(string $tipo): string
    {
        $iconos = [
            self::TIPO_ENTREGA_PROXIMA => 'fa-clock',
            self::TIPO_ENTREGA_HOY => 'fa-calendar-day',
            self::TIPO_ENTREGA_RETRASADA => 'fa-exclamation-triangle',
            self::TIPO_TRABAJO_CREADO => 'fa-briefcase',
            self::TIPO_TRABAJO_COMPLETADO => 'fa-check-circle',
            self::TIPO_STOCK_BAJO => 'fa-exclamation-triangle',
        ];

        return $iconos[$tipo] ?? 'fa-info-circle';
    }

    /**
     * Prioridad por tipo
     */
    public static function getPrioridadPorTipo(string $tipo): string
    {
        $prioridades = [
            self::TIPO_ENTREGA_PROXIMA => self::PRIORIDAD_ALTA,
            self::TIPO_ENTREGA_HOY => self::PRIORIDAD_URGENTE,
            self::TIPO_ENTREGA_RETRASADA => self::PRIORIDAD_URGENTE,
            self::TIPO_TRABAJO_CREADO => self::PRIORIDAD_NORMAL,
            self::TIPO_TRABAJO_COMPLETADO => self::PRIORIDAD_NORMAL,
            self::TIPO_STOCK_BAJO => self::PRIORIDAD_ALTA,
        ];

        return $prioridades[$tipo] ?? self::PRIORIDAD_NORMAL;
    }

    /**
     * Relaciones
     */
    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Scopes
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopePorPrioridad($query, string $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Marcar como leída
     */
    public function marcarComoLeida(): void
    {
        $this->update([
            'leida' => true,
            'fecha_leida' => now(),
        ]);
    }

    /**
     * Crear notificación de entrega próxima
     */
    public static function crearNotificacionEntregaProxima(Trabajo $trabajo): self
    {
        return self::create([
            'tipo' => self::TIPO_ENTREGA_PROXIMA,
            'titulo' => 'Entrega Próxima',
            'mensaje' => "El trabajo '{$trabajo->tipo_trabajo}' debe entregarse en menos de 24 horas",
            'prioridad' => self::getPrioridadPorTipo(self::TIPO_ENTREGA_PROXIMA),
            'icono' => self::getIconoPorTipo(self::TIPO_ENTREGA_PROXIMA),
            'trabajo_id' => $trabajo->id,
            'datos_adicionales' => [
                'cliente' => $trabajo->cliente?->nombre_completo,
                'fecha_entrega' => $trabajo->fecha_entrega?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Crear notificación de entrega de hoy
     */
    public static function crearNotificacionEntregaHoy(Trabajo $trabajo): self
    {
        return self::create([
            'tipo' => self::TIPO_ENTREGA_HOY,
            'titulo' => 'Entrega Hoy',
            'mensaje' => "El trabajo '{$trabajo->tipo_trabajo}' debe entregarse HOY",
            'prioridad' => self::getPrioridadPorTipo(self::TIPO_ENTREGA_HOY),
            'icono' => self::getIconoPorTipo(self::TIPO_ENTREGA_HOY),
            'trabajo_id' => $trabajo->id,
            'datos_adicionales' => [
                'cliente' => $trabajo->cliente?->nombre_completo,
                'fecha_entrega' => $trabajo->fecha_entrega?->toIso8601String(),
            ],
        ]);
    }
}
