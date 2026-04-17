<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacturasSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar que existen clientes y trabajos
        $clientes = DB::table('clientes')->pluck('id')->toArray();
        $trabajos = DB::table('trabajos')->pluck('id')->toArray();
        
        if (empty($clientes)) {
            $this->command->error('Primero ejecuta el seeder de clientes!');
            return;
        }

        // Datos de series de facturación
        $series = [
            'A' => ['nombre' => 'Factura Ordinaria', 'inicio' => 1],
            'B' => ['nombre' => 'Factura Simplificada', 'inicio' => 1],
            'C' => ['nombre' => 'Factura Rectificativa', 'inicio' => 1],
        ];

        $facturas = [
            // Facturas Pagadas (Serie A)
            [
                'cliente_id' => $clientes[0], // Juan García
                'trabajo_id' => $trabajos[0] ?? null, // TRAB-2024-001
                'serie' => 'A',
                'numero' => 1,
                'fecha_emision' => '2024-01-28',
                'fecha_vencimiento' => '2024-02-28',
                'subtotal' => 347.11,
                'iva' => 21.00,
                'total' => 420.00,
                'estado' => 'pagada',
                'concepto' => 'Tapizado completo de sofá 3 plazas con tela de lino beige',
                'observaciones' => 'Cliente pagó al contado',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Tapizado sofá 3 plazas',
                        'cantidad' => 1,
                        'precio_unitario' => 250.00,
                        'subtotal' => 250.00
                    ],
                    [
                        'descripcion' => 'Tela de lino beige (8 metros)',
                        'cantidad' => 8,
                        'precio_unitario' => 25.50,
                        'subtotal' => 204.00
                    ],
                    [
                        'descripcion' => 'Espuma alta densidad',
                        'cantidad' => 2,
                        'precio_unitario' => 32.50,
                        'subtotal' => 65.00
                    ],
                    [
                        'descripcion' => 'Mano de obra',
                        'cantidad' => 15,
                        'precio_unitario' => 20.00,
                        'subtotal' => 300.00
                    ],
                    [
                        'descripcion' => 'Descuento fidelidad',
                        'cantidad' => 1,
                        'precio_unitario' => -471.89,
                        'subtotal' => -471.89
                    ]
                ]),
                'forma_pago' => 'efectivo',
                'fecha_pago' => '2024-01-28',
                'incluir_clausulas' => true,
            ],
            [
                'cliente_id' => $clientes[1], // María Rodríguez
                'trabajo_id' => $trabajos[1] ?? null, // TRAB-2024-002
                'serie' => 'A',
                'numero' => 2,
                'fecha_emision' => '2024-02-20',
                'fecha_vencimiento' => '2024-03-20',
                'subtotal' => 161.16,
                'iva' => 21.00,
                'total' => 195.00,
                'estado' => 'pagada',
                'concepto' => 'Restauración silla antigua con terciopelo rojo',
                'observaciones' => 'Material adicional requerido',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Restauración estructura madera',
                        'cantidad' => 1,
                        'precio_unitario' => 60.00,
                        'subtotal' => 60.00
                    ],
                    [
                        'descripcion' => 'Terciopelo rojo (2 metros)',
                        'cantidad' => 2,
                        'precio_unitario' => 42.80,
                        'subtotal' => 85.60
                    ],
                    [
                        'descripcion' => 'Mano de obra',
                        'cantidad' => 8,
                        'precio_unitario' => 18.00,
                        'subtotal' => 144.00
                    ],
                    [
                        'descripcion' => 'Clavos decorativos',
                        'cantidad' => 50,
                        'precio_unitario' => 0.85,
                        'subtotal' => 42.50
                    ],
                    [
                        'descripcion' => 'Material adicional',
                        'cantidad' => 1,
                        'precio_unitario' => 15.00,
                        'subtotal' => 15.00
                    ],
                    [
                        'descripcion' => 'Descuento',
                        'cantidad' => 1,
                        'precio_unitario' => -161.94,
                        'subtotal' => -161.94
                    ]
                ]),
                'forma_pago' => 'transferencia',
                'fecha_pago' => '2024-02-25',
                'incluir_clausulas' => true,
            ],

            // Facturas Emitidas (pendientes de pago)
            [
                'cliente_id' => $clientes[4], // Pedro Sánchez
                'trabajo_id' => $trabajos[2] ?? null, // TRAB-2024-003
                'serie' => 'A',
                'numero' => 3,
                'fecha_emision' => '2024-03-18',
                'fecha_vencimiento' => '2024-04-18',
                'subtotal' => 256.20,
                'iva' => 21.00,
                'total' => 310.00,
                'estado' => 'emitida',
                'concepto' => 'Fabricación butaca personalizada reclinable',
                'observaciones' => 'Cliente debe recoger en taller',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Fabricación butaca reclinable',
                        'cantidad' => 1,
                        'precio_unitario' => 150.00,
                        'subtotal' => 150.00
                    ],
                    [
                        'descripcion' => 'Mecanismo reclinable especial',
                        'cantidad' => 1,
                        'precio_unitario' => 85.00,
                        'subtotal' => 85.00
                    ],
                    [
                        'descripcion' => 'Tela de cuero sintético (3 metros)',
                        'cantidad' => 3,
                        'precio_unitario' => 35.90,
                        'subtotal' => 107.70
                    ],
                    [
                        'descripcion' => 'Espuma viscoelástica',
                        'cantidad' => 1,
                        'precio_unitario' => 55.90,
                        'subtotal' => 55.90
                    ]
                ]),
                'forma_pago' => 'tarjeta',
                'fecha_pago' => null,
                'incluir_clausulas' => true,
            ],

            // Factura a Empresa (Hotel Playa Dorada)
            [
                'cliente_id' => $clientes[5], // Hotel Playa Dorada
                'trabajo_id' => null, // Factura por servicios varios
                'serie' => 'A',
                'numero' => 4,
                'fecha_emision' => '2024-03-01',
                'fecha_vencimiento' => '2024-04-01',
                'subtotal' => 413.22,
                'iva' => 21.00,
                'total' => 500.00,
                'estado' => 'pagada',
                'concepto' => 'Mantenimiento anual mobiliario tapizado',
                'observaciones' => 'Contrato anual Q1 2024',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Mantenimiento preventivo',
                        'cantidad' => 1,
                        'precio_unitario' => 300.00,
                        'subtotal' => 300.00
                    ],
                    [
                        'descripcion' => 'Reparación 2 sillas comedor',
                        'cantidad' => 2,
                        'precio_unitario' => 75.00,
                        'subtotal' => 150.00
                    ],
                    [
                        'descripcion' => 'Materiales de limpieza especial',
                        'cantidad' => 1,
                        'precio_unitario' => 50.00,
                        'subtotal' => 50.00
                    ]
                ]),
                'forma_pago' => 'transferencia',
                'fecha_pago' => '2024-03-10',
                'incluir_clausulas' => true,
            ],

            // Factura Simplificada (Serie B)
            [
                'cliente_id' => $clientes[3], // Ana López
                'trabajo_id' => null,
                'serie' => 'B',
                'numero' => 1,
                'fecha_emision' => '2024-02-15',
                'fecha_vencimiento' => null,
                'subtotal' => 24.79,
                'iva' => 21.00,
                'total' => 30.00,
                'estado' => 'pagada',
                'concepto' => 'Reparación menor cremallera cojín',
                'observaciones' => 'Servicio express',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Reparación cremallera cojín',
                        'cantidad' => 1,
                        'precio_unitario' => 15.00,
                        'subtotal' => 15.00
                    ],
                    [
                        'descripcion' => 'Cremallera nueva 40cm',
                        'cantidad' => 1,
                        'precio_unitario' => 4.75,
                        'subtotal' => 4.75
                    ],
                    [
                        'descripcion' => 'Mano de obra',
                        'cantidad' => 0.5,
                        'precio_unitario' => 20.00,
                        'subtotal' => 10.00
                    ]
                ]),
                'forma_pago' => 'efectivo',
                'fecha_pago' => '2024-02-15',
                'incluir_clausulas' => false,
            ],

            // Factura Vencida
            [
                'cliente_id' => $clientes[7], // Tech Solutions
                'trabajo_id' => $trabajos[7] ?? null, // TRAB-2024-008
                'serie' => 'A',
                'numero' => 5,
                'fecha_emision' => '2024-02-01',
                'fecha_vencimiento' => '2024-03-01',
                'subtotal' => 1446.28,
                'iva' => 21.00,
                'total' => 1750.00,
                'estado' => 'vencida',
                'concepto' => 'Reparación y tapizado de 20 sillas de oficina',
                'observaciones' => 'Recordar al cliente',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Reparación estructura sillas',
                        'cantidad' => 20,
                        'precio_unitario' => 25.00,
                        'subtotal' => 500.00
                    ],
                    [
                        'descripcion' => 'Tapizado completo',
                        'cantidad' => 20,
                        'precio_unitario' => 40.00,
                        'subtotal' => 800.00
                    ],
                    [
                        'descripcion' => 'Tela poliéster negro (40 metros)',
                        'cantidad' => 40,
                        'precio_unitario' => 15.25,
                        'subtotal' => 610.00
                    ],
                    [
                        'descripcion' => 'Espuma media densidad',
                        'cantidad' => 20,
                        'precio_unitario' => 24.75,
                        'subtotal' => 495.00
                    ],
                    [
                        'descripcion' => 'Descuento por volumen',
                        'cantidad' => 1,
                        'precio_unitario' => -155.72,
                        'subtotal' => -155.72
                    ]
                ]),
                'forma_pago' => 'transferencia',
                'fecha_pago' => null,
                'incluir_clausulas' => true,
            ],

            // Factura Borrador
            [
                'cliente_id' => $clientes[6], // Restaurante La Terraza
                'trabajo_id' => $trabajos[5] ?? null, // TRAB-2024-006
                'serie' => 'A',
                'numero' => 6,
                'fecha_emision' => now()->format('Y-m-d'),
                'fecha_vencimiento' => now()->addDays(30)->format('Y-m-d'),
                'subtotal' => 289.26,
                'iva' => 21.00,
                'total' => 350.00,
                'estado' => 'borrador',
                'concepto' => 'Diseño y fabricación banqueta para bar',
                'observaciones' => 'Pendiente aprobación presupuesto',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Diseño personalizado banqueta',
                        'cantidad' => 1,
                        'precio_unitario' => 100.00,
                        'subtotal' => 100.00
                    ],
                    [
                        'descripcion' => 'Fabricación estructura madera',
                        'cantidad' => 1,
                        'precio_unitario' => 120.00,
                        'subtotal' => 120.00
                    ],
                    [
                        'descripcion' => 'Cuero vaca negro (2.5 metros)',
                        'cantidad' => 2.5,
                        'precio_unitario' => 85.50,
                        'subtotal' => 213.75
                    ],
                    [
                        'descripcion' => 'Espuma alta densidad',
                        'cantidad' => 1,
                        'precio_unitario' => 32.50,
                        'subtotal' => 32.50
                    ],
                    [
                        'descripcion' => 'Ajuste presupuesto',
                        'cantidad' => 1,
                        'precio_unitario' => -177.49,
                        'subtotal' => -177.49
                    ]
                ]),
                'forma_pago' => null,
                'fecha_pago' => null,
                'incluir_clausulas' => true,
            ],

            // Factura Cancelada
            [
                'cliente_id' => $clientes[8], // Clínica Dental
                'trabajo_id' => $trabajos[8] ?? null, // TRAB-2024-009
                'serie' => 'C',
                'numero' => 1,
                'fecha_emision' => '2024-03-01',
                'fecha_vencimiento' => '2024-04-01',
                'subtotal' => 413.22,
                'iva' => 21.00,
                'total' => 500.00,
                'estado' => 'cancelada',
                'concepto' => 'Mantenimiento sillón dental - CANCELADA',
                'observaciones' => 'Cancelada por cliente antes de inicio',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Mantenimiento sillón dental',
                        'cantidad' => 1,
                        'precio_unitario' => 350.00,
                        'subtotal' => 350.00
                    ],
                    [
                        'descripcion' => 'Materiales especiales',
                        'cantidad' => 1,
                        'precio_unitario' => 150.00,
                        'subtotal' => 150.00
                    ]
                ]),
                'forma_pago' => null,
                'fecha_pago' => null,
                'incluir_clausulas' => true,
            ],

            // Factura Urgente del Colegio
            [
                'cliente_id' => $clientes[9], // Colegio San José
                'trabajo_id' => $trabajos[9] ?? null, // TRAB-2024-010
                'serie' => 'A',
                'numero' => 7,
                'fecha_emision' => now()->format('Y-m-d'),
                'fecha_vencimiento' => now()->addDays(15)->format('Y-m-d'),
                'subtotal' => 247.93,
                'iva' => 21.00,
                'total' => 300.00,
                'estado' => 'emitida',
                'concepto' => 'Reparación urgente 5 sillas aula infantil',
                'observaciones' => 'URGENTE - Necesario para uso inmediato',
                'lineas' => json_encode([
                    [
                        'descripcion' => 'Reparación estructura 5 sillas',
                        'cantidad' => 5,
                        'precio_unitario' => 25.00,
                        'subtotal' => 125.00
                    ],
                    [
                        'descripcion' => 'Refuerzo patas y uniones',
                        'cantidad' => 5,
                        'precio_unitario' => 15.00,
                        'subtotal' => 75.00
                    ],
                    [
                        'descripcion' => 'Materiales de refuerzo',
                        'cantidad' => 1,
                        'precio_unitario' => 50.00,
                        'subtotal' => 50.00
                    ],
                    [
                        'descripcion' => 'Servicio urgente',
                        'cantidad' => 1,
                        'precio_unitario' => 50.00,
                        'subtotal' => 50.00
                    ]
                ]),
                'forma_pago' => 'transferencia',
                'fecha_pago' => null,
                'incluir_clausulas' => true,
            ],
        ];

        foreach ($facturas as $factura) {
            // Generar número completo
            $numeroCompleto = $factura['serie'] . '-' . str_pad($factura['numero'], 4, '0', STR_PAD_LEFT);
            
            DB::table('facturas')->insert(array_merge($factura, [
                'numero_completo' => $numeroCompleto,
                'created_at' => $factura['fecha_emision'],
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Seeder de facturas ejecutado exitosamente!');
        $this->command->info('Total de facturas insertadas: ' . count($facturas));
        
        // Mostrar estadísticas
        $this->command->info("\nResumen por estado:");
        $estados = array_column($facturas, 'estado');
        $counts = array_count_values($estados);
        foreach ($counts as $estado => $cantidad) {
            $this->command->info("- $estado: $cantidad");
        }
        
        $this->command->info("\nResumen por serie:");
        $series = array_column($facturas, 'serie');
        $seriesCount = array_count_values($series);
        foreach ($seriesCount as $serie => $cantidad) {
            $this->command->info("- Serie $serie: $cantidad");
        }
        
        $totalFacturado = array_sum(array_column($facturas, 'total'));
        $this->command->info("\nTotal facturado: " . number_format($totalFacturado, 2) . " €");
    }
}