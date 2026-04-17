<?php

return [
    'series' => [
        'A' => [
            'descripcion' => 'Facturación General',
            'activa' => true,
            'numero_inicio' => 1,
        ],
        'B' => [
            'descripcion' => 'Facturación Exportación',
            'activa' => false,
            'numero_inicio' => 1,
        ],
        'C' => [
            'descripcion' => 'Facturación Servicios',
            'activa' => false,
            'numero_inicio' => 1,
        ],
    ],
    
    'iva' => [
        'general' => 21,
        'reducido' => 10,
        'superreducido' => 4,
    ],
    
    'vencimiento' => [
        'dias_predeterminados' => 30,
    ],
    
    'numeracion' => [
        'formato' => 'SERIE-NUMERO',
        'longitud_numero' => 6,
        'relleno' => '0',
    ],
];