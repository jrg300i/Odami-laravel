<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    protected $table = 'entregas';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'trabajo_id',
        'fecha_entrega',
        'estado',
        'notas',
        'recordatorio_enviado',
        'fecha_recordatorio',
        'creado_por',
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
        'fecha_recordatorio' => 'datetime',
        'recordatorio_enviado' => 'boolean',
    ];

    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }
}
