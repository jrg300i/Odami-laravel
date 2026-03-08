<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'actualizado_por',
    ];

    protected $casts = [
        'fecha_actualizacion' => 'datetime',
    ];

    public static function get($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    public static function set($clave, $valor, $descripcion = null)
    {
        return self::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor, 'descripcion' => $descripcion]
        );
    }
}
