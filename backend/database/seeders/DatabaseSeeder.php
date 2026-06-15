<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedUsers();
        $this->call(PlantillaCorreoSeeder::class);
    }

    private function seedCategories(): void
    {
        $names = ['Casa', 'Apartamento', 'Terreno', 'Local Comercial'];

        foreach ($names as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }

    private function seedUsers(): void
    {
        User::create([
            'name'     => 'Admin Galdámez',
            'email'    => 'admin@galdamez.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'phone'    => null,
        ]);

        User::create([
            'name'     => 'Agente de Prueba',
            'email'    => 'agente@galdamez.com',
            'password' => Hash::make('password123'),
            'role'     => 'agente',
            'phone'    => null,
        ]);
    }
}
