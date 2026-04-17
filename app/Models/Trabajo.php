<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'user_id',
        'codigo_trabajo',
        'titulo',
        'descripcion',
        'tipo',
        'estado',
        'costo_estimado',
        'costo_final',
        'fecha_inicio',
        'fecha_fin_estimada',
        'fecha_fin_real',
        'prioridad',
        'notas_internas',
        'observaciones_cliente',
        'urgente'
    ];

    protected $casts = [
        'costo_estimado' => 'decimal:2',
        'costo_final' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin_estimada' => 'date',
        'fecha_fin_real' => 'date',
        'urgente' => 'boolean'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'trabajo_material')
                    ->withPivot('cantidad', 'unidad_medida', 'costo_total', 'observaciones')
                    ->withTimestamps();
    }

    public function fotos()
    {
        return $this->hasMany(FotoTrabajo::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('titulo', 'ILIKE', "%{$search}%")
              ->orWhere('codigo_trabajo', 'ILIKE', "%{$search}%")
              ->orWhere('descripcion', 'ILIKE', "%{$search}%")
              ->orWhereHas('cliente', function($q) use ($search) {
                  $q->where('nombre', 'ILIKE', "%{$search}%")
                    ->orWhere('apellido', 'ILIKE', "%{$search}%");
              });
        });
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeUrgentes($query)
    {
        return $query->where('urgente', true);
    }

    // Accessors
    public function getDuracionEstimadaAttribute()
    {
        if ($this->fecha_inicio && $this->fecha_fin_estimada) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin_estimada);
        }
        return null;
    }

    public function getDiasRestantesAttribute()
    {
        if ($this->fecha_fin_estimada && $this->estado != 'completado') {
            return now()->diffInDays($this->fecha_fin_estimada, false);
        }
        return null;
    }

    public function getFotoPrincipalAttribute()
    {
        return $this->fotos()->where('es_principal', true)->first();
    }

    public function calcularCostosMateriales()
    {
        return $this->materiales()->sum('trabajo_material.costo_total');
    }

    public function marcarComoCompletado()
    {
        $this->update([
            'estado' => 'completado',
            'fecha_fin_real' => now(),
            'costo_final' => $this->costo_final ?? $this->costo_estimado
        ]);
    }
}