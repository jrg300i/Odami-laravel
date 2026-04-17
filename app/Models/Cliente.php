<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo_cliente',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'codigo_postal',
        'notas',
        'tipo',
        'dni_cif',
        'activo',
        'user_id'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nombre', 'ILIKE', "%{$search}%")
              ->orWhere('apellido', 'ILIKE', "%{$search}%")
              ->orWhere('email', 'ILIKE', "%{$search}%")
              ->orWhere('codigo_cliente', 'ILIKE', "%{$search}%")
              ->orWhere('dni_cif', 'ILIKE', "%{$search}%");
        });
    }

    // Accessors
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getTotalFacturadoAttribute()
    {
        return $this->facturas()->where('estado', '!=', 'cancelada')->sum('total');
    }

    public function getTotalPagadoAttribute()
    {
        return $this->pagos()->where('estado', 'completado')->sum('monto');
    }

    public function getTrabajosCompletadosCountAttribute()
    {
        return $this->trabajos()->where('estado', 'completado')->count();
    }
}