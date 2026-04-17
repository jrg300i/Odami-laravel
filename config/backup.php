<?php

return [
    'automatico' => env('BACKUP_AUTOMATICO', false),
    'frecuencia' => env('BACKUP_FRECUENCIA', 'daily'),
    'retencion_dias' => env('BACKUP_RETENCION_DIAS', 30),
    
    'ubicaciones' => [
        'manuales' => 'backups/manuales',
        'automaticos' => 'backups/automaticos',
    ],
    
    'base_datos' => [
        'incluir' => true,
        'comando' => 'pg_dump',
    ],
    
    'archivos' => [
        'incluir' => true,
        'carpetas' => [
            'storage/app/public/trabajos',
            'storage/app/public/comprobantes',
        ],
        'excluir' => [
            'storage/app/public/temp',
            'storage/logs',
        ],
    ],
];