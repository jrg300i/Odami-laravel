<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioMovimiento extends Model
{
    protected $table = 'inventario_movimientos';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'tipo_movimiento',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
        'trabajo_id',
        'realizado_por',
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'item_id');
    }

    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function realizador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'realizado_por');
    }
}
