<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionesSeeder extends Seeder
{
    public function run()
    {
        $configuraciones = [
            [
                'clave' => 'empresa_nombre',
                'valor' => 'Tapicería Odami',
                'tipo' => 'string',
                'grupo' => 'empresa',
                'descripcion' => 'Nombre de la empresa',
            ],
            [
                'clave' => 'empresa_direccion',
                'valor' => 'Calle Principal 123, Ciudad',
                'tipo' => 'string',
                'grupo' => 'empresa',
                'descripcion' => 'Dirección de la empresa',
            ],
            [
                'clave' => 'empresa_telefono',
                'valor' => '+34 912 345 678',
                'tipo' => 'string',
                'grupo' => 'empresa',
                'descripcion' => 'Teléfono de contacto',
            ],
            [
                'clave' => 'empresa_email',
                'valor' => 'info@tapiceria-odami.com',
                'tipo' => 'string',
                'grupo' => 'empresa',
                'descripcion' => 'Email de contacto',
            ],
            [
                'clave' => 'empresa_cif',
                'valor' => 'B12345678',
                'tipo' => 'string',
                'grupo' => 'empresa',
                'descripcion' => 'CIF de la empresa',
            ],
            [
                'clave' => 'backup_automatico',
                'valor' => 'true',
                'tipo' => 'boolean',
                'grupo' => 'backup',
                'descripcion' => 'Activar backups automáticos',
            ],
            [
                'clave' => 'frecuencia_backup',
                'valor' => 'daily',
                'tipo' => 'string',
                'grupo' => 'backup',
                'descripcion' => 'Frecuencia de backups automáticos',
                'opciones' => ['daily', 'weekly', 'monthly'],
            ],
            [
                'clave' => 'dias_retencion_backup',
                'valor' => '30',
                'tipo' => 'integer',
                'grupo' => 'backup',
                'descripcion' => 'Días de retención de backups',
            ],
            [
                'clave' => 'comprimir_fotos_automatico',
                'valor' => 'true',
                'tipo' => 'boolean',
                'grupo' => 'fotos',
                'descripcion' => 'Comprimir fotos automáticamente',
            ],
            [
                'clave' => 'dias_compresion_fotos',
                'valor' => '7',
                'tipo' => 'integer',
                'grupo' => 'fotos',
                'descripcion' => 'Días antes de comprimir fotos',
            ],
        ];

        foreach ($configuraciones as $config) {
            Configuracion::create($config);
        }
    }
}