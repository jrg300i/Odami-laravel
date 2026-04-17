<?php

return [
    'roles' => [
        'admin' => [
            'nombre' => 'Administrador',
            'permisos' => [
                'clientes.*',
                'trabajos.*',
                'facturas.*',
                'pagos.*',
                'fotos.*',
                'materiales.*',
                'backups.*',
                'reportes.*',
                'configuracion.*',
                'usuarios.*',
            ],
        ],
        'tapicero' => [
            'nombre' => 'Tapicero',
            'permisos' => [
                'clientes.ver',
                'trabajos.*',
                'fotos.*',
                'materiales.ver',
                'facturas.ver',
            ],
        ],
        'ventas' => [
            'nombre' => 'Ventas',
            'permisos' => [
                'clientes.*',
                'trabajos.ver',
                'facturas.*',
                'pagos.*',
                'reportes.ver',
            ],
        ],
        'cliente' => [
            'nombre' => 'Cliente',
            'permisos' => [
                'clientes.ver_propio',
                'trabajos.ver_propio',
                'facturas.ver_propias',
                'pagos.ver_propios',
            ],
        ],
    ],
];