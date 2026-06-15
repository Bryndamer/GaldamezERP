<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Inmueble;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inmueble>
 */
class InmuebleFactory extends Factory
{
    protected $model = Inmueble::class;

    private static array $colonias = [
        'Col. Escalón', 'Col. San Benito', 'Col. Flor Blanca', 'Res. Santa Elena',
        'Col. Médica', 'Col. La Mascota', 'Urb. Merliot', 'Col. Jardines de Guadalupe',
        'Res. Altavista', 'Col. Centroamérica', 'Urb. Los Héroes', 'Col. La Sultana',
        'Bo. San Jacinto', 'Col. Miramonte', 'Urb. Bello Horizonte',
    ];

    private static array $municipios = [
        'San Salvador', 'Santa Tecla', 'Antiguo Cuscatlán', 'Soyapango',
        'Mejicanos', 'Apopa', 'San Marcos', 'Ilopango', 'Ciudad Delgado',
    ];

    public function definition(): array
    {
        $tipo = fake()->randomElement(['casa', 'apto', 'terreno']);
        $esTerrreno = $tipo === 'terreno';

        return [
            'user_id'      => User::factory(),
            'category_id'  => Category::factory(),
            'titulo'       => $this->generarTitulo($tipo),
            'descripcion'  => fake()->paragraphs(2, true),
            'precio'       => fake()->randomFloat(2, 15_000, 500_000),
            'direccion'    => $this->generarDireccion(),
            'habitaciones' => $esTerrreno ? 0 : fake()->numberBetween(1, 6),
            'banos'        => $esTerrreno ? 0 : fake()->numberBetween(1, 4),
            'metraje'      => fake()->randomFloat(2, 60, 800),
            'estado'       => fake()->randomElement([
                'disponible', 'disponible', 'disponible', 'vendido', 'reservado',
            ]),
            'tipo'  => $tipo,
            'fotos' => [],
        ];
    }

    private function generarTitulo(string $tipo): string
    {
        $prefijos = [
            'casa'    => ['Hermosa casa', 'Casa amplia', 'Casa familiar', 'Casa moderna'],
            'apto'    => ['Apartamento céntrico', 'Apto moderno', 'Apartamento ejecutivo', 'Apto luminoso'],
            'terreno' => ['Terreno plano', 'Terreno esquinero', 'Lote residencial', 'Terreno con acceso'],
        ];
        $colonia = fake()->randomElement(self::$colonias);

        return fake()->randomElement($prefijos[$tipo]) . ' en ' . $colonia;
    }

    private function generarDireccion(): string
    {
        $colonia = fake()->randomElement(self::$colonias);
        $municipio = fake()->randomElement(self::$municipios);
        $calle = fake()->numerify('Calle ##');
        $numero = fake()->numerify('#-##');

        return "{$calle} {$numero}, {$colonia}, {$municipio}, El Salvador";
    }
}
