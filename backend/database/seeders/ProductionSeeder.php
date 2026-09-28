<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Se ejecuta en cada deploy (post-deploy del workflow).
 * Debe poder correr N veces sin duplicar datos.
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Casa', 'Apartamento', 'Terreno', 'Local Comercial'] as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        $this->call(PlantillaCorreoSeeder::class);
    }
}
