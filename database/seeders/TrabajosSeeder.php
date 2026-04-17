<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrabajosSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar que existen clientes y usuarios
        $clientes = DB::table('clientes')->pluck('id')->toArray();
        $usuarios = DB::table('users')->pluck('id')->toArray();
        
        if (empty($clientes) || empty($usuarios)) {
            $this->command->error('Primero ejecuta los seeders de clientes y usuarios!');
            return;
        }

        $trabajos = [
            // Trabajos Completados
            [
                'cliente_id' => $clientes[0], // Juan García
                'user_id' => $usuarios[1] ?? $usuarios[0], // Tapicero
                'codigo_trabajo' => 'TRAB-2024-001',
                'titulo' => 'Tapizado completo de sofá 3 plazas',
                'descripcion' => 'Tapizado completo con tela de lino beige, cambio de espumas',
                'tipo' => 'sofa',
                'estado' => 'entregado',
                'costo_estimado' => 450.00,
                'costo_final' => 420.00,
                'fecha_inicio' => '2024-01-15',
                'fecha_fin_estimada' => '2024-01-30',
                'fecha_fin_real' => '2024-01-28',
                'prioridad' => 2,
                'notas_internas' => 'Cliente satisfecho, pagó al contado',
                'observaciones_cliente' => 'Quiero que quede como nuevo',
                'urgente' => false,
            ],
            [
                'cliente_id' => $clientes[1], // María Rodríguez
                'user_id' => $usuarios[1] ?? $usuarios[0],
                'codigo_trabajo' => 'TRAB-2024-002',
                'titulo' => 'Restauración silla antigua',
                'descripcion' => 'Restauración de silla de madera con tapizado en terciopelo rojo',
                'tipo' => 'silla',
                'estado' => 'entregado',
                'costo_estimado' => 180.00,
                'costo_final' => 195.00,
                'fecha_inicio' => '2024-02-10',
                'fecha_fin_estimada' => '2024-02-25',
                'fecha_fin_real' => '2024-02-20',
                'prioridad' => 3,
                'notas_internas' => 'Material adicional requerido',
                'observaciones_cliente' => 'Es una silla heredada de mi abuela',
                'urgente' => false,
            ],
            [
                'cliente_id' => $clientes[4], // Pedro Sánchez
                'user_id' => $usuarios[0], // Admin
                'codigo_trabajo' => 'TRAB-2024-003',
                'titulo' => 'Fabricación butaca personalizada',
                'descripcion' => 'Butaca reclinable con mecanismo especial',
                'tipo' => 'butaca',
                'estado' => 'entregado',
                'costo_estimado' => 320.00,
                'costo_final' => 310.00,
                'fecha_inicio' => '2024-03-05',
                'fecha_fin_estimada' => '2024-03-20',
                'fecha_fin_real' => '2024-03-18',
                'prioridad' => 2,
                'notas_internas' => 'Mecanismo especial importado',
                'observaciones_cliente' => 'Para mi sala de lectura',
                'urgente' => false,
            ],

            // Trabajos en Proceso
            [
                'cliente_id' => $clientes[5], // Hotel Playa Dorada
                'user_id' => $usuarios[1] ?? $usuarios[0],
                'codigo_trabajo' => 'TRAB-2024-004',
                'titulo' => 'Lote de 10 sillas de comedor',
                'descripcion' => 'Tapizado de 10 sillas de comedor del hotel',
                'tipo' => 'silla',
                'estado' => 'en_proceso',
                'costo_estimado' => 1200.00,
                'costo_final' => null,
                'fecha_inicio' => '2024-04-01',
                'fecha_fin_estimada' => '2024-04-30',
                'fecha_fin_real' => null,
                'prioridad' => 1,
                'notas_internas' => 'Usar materiales resistentes a humedad',
                'observaciones_cliente' => 'Necesitamos para temporada alta',
                'urgente' => true,
            ],
            [
                'cliente_id' => $clientes[2], // Carlos Martínez
                'user_id' => $usuarios[0],
                'codigo_trabajo' => 'TRAB-2024-005',
                'titulo' => 'Sillón orejero',
                'descripcion' => 'Tapizado completo de sillón orejero',
                'tipo' => 'sillon',
                'estado' => 'en_proceso',
                'costo_estimado' => 280.00,
                'costo_final' => null,
                'fecha_inicio' => '2024-04-10',
                'fecha_fin_estimada' => '2024-04-25',
                'fecha_fin_real' => null,
                'prioridad' => 2,
                'notas_internas' => 'Esperando entrega de tela',
                'observaciones_cliente' => 'Quiero color gris oscuro',
                'urgente' => false,
            ],

            // Trabajos en Presupuesto
            [
                'cliente_id' => $clientes[6], // Restaurante La Terraza
                'user_id' => $usuarios[2] ?? $usuarios[0], // Atención al cliente
                'codigo_trabajo' => 'TRAB-2024-006',
                'titulo' => 'Banqueta bar personalizada',
                'descripcion' => 'Diseño y fabricación de banqueta para bar',
                'tipo' => 'personalizado',
                'estado' => 'presupuesto',
                'costo_estimado' => 350.00,
                'costo_final' => null,
                'fecha_inicio' => null,
                'fecha_fin_estimada' => null,
                'fecha_fin_real' => null,
                'prioridad' => 3,
                'notas_internas' => 'Enviar presupuesto detallado',
                'observaciones_cliente' => 'Necesito ver muestras de cuero',
                'urgente' => false,
            ],
            [
                'cliente_id' => $clientes[3], // Ana López
                'user_id' => $usuarios[2] ?? $usuarios[0],
                'codigo_trabajo' => 'TRAB-2024-007',
                'titulo' => 'Cabecero cama matrimonial',
                'descripcion' => 'Cabecero acolchado con botones',
                'tipo' => 'cabecero',
                'estado' => 'presupuesto',
                'costo_estimado' => 220.00,
                'costo_final' => null,
                'fecha_inicio' => null,
                'fecha_fin_estimada' => null,
                'fecha_fin_real' => null,
                'prioridad' => 2,
                'notas_internas' => 'Cliente indecisa sobre color',
                'observaciones_cliente' => '¿Pueden enviarme fotos de trabajos similares?',
                'urgente' => false,
            ],

            // Trabajo Completado pero no entregado
            [
                'cliente_id' => $clientes[7], // Tech Solutions
                'user_id' => $usuarios[1] ?? $usuarios[0],
                'codigo_trabajo' => 'TRAB-2024-008',
                'titulo' => '20 sillas de oficina',
                'descripcion' => 'Reparación y tapizado de sillas de oficina',
                'tipo' => 'silla',
                'estado' => 'completado',
                'costo_estimado' => 1800.00,
                'costo_final' => 1750.00,
                'fecha_inicio' => '2024-03-20',
                'fecha_fin_estimada' => '2024-04-15',
                'fecha_fin_real' => '2024-04-10',
                'prioridad' => 1,
                'notas_internas' => 'Esperando que cliente recoja',
                'observaciones_cliente' => 'Coordinaremos recogida con logística',
                'urgente' => false,
            ],

            // Trabajo Cancelado
            [
                'cliente_id' => $clientes[8], // Clínica Dental
                'user_id' => $usuarios[0],
                'codigo_trabajo' => 'TRAB-2024-009',
                'titulo' => 'Sillón dental',
                'descripcion' => 'Mantenimiento de sillón dental',
                'tipo' => 'personalizado',
                'estado' => 'cancelado',
                'costo_estimado' => 500.00,
                'costo_final' => null,
                'fecha_inicio' => '2024-02-28',
                'fecha_fin_estimada' => '2024-03-15',
                'fecha_fin_real' => null,
                'prioridad' => 2,
                'notas_internas' => 'Cancelado por cliente, encontró otro proveedor',
                'observaciones_cliente' => 'Lo siento, necesito más rápido',
                'urgente' => false,
            ],

            // Trabajo Urgente
            [
                'cliente_id' => $clientes[9], // Colegio San José
                'user_id' => $usuarios[1] ?? $usuarios[0],
                'codigo_trabajo' => 'TRAB-2024-010',
                'titulo' => 'Reparación sillas aula infantil',
                'descripcion' => 'Reparación urgente de 5 sillas del aula',
                'tipo' => 'silla',
                'estado' => 'en_proceso',
                'costo_estimado' => 300.00,
                'costo_final' => null,
                'fecha_inicio' => '2024-04-12',
                'fecha_fin_estimada' => '2024-04-19',
                'fecha_fin_real' => null,
                'prioridad' => 1,
                'notas_internas' => 'URGENTE - Aula sin mobiliario',
                'observaciones_cliente' => 'Necesitamos para el lunes',
                'urgente' => true,
            ],
        ];

        foreach ($trabajos as $trabajo) {
            DB::table('trabajos')->insert(array_merge($trabajo, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Seeder de trabajos ejecutado exitosamente!');
        $this->command->info('Total de trabajos insertados: ' . count($trabajos));
        
        // Mostrar estadísticas
        $estados = array_column($trabajos, 'estado');
        $counts = array_count_values($estados);
        foreach ($counts as $estado => $cantidad) {
            $this->command->info("- $estado: $cantidad");
        }
    }
}