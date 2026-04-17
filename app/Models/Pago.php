<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'factura_id',
        'cliente_id',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'referencia',
        'observaciones',
        'estado',
        'comprobante_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Opcional: Definir valores por defecto
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'estado' => 'pendiente',
    ];

    /**
     * RELACIONES
     */

    /**
     * Obtener la factura asociada al pago.
     */
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    /**
     * Obtener el cliente asociado al pago.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * SCOPES (Filtros)
     */

    /**
     * Scope para obtener pagos completados.
     */
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    /**
     * Scope para obtener pagos pendientes.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para obtener pagos fallidos.
     */
    public function scopeFallidos($query)
    {
        return $query->where('estado', 'fallido');
    }

    /**
     * Scope para obtener pagos reembolsados.
     */
    public function scopeReembolsados($query)
    {
        return $query->where('estado', 'reembolsado');
    }

    /**
     * Scope para filtrar por método de pago.
     */
    public function scopePorMetodo($query, $metodo)
    {
        return $query->where('metodo_pago', $metodo);
    }

    /**
     * Scope para filtrar por rango de fechas.
     */
    public function scopeEntreFechas($query, $desde, $hasta = null)
    {
        $query->whereDate('fecha_pago', '>=', $desde);
        
        if ($hasta) {
            $query->whereDate('fecha_pago', '<=', $hasta);
        }
        
        return $query;
    }

    /**
     * Scope para obtener pagos de un cliente específico.
     */
    public function scopeDeCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    /**
     * MÉTODOS DE ACCESO (Accessors)
     */

    /**
     * Formatear el monto con símbolo de euro.
     */
    public function getMontoFormateadoAttribute()
    {
        return number_format($this->monto, 2, ',', '.') . ' €';
    }

    /**
     * Obtener el método de pago formateado.
     */
    public function getMetodoPagoFormateadoAttribute()
    {
        $metodos = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia Bancaria',
            'tarjeta' => 'Tarjeta de Crédito/Débito',
            'cheque' => 'Cheque',
        ];
        
        return $metodos[$this->metodo_pago] ?? ucfirst($this->metodo_pago);
    }

    /**
     * Obtener el estado formateado.
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'completado' => 'Completado',
            'fallido' => 'Fallido',
            'reembolsado' => 'Reembolsado',
        ];
        
        return $estados[$this->estado] ?? ucfirst($this->estado);
    }

    /**
     * Verificar si el pago está completado.
     */
    public function getEstaCompletadoAttribute()
    {
        return $this->estado === 'completado';
    }

    /**
     * Verificar si el pago está pendiente.
     */
    public function getEstaPendienteAttribute()
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si el pago tiene comprobante.
     */
    public function getTieneComprobanteAttribute()
    {
        return !empty($this->comprobante_path);
    }

    /**
     * Obtener la URL del comprobante.
     */
    public function getUrlComprobanteAttribute()
    {
        if (!$this->comprobante_path) {
            return null;
        }
        
        // Si usas almacenamiento público
        return asset('storage/' . $this->comprobante_path);
    }

    /**
     * MÉTODOS DE UTILIDAD
     */

    /**
     * Marcar pago como completado.
     */
    public function marcarComoCompletado($referencia = null, $comprobantePath = null)
    {
        $this->update([
            'estado' => 'completado',
            'referencia' => $referencia ?? $this->referencia,
            'comprobante_path' => $comprobantePath ?? $this->comprobante_path,
            'fecha_pago' => $this->fecha_pago ?? now(),
        ]);
        
        return $this;
    }

    /**
     * Marcar pago como fallido.
     */
    public function marcarComoFallido($observaciones = null)
    {
        $this->update([
            'estado' => 'fallido',
            'observaciones' => $observaciones ?? ($this->observaciones . ' - Pago fallido'),
        ]);
        
        return $this;
    }

    /**
     * Reembolsar el pago.
     */
    public function reembolsar($observaciones = null)
    {
        $this->update([
            'estado' => 'reembolsado',
            'observaciones' => $observaciones ?? ($this->observaciones . ' - Reembolsado'),
        ]);
        
        return $this;
    }

    /**
     * Verificar si el pago es reembolsable.
     */
    public function esReembolsable()
    {
        return $this->estado === 'completado' && 
               now()->diffInDays($this->fecha_pago) <= 30; // Reembolsable hasta 30 días
    }

    /**
     * Calcular días transcurridos desde el pago.
     */
    public function diasDesdePago()
    {
        if (!$this->fecha_pago) {
            return null;
        }
        
        return now()->diffInDays($this->fecha_pago);
    }

    /**
     * Verificar si el pago está vencido (si estaba pendiente).
     */
    public function estaVencido()
    {
        return $this->estado === 'pendiente' && 
               $this->created_at->diffInDays(now()) > 7; // Vence a los 7 días
    }

    /**
     * Obtener información resumida del pago.
     */
    public function obtenerResumen()
    {
        return [
            'id' => $this->id,
            'monto' => $this->monto_formateado,
            'fecha' => $this->fecha_pago->format('d/m/Y'),
            'metodo' => $this->metodo_pago_formateado,
            'estado' => $this->estado_formateado,
            'cliente' => $this->cliente ? $this->cliente->nombre : 'N/A',
            'factura' => $this->factura ? $this->factura->numero_completo : 'N/A',
        ];
    }
}