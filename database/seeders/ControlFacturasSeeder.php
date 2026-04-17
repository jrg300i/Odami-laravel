<?php

namespace Database\Seeders;

use App\Models\ControlFactura;
use Illuminate\Database\Seeder;

class ControlFacturasSeeder extends Seeder
{
    public function run()
    {
        $series = [
            [
                'serie' => 'A',
                'descripcion' => 'Facturación General',
                'ultimo_numero' => 0,
                'activo' => true,
                'numero_inicio' => 1,
            ],
            [
                'serie' => 'B',
                'descripcion' => 'Facturación Exportación',
                'ultimo_numero' => 0,
                'activo' => false,
                'numero_inicio' => 1,
            ],
            [
                'serie' => 'C',
                'descripcion' => 'Facturación Servicios',
                'ultimo_numero' => 0,
                'activo' => false,
                'numero_inicio' => 1,
            ],
        ];

        foreach ($series as $serie) {
            ControlFactura::create($serie);
        }
    }
}