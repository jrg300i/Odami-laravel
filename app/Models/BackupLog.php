<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'archivo',
        'tamanio',
        'ubicacion',
        'observaciones',
        'estado',
        'detalles',
        'iniciado_en',
        'completado_en'
    ];

    protected $casts = [
        'tamanio' => 'decimal:2',
        'detalles' => 'array',
        'iniciado_en' => 'datetime',
        'completado_en' => 'datetime'
    ];

    // Scopes
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopeFallidos($query)
    {
        return $query->where('estado', 'fallido');
    }

    public function scopeAutomaticos($query)
    {
        return $query->where('tipo', 'automatico');
    }

    // Accessors
    public function getDuracionAttribute()
    {
        if ($this->completado_en) {
            return $this->iniciado_en->diffInSeconds($this->completado_en);
        }
        return null;
    }

    public function getDuracionFormateadaAttribute()
    {
        $duracion = $this->duracion;
        if (!$duracion) return null;

        if ($duracion < 60) {
            return "{$duracion} segundos";
        } elseif ($duracion < 3600) {
            return floor($duracion / 60) . " minutos";
        } else {
            return floor($duracion / 3600) . " horas";
        }
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'completado' => 'success',
            'fallido' => 'danger',
            'en_proceso' => 'warning',
            default => 'secondary'
        };
    }
}