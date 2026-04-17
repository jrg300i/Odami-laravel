<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@tapiceria.com',
            'password' => Hash::make('password123'),
            'phone' => '+52 55 1234 5678',
            'address' => 'Av. Principal #123, CDMX',
            'is_active' => true,
        ]);
        
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // Crear usuario tapicero
        $tapicero = User::create([
            'name' => 'Juan Pérez',
            'email' => 'tapicero@tapiceria.com',
            'password' => Hash::make('password123'),
            'phone' => '+52 55 8765 4321',
            'address' => 'Calle Secundaria #456, CDMX',
            'is_active' => true,
        ]);
        
        $tapiceroRole = Role::where('name', 'tapicero')->first();
        $tapicero->roles()->attach($tapiceroRole);

        // Crear usuario cliente
        $cliente = User::create([
            'name' => 'María García',
            'email' => 'cliente@ejemplo.com',
            'password' => Hash::make('password123'),
            'phone' => '+52 55 5555 5555',
            'address' => 'Calle Ejemplo #789, CDMX',
            'is_active' => true,
        ]);
        
        $clienteRole = Role::where('name', 'cliente')->first();
        $cliente->roles()->attach($clienteRole);

        // Crear más usuarios de prueba
        $users = [
            [
                'name' => 'Carlos López',
                'email' => 'carlos@tapiceria.com',
                'role' => 'tapicero',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@ejemplo.com',
                'role' => 'cliente',
            ],
            [
                'name' => 'Roberto Sánchez',
                'email' => 'roberto@ejemplo.com',
                'role' => 'cliente',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'phone' => '+52 55 ' . rand(1000, 9999) . ' ' . rand(1000, 9999),
                'address' => 'Dirección ' . rand(1, 1000),
                'is_active' => true,
            ]);
            
            $role = Role::where('name', $userData['role'])->first();
            $user->roles()->attach($role);
        }

        $this->command->info('Usuarios de prueba creados exitosamente.');
    }
}