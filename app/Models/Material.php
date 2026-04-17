<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiales'; // ← Agrega esta línea
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo',
        'color',
        'codigo_referencia',
        'precio_metro',
        'precio_unidad',
        'stock_actual',
        'stock_minimo',
        'proveedor',
        'caracteristicas',
        'activo'
    ];

    protected $casts = [
        'precio_metro' => 'decimal:2',
        'precio_unidad' => 'decimal:2',
        'activo' => 'boolean'
    ];

    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class, 'trabajo_material')
                    ->withPivot('cantidad', 'unidad_medida', 'costo_total', 'observaciones')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock_actual <= stock_minimo');
    }

    // Accessors
    public function getStockBajoAttribute()
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    public function getValorStockAttribute()
    {
        return $this->stock_actual * $this->precio_unidad;
    }
}