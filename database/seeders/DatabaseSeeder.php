<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            ConfiguracionesSeeder::class,
            ClientesSeeder::class,
            MaterialesSeeder::class,
            TrabajosSeeder::class,
            FacturasSeeder::class,
            ClausulasSeeder::class,
            FotosTrabajosSeeder::class,
            PagosSeeder::class,


        ]);
    }
}