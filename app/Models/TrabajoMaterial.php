<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo que representa el consumo de materiales en un trabajo.
 * 
 * Relación:
 * - Un trabajo tiene muchos materiales registrados (One to Many)
 * - Un item del inventario puede estar en muchos registros de consumo
 */
class TrabajoMaterial extends Model
{
    protected $table = 'trabajo_materiales';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'trabajo_id',
        'inventario_id',
        'cantidad_usada',
        'unidad_medida',
        'observaciones',
    ];

    protected $casts = [
        'cantidad_usada' => 'decimal:2',
    ];

    /**
     * Obtener el trabajo al que pertenece este registro
     */
    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class, 'trabajo_id');
    }

    /**
     * Obtener el item del inventario usado
     */
    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }

    /**
     * Scope para obtener materiales de un trabajo específico
     */
    public function scopePorTrabajo($query, $trabajoId)
    {
        return $query->where('trabajo_id', $trabajoId);
    }

    /**
     * Scope para obtener registros de un item específico
     */
    public function scopePorInventario($query, $inventarioId)
    {
        return $query->where('inventario_id', $inventarioId);
    }
}
