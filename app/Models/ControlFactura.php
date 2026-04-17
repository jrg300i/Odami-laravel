<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlFactura extends Model
{
    use HasFactory;

    protected $table = 'control_facturas';

    protected $fillable = [
        'serie',
        'ultimo_numero',
        'descripcion',
        'activo',
        'numero_inicio'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_numero' => 'integer',
        'numero_inicio' => 'integer'
    ];

    // Métodos
    public function obtenerSiguienteNumero()
    {
        $this->increment('ultimo_numero');
        return $this->ultimo_numero;
    }

    public function formatearNumero($numero)
    {
        return str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    public function generarNumeroCompleto($numero = null)
    {
        $numero = $numero ?: $this->obtenerSiguienteNumero();
        return "{$this->serie}-" . $this->formatearNumero($numero);
    }
}