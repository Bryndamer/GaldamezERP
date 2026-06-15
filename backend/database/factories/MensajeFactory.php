<?php

namespace Database\Factories;

use App\Models\Inmueble;
use App\Models\Mensaje;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mensaje>
 */
class MensajeFactory extends Factory
{
    protected $model = Mensaje::class;

    public function definition(): array
    {
        return [
            'inmueble_id' => Inmueble::factory(),
            'nombre'      => fake()->name(),
            'email'       => fake()->safeEmail(),
            'telefono'    => fake()->numerify('7###-####'),
            'mensaje'     => fake()->paragraph(3),
            'tipo'        => 'contacto',
            'leido'       => fake()->boolean(30),
        ];
    }
}
