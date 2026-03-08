<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuario extends Model
{
    protected $table = 'usuarios';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'username',
        'password',
        'nombre',
        'email',
        'telefono',
        'rol',
        'activo',
        'ultimo_acceso',
        'created_by',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_creacion' => 'datetime',
        'ultimo_acceso' => 'datetime',
    ];

    public function trabajosCreados(): HasMany
    {
        return $this->hasMany(Trabajo::class, 'creado_por');
    }

    public function clientesCreados(): HasMany
    {
        return $this->hasMany(Cliente::class, 'creado_por');
    }

    public function facturasEmitidas(): HasMany
    {
        return $this->hasMany(Factura::class, 'emitida_por');
    }
}
