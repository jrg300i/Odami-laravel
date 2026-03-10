<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'fecha_ingreso' => 'datetime',
        'fecha_entrega' => 'datetime',
        'fecha_completado' => 'datetime',
    ];

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

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function modificador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'modificado_por');
    }

    public function getSaldoAttribute(): float
    {
        return ($this->precio_final ?? 0) - ($this->anticipo ?? 0);
    }
}
