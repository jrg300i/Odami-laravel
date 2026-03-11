<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'usuarios';

    protected $primaryKey = 'id';

    public $timestamps = false;

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

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Verificar si el usuario es vendedor
     */
    public function esVendedor(): bool
    {
        return $this->rol === 'vendedor';
    }

    /**
     * Verificar si el usuario está activo
     */
    public function estaActivo(): bool
    {
        return $this->activo === true;
    }

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
