<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PagoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'monto' => $this->faker->randomFloat(2, 50, 5000),
            'fecha_pago' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'metodo_pago' => $this->faker->randomElement(['efectivo', 'transferencia', 'tarjeta']),
            'referencia' => 'PAGO-' . strtoupper($this->faker->bothify('??##-####')),
            'observaciones' => $this->faker->sentence(),
            'estado' => $this->faker->randomElement(['pendiente', 'completado', 'fallido']),
            'comprobante_path' => $this->faker->boolean(70) ? 
                'comprobantes/' . date('Y/m') . '/' . $this->faker->uuid() . '.pdf' : null,
        ];
    }
}