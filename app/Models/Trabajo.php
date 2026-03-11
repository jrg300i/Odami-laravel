<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Trabajo extends Model
{
    protected $table = 'trabajos';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'tipo_trabajo',
        'descripcion',
        'estado',
        'precio_estimado',
        'precio_final',
        'anticipo',
        'fecha_recibido',
        'fecha_entrega',
        'fecha_completado',
        'notas',
        'creado_por',
        'modificado_por',
    ];

    protected $casts = [
        'precio_estimado' => 'decimal:2',
        'precio_final' => 'decimal:2',
        'anticipo' => 'decimal:2',
        'fecha_recibido' => 'datetime',
        'fecha_entrega' => 'datetime',
        'fecha_completado' => 'datetime',
    ];

    /**
     * Scopes para consultas
     */
    public function scopePorEntregar(Builder $query): void
    {
        $query->whereNotNull('fecha_entrega')
            ->where('fecha_entrega', '>', now())
            ->whereNotIn('estado', ['entregado', 'cancelado']);
    }

    public function scopeEntregaProxima(Builder $query): void
    {
        $query->whereNotNull('fecha_entrega')
            ->where('fecha_entrega', '>', now())
            ->where('fecha_entrega', '<=', now()->addHours(24))
            ->whereNotIn('estado', ['entregado', 'cancelado']);
    }

    public function scopeEntregaHoy(Builder $query): void
    {
        $query->whereDate('fecha_entrega', today())
            ->whereNotIn('estado', ['entregado', 'cancelado']);
    }

    public function scopeRetrasados(Builder $query): void
    {
        $query->whereNotNull('fecha_entrega')
            ->where('fecha_entrega', '<', now())
            ->whereNotIn('estado', ['entregado', 'cancelado']);
    }

    /**
     * Relaciones
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoTrabajo::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    /**
     * Materiales usados en este trabajo (One to Many)
     * Un trabajo usa muchos materiales del inventario
     */
    public function materiales(): HasMany
    {
        return $this->hasMany(TrabajoMaterial::class, 'trabajo_id');
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class);
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function modificador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'modificado_por');
    }

    /**
     * Accessors
     */
    public function getSaldoAttribute(): float
    {
        return ($this->precio_final ?? $this->precio_estimado ?? 0) - ($this->anticipo ?? 0);
    }

    public function getDiasParaEntregaAttribute(): ?int
    {
        if (!$this->fecha_entrega) {
            return null;
        }

        return now()->diffInDays($this->fecha_entrega, false);
    }

    public function getEstadoEntregaAttribute(): string
    {
        if (!$this->fecha_entrega) {
            return 'sin_fecha';
        }

        if ($this->fecha_entrega->isPast()) {
            return 'retrasado';
        }

        if ($this->fecha_entrega->isToday()) {
            return 'hoy';
        }

        if ($this->fecha_entrega->diffInHours(now()) <= 24) {
            return 'proximo';
        }

        return 'a_tiempo';
    }
}
