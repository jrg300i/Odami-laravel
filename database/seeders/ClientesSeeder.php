<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        // Primero necesitamos al menos un usuario para la relación
        $userId = DB::table('users')->value('id');
        
        if (!$userId) {
            // Si no hay usuarios, creamos uno
            $userId = DB::table('users')->insertGetId([
                'name' => 'Administrador',
                'email' => 'admin@tapiceria.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $clientes = [
            // Clientes Particulares
            [
                'codigo_cliente' => 'CLI-001',
                'nombre' => 'Juan',
                'apellido' => 'García López',
                'email' => 'juan.garcia@email.com',
                'telefono' => '600123456',
                'direccion' => 'Calle Principal 123',
                'ciudad' => 'Madrid',
                'codigo_postal' => '28001',
                'notas' => 'Cliente habitual, paga puntualmente',
                'tipo' => 'particular',
                'dni_cif' => '12345678A',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-002',
                'nombre' => 'María',
                'apellido' => 'Rodríguez Sánchez',
                'email' => 'maria.rodriguez@email.com',
                'telefono' => '600234567',
                'direccion' => 'Avenida Central 45',
                'ciudad' => 'Barcelona',
                'codigo_postal' => '08001',
                'notas' => 'Prefiere telas de algodón',
                'tipo' => 'particular',
                'dni_cif' => '87654321B',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-003',
                'nombre' => 'Carlos',
                'apellido' => 'Martínez Fernández',
                'email' => 'carlos.martinez@email.com',
                'telefono' => '600345678',
                'direccion' => 'Plaza Mayor 67',
                'ciudad' => 'Valencia',
                'codigo_postal' => '46001',
                'notas' => 'Solicita presupuesto detallado',
                'tipo' => 'particular',
                'dni_cif' => '11223344C',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-004',
                'nombre' => 'Ana',
                'apellido' => 'López Gómez',
                'email' => 'ana.lopez@email.com',
                'telefono' => '600456789',
                'direccion' => 'Calle Nueva 89',
                'ciudad' => 'Sevilla',
                'codigo_postal' => '41001',
                'notas' => 'Cliente desde 2020',
                'tipo' => 'particular',
                'dni_cif' => '55667788D',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-005',
                'nombre' => 'Pedro',
                'apellido' => 'Sánchez Ruiz',
                'email' => 'pedro.sanchez@email.com',
                'telefono' => '600567890',
                'direccion' => 'Paseo del Parque 12',
                'ciudad' => 'Zaragoza',
                'codigo_postal' => '50001',
                'notas' => 'Encargó tapizado completo de sofá',
                'tipo' => 'particular',
                'dni_cif' => '99887766E',
                'activo' => true,
                'user_id' => $userId,
            ],

            // Clientes Empresa
            [
                'codigo_cliente' => 'CLI-006',
                'nombre' => 'Hotel',
                'apellido' => 'Playa Dorada',
                'email' => 'reservas@playadorada.com',
                'telefono' => '912345678',
                'direccion' => 'Avenida del Mar 234',
                'ciudad' => 'Málaga',
                'codigo_postal' => '29001',
                'notas' => 'Contrato anual de mantenimiento',
                'tipo' => 'empresa',
                'dni_cif' => 'A12345678',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-007',
                'nombre' => 'Restaurante',
                'apellido' => 'La Terraza',
                'email' => 'info@laterraza.com',
                'telefono' => '913456789',
                'direccion' => 'Calle Comercial 56',
                'ciudad' => 'Madrid',
                'codigo_postal' => '28002',
                'notas' => 'Necesita materiales resistentes',
                'tipo' => 'empresa',
                'dni_cif' => 'B23456789',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-008',
                'nombre' => 'Oficinas',
                'apellido' => 'Tech Solutions',
                'email' => 'compras@techsolutions.com',
                'telefono' => '914567890',
                'direccion' => 'Parque Tecnológico 78',
                'ciudad' => 'Bilbao',
                'codigo_postal' => '48001',
                'notas' => 'Pedido de 20 sillas de oficina',
                'tipo' => 'empresa',
                'dni_cif' => 'C34567890',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-009',
                'nombre' => 'Clínica',
                'apellido' => 'Dental Sonrisa',
                'email' => 'admin@dentalsonrisa.com',
                'telefono' => '915678901',
                'direccion' => 'Calle Salud 34',
                'ciudad' => 'Valencia',
                'codigo_postal' => '46002',
                'notas' => 'Materiales higiénicos especiales',
                'tipo' => 'empresa',
                'dni_cif' => 'D45678901',
                'activo' => true,
                'user_id' => $userId,
            ],
            [
                'codigo_cliente' => 'CLI-010',
                'nombre' => 'Colegio',
                'apellido' => 'San José',
                'email' => 'administracion@colegiosanjose.edu',
                'telefono' => '916789012',
                'direccion' => 'Avenida Educación 90',
                'ciudad' => 'Sevilla',
                'codigo_postal' => '41002',
                'notas' => 'Presupuesto para aula infantil',
                'tipo' => 'empresa',
                'dni_cif' => 'E56789012',
                'activo' => true,
                'user_id' => $userId,
            ],
        ];

        foreach ($clientes as $cliente) {
            DB::table('clientes')->insert(array_merge($cliente, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Seeder de clientes ejecutado exitosamente!');
        $this->command->info('Total de clientes insertados: ' . count($clientes));
        $this->command->info('Particulares: 5 | Empresas: 5');
    }
}