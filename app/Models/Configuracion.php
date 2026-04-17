<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones'; // ← Agrega esta línea
    use HasFactory;

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'grupo',
        'descripcion',
        'opciones'
    ];

    protected $casts = [
        'opciones' => 'array'
    ];

    // Métodos estáticos para configuraciones comunes
    public static function obtener($clave, $default = null)
    {
        $config = static::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    public static function establecer($clave, $valor, $tipo = 'string', $grupo = 'general', $descripcion = null)
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'tipo' => $tipo,
                'grupo' => $grupo,
                'descripcion' => $descripcion
            ]
        );
    }

    public static function obtenerBackupAutomatico()
    {
        return static::obtener('backup_automatico', 'false') === 'true';
    }

    public static function obtenerFrecuenciaBackup()
    {
        return static::obtener('frecuencia_backup', 'daily');
    }

    public static function obtenerDiasRetencionBackup()
    {
        return (int) static::obtener('dias_retencion_backup', '30');
    }
}