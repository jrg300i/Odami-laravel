<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'trabajo_id',
        'serie',
        'numero',
        'numero_completo',
        'fecha_emision',
        'fecha_vencimiento',
        'subtotal',
        'iva',
        'total',
        'estado',
        'concepto',
        'observaciones',
        'lineas',
        'forma_pago',
        'fecha_pago',
        'incluir_clausulas'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'date',
        'lineas' => 'array',
        'incluir_clausulas' => 'boolean'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }



    // Scopes
    public function scopeEmitidas($query)
    {
        return $query->where('estado', 'emitida');
    }

    public function scopePagadas($query)
    {
        return $query->where('estado', 'pagada');
    }

    public function scopePorSerie($query, $serie)
    {
        return $query->where('serie', $serie);
    }

    public function scopeVencidas($query)
    {
        return $query->where('fecha_vencimiento', '<', now())
                    ->where('estado', 'emitida');
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'borrador' => 'secondary',
            'emitida' => 'primary',
            'pagada' => 'success',
            'cancelada' => 'danger',
            'vencida' => 'warning',
            default => 'secondary'
        };
    }

    // Accessors
    public function getSaldoPendienteAttribute()
    {
        $totalPagado = $this->pagos()->where('estado', 'completado')->sum('monto');
        return $this->total - $totalPagado;
    }

    public function getEstaPagadaAttribute()
    {
        return $this->saldo_pendiente <= 0;
    }

    public function getEstaVencidaAttribute()
    {
        return $this->fecha_vencimiento && $this->fecha_vencimiento < now() && $this->estado == 'emitida';
    }

    // Métodos
    public function generarNumeroCompleto()
    {
        return "{$this->serie}-" . str_pad($this->numero, 6, '0', STR_PAD_LEFT);
    }

    public function agregarLinea($descripcion, $cantidad, $precio, $iva = 21)
    {
        $lineas = $this->lineas ?? [];
        $lineas[] = [
            'descripcion' => $descripcion,
            'cantidad' => $cantidad,
            'precio' => $precio,
            'iva' => $iva,
            'total' => $cantidad * $precio
        ];
        
        $this->update(['lineas' => $lineas]);
        $this->recalcularTotales();
    }

    public function recalcularTotales()
    {
        $lineas = $this->lineas ?? [];
        $subtotal = 0;
        
        foreach ($lineas as $linea) {
            $subtotal += $linea['total'];
        }
        
        $iva = $subtotal * ($this->iva / 100);
        $total = $subtotal + $iva;
        
        $this->update([
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    }

    public function marcarComoPagada()
    {
        $this->update([
            'estado' => 'pagada',
            'fecha_pago' => now()
        ]);
    }
}