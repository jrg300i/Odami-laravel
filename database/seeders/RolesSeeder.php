<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrador del sistema con acceso completo',
                'color' => '#EF4444',
                'level' => 100,
                'permissions' => json_encode([
                    'users.manage',
                    'roles.manage',
                    'clientes.manage',
                    'trabajos.manage',
                    'facturas.manage',
                    'pagos.manage',
                    'fotos.manage',
                    'materiales.manage',
                    'clausulas.manage',
                    'backups.manage',
                    'config.system',
                    'reportes.view',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'tapicero',
                'description' => 'Tapicero encargado de realizar trabajos',
                'color' => '#3B82F6',
                'level' => 50,
                'permissions' => json_encode([
                    'clientes.view',
                    'trabajos.manage',
                    'fotos.manage',
                    'materiales.view',
                    'reportes.view',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'cliente',
                'description' => 'Cliente que contrata servicios de tapicería',
                'color' => '#10B981',
                'level' => 10,
                'permissions' => json_encode([
                    'clientes.own',
                    'trabajos.own',
                    'facturas.own',
                    'pagos.own',
                    'fotos.own',
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('Roles creados exitosamente.');
    }
}