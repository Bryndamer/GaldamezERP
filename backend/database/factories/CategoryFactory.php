<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $base = fake()->randomElement([
            'Casa', 'Apartamento', 'Terreno', 'Local Comercial',
            'Bodega', 'Oficina', 'Galera', 'Quinta',
        ]);
        $name = $base . ' ' . fake()->lexify('??');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
