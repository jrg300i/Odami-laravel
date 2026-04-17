<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clausula extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido',
        'orden',
        'activa',
        'obligatoria',
        'tipo'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'obligatoria' => 'boolean'
    ];

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeObligatorias($query)
    {
        return $query->where('obligatoria', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden')->orderBy('id');
    }
}