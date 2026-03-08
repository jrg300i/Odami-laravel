<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoTrabajo extends Model
{
    protected $table = 'fotos_trabajo';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'trabajo_id',
        'foto_url',
        'foto_base64',
        'tipo',
        'descripcion',
        'subido_por',
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function subidor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'subido_por');
    }
}
