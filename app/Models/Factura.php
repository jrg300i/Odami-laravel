<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    protected $table = 'facturas';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'trabajo_id',
        'numero_factura',
        'subtotal',
        'igv',
        'total',
        'estado_pago',
        'metodo_pago',
        'observaciones',
        'emitida_por',
        'fecha_pago',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_emision' => 'datetime',
        'fecha_pago' => 'datetime',
    ];

    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function emisor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'emitida_por');
    }
}
