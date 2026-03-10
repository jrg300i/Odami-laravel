<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'inventario';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'categoria',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'unidad',
        'precio_unitario',
        'proveedor',
        'contacto_proveedor',
        'ubicacion',
        'creado_por',
        'modificado_por',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'fecha_actualizacion' => 'datetime',
    ];

    public function movimientos(): HasMany
    {
        return $this->hasMany(InventarioMovimiento::class, 'item_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function getStockDisponibleAttribute(): bool
    {
        return $this->stock_actual >= $this->stock_minimo;
    }
}
