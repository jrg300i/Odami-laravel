<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'trabajo_id',
        'numero_factura',
        'tipo',
        'subtotal',
        'igv',
        'total',
        'estado_pago',
        'metodo_pago',
        'observaciones',
        'emitida_por',
        'fecha_pago',
        // Datos del cliente
        'cliente_nombre',
        'cliente_apellido',
        'cliente_documento',
        'cliente_direccion',
        'cliente_telefono',
        'cliente_email',
        // Datos del trabajo
        'trabajo_tipo',
        'trabajo_descripcion',
        'trabajo_fecha_recibido',
        'trabajo_fecha_entrega',
        // Datos legales de la empresa
        'empresa_ruc',
        'empresa_razon_social',
        'empresa_direccion',
        'empresa_telefono',
        'empresa_email',
        // Datos del representante
        'representante_nombre',
        'representante_dni',
        'representante_cargo',
        // Firma y sello
        'firma_base64',
        'sello_base64',
        'notas_legales',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_emision' => 'datetime',
        'fecha_pago' => 'datetime',
    ];

    /**
     * Constantes de tipo de factura
     */
    const TIPO_ORIGINAL = 'original';
    const TIPO_COPIA = 'copia';

    /**
     * Scopes para consultas
     */
    public function scopePorCliente($query, $clienteId)
    {
        return $query->whereHas('trabajo', function ($q) use ($clienteId) {
            $q->where('cliente_id', $clienteId);
        });
    }

    public function scopePorNumero($query, $numero)
    {
        return $query->where('numero_factura', 'LIKE', "%{$numero}%");
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Relaciones
     */
    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function emisor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'emitida_por');
    }

    public function condiciones(): BelongsToMany
    {
        return $this->belongsToMany(CondicionTrabajo::class, 'condicion_factura')
            ->withPivot('orden')
            ->orderByPivot('orden');
    }

    /**
     * Accessors
     */
    public function getNombreClienteAttribute(): ?string
    {
        return $this->trabajo?->cliente?->nombre_completo;
    }

    public function getClienteIdAttribute(): ?int
    {
        return $this->trabajo?->cliente_id;
    }

    public function getTipoTrabajoAttribute(): ?string
    {
        return $this->trabajo?->tipo_trabajo;
    }

    public function getFechaRecibidoAttribute(): ?string
    {
        return $this->trabajo?->fecha_recibido;
    }

    public function getFechaEntregaAttribute(): ?string
    {
        return $this->trabajo?->fecha_entrega;
    }

    /**
     * Obtener información completa de la factura
     */
    public function getInformacionCompletaAttribute(): array
    {
        return [
            'id' => $this->id,
            'numero_factura' => $this->numero_factura,
            'tipo' => $this->tipo,
            'cliente_id' => $this->cliente_id,
            'nombre_cliente' => $this->nombre_cliente,
            'trabajo' => $this->tipo_trabajo,
            'fecha_recibido' => $this->fecha_recibido,
            'fecha_entrega' => $this->fecha_entrega,
            'subtotal' => $this->subtotal,
            'igv' => $this->igv,
            'total' => $this->total,
            'estado_pago' => $this->estado_pago,
            'fecha_emision' => $this->fecha_emision,
        ];
    }
}
