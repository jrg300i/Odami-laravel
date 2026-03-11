<?php

namespace Database\Seeders;

use App\Models\CondicionTrabajo;
use Illuminate\Database\Seeder;

class CondicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $condiciones = [
            [
                'titulo' => 'Pago del 50% para comenzar',
                'descripcion' => 'Se debe abonar el 50% del total antes de comenzar el trabajo',
                'orden' => 1,
            ],
            [
                'titulo' => 'Pago completo al retirar',
                'descripcion' => 'El saldo restante debe ser cancelado al momento de retirar el trabajo',
                'orden' => 2,
            ],
            [
                'titulo' => 'Garantía de 30 días',
                'descripcion' => 'El trabajo tiene una garantía de 30 días por defectos de fabricación',
                'orden' => 3,
            ],
            [
                'titulo' => 'No incluye materiales adicionales',
                'descripcion' => 'El presupuesto no incluye materiales adicionales no contemplados inicialmente',
                'orden' => 4,
            ],
            [
                'titulo' => 'Tiempo de entrega estimado',
                'descripcion' => 'El tiempo de entrega es estimado y puede variar según la complejidad del trabajo',
                'orden' => 5,
            ],
            [
                'titulo' => 'Revisión al retirar',
                'descripcion' => 'El cliente debe revisar el trabajo al momento de retirar. Reclamos posteriores no serán considerados',
                'orden' => 6,
            ],
            [
                'titulo' => 'Almacenamiento gratuito 15 días',
                'descripcion' => 'El trabajo se almacena sin costo por 15 días después de notificado. Luego se cobrará almacenamiento',
                'orden' => 7,
            ],
            [
                'titulo' => 'No se aceptan devoluciones',
                'descripcion' => 'Una vez aprobado el trabajo, no se aceptan devoluciones ni reclamos por cambios de opinión',
                'orden' => 8,
            ],
        ];

        foreach ($condiciones as $condicion) {
            CondicionTrabajo::create([
                'titulo' => $condicion['titulo'],
                'descripcion' => $condicion['descripcion'],
                'activa' => true,
                'orden' => $condicion['orden'],
            ]);
        }
    }
}
