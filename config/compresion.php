<?php

return [
    'imagenes' => [
        'calidad_miniatura' => 80,
        'calidad_comprimida' => 75,
        'tamanio_maximo_miniatura' => 300,
        'tamanio_maximo_comprimida' => 1200,
        'comprimir_automaticamente' => true,
        'dias_para_compresion' => 7,
    ],

    'archivos' => [
        'tamanio_maximo_sin_compresion' => 1024, // KB
        'formato_salida' => 'jpg',
    ],

    'limpieza' => [
        'dias_retener_temporales' => 1,
        'ejecutar_automaticamente' => true,
    ],
];
