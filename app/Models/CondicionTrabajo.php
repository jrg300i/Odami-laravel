<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CondicionTrabajo extends Model
{
    protected $table = 'condiciones_trabajo';

    protected $fillable = [
        'titulo',
        'descripcion',
        'activa',
        'orden',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope para condiciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true)->orderBy('orden');
    }

    /**
     * Relación con facturas
     */
    public function facturas(): BelongsToMany
    {
        return $this->belongsToMany(Factura::class, 'condicion_factura')
            ->withPivot('orden')
            ->orderByPivot('orden');
    }
}
