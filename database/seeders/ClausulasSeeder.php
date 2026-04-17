<?php

namespace Database\Seeders;

use App\Models\Clausula;
use Illuminate\Database\Seeder;

class ClausulasSeeder extends Seeder
{
    public function run()
    {
        $clausulas = [
            [
                'titulo' => 'Condiciones de Pago',
                'contenido' => 'El pago deberá realizarse en un plazo máximo de 30 días desde la fecha de emisión de la factura. Los pagos fuera de plazo devengarán intereses de mora según la ley vigente.',
                'orden' => 1,
                'activa' => true,
                'obligatoria' => true,
                'tipo' => 'pago',
            ],
            [
                'titulo' => 'Garantía del Trabajo',
                'contenido' => 'Se ofrece una garantía de 12 meses sobre los trabajos de tapicería realizados, que cubre defectos de materiales y mano de obra. La garantía no cubre desgaste normal ni daños por mal uso.',
                'orden' => 2,
                'activa' => true,
                'obligatoria' => true,
                'tipo' => 'garantia',
            ],
            [
                'titulo' => 'Plazo de Entrega',
                'contenido' => 'Los plazos de entrega son estimados y pueden verse afectados por factores externos. Nos reservamos el derecho de modificar los plazos previa comunicación al cliente.',
                'orden' => 3,
                'activa' => true,
                'obligatoria' => false,
                'tipo' => 'entrega',
            ],
            [
                'titulo' => 'Cambios y Devoluciones',
                'contenido' => 'No se admiten devoluciones una vez iniciado el trabajo. Los cambios en los materiales seleccionados podrán realizarse siempre que no se haya comenzado el trabajo con los mismos.',
                'orden' => 4,
                'activa' => true,
                'obligatoria' => false,
                'tipo' => 'devoluciones',
            ],
        ];

        foreach ($clausulas as $clausula) {
            Clausula::create($clausula);
        }
    }
}