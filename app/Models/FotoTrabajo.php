<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoTrabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trabajo_id',
        'titulo',
        'descripcion',
        'ruta_original',
        'ruta_miniatura',
        'ruta_comprimida',
        'fase',
        'es_principal',
        'orden',
        'tamanio_original',
        'tamanio_comprimido',
        'metadata'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'tamanio_original' => 'decimal:2',
        'tamanio_comprimido' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }

    // Scopes
    public function scopePorFase($query, $fase)
    {
        return $query->where('fase', $fase);
    }

    public function scopePrincipales($query)
    {
        return $query->where('es_principal', true);
    }

    // Accessors
    public function getUrlOriginalAttribute()
    {
        return asset('storage/' . $this->ruta_original);
    }

    public function getUrlMiniaturaAttribute()
    {
        return $this->ruta_miniatura ? asset('storage/' . $this->ruta_miniatura) : $this->url_original;
    }

    public function getUrlComprimidaAttribute()
    {
        return $this->ruta_comprimida ? asset('storage/' . $this->ruta_comprimida) : $this->url_original;
    }

    public function getTamanioFormateadoAttribute()
    {
        $tamanio = $this->tamanio_comprimido ?: $this->tamanio_original;
        
        if ($tamanio >= 1024) {
            return number_format($tamanio / 1024, 2) . ' MB';
        }
        
        return number_format($tamanio, 2) . ' KB';
    }

    // Métodos
    public function marcarComoPrincipal()
    {
        // Quitar principal de otras fotos del mismo trabajo
        $this->trabajo->fotos()->update(['es_principal' => false]);
        
        // Marcar esta como principal
        $this->update(['es_principal' => true]);
    }
}