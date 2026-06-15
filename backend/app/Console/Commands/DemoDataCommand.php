<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Inmueble;
use App\Models\Mensaje;
use App\Models\User;
use Database\Seeders\PlantillaCorreoSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataCommand extends Command
{
    protected $signature = 'demodata
                            {--fresh : Ejecutar migrate:fresh antes de insertar datos}';

    protected $description = 'Inserta datos de demostración en todas las tablas';

    public function handle(): int
    {
        if (! $this->confirm('⚠️  Esto borrará TODOS los datos actuales. ¿Continuar?')) {
            $this->info('Operación cancelada.');
            return self::SUCCESS;
        }

        if ($this->option('fresh')) {
            $this->info('Ejecutando migrate:fresh...');
            Artisan::call('migrate:fresh', [], $this->output);
        } else {
            $this->truncateTables();
        }

        $this->newLine();
        $this->line('<fg=cyan>Insertando datos de demostración...</>');

        $categories = $this->createCategories();
        $users      = $this->createUsers();
        $inmuebles  = $this->createInmuebles($users, $categories);
        $mensajes   = $this->createMensajes($inmuebles);
        $this->seedPlantillas();

        $this->newLine();
        $this->table(
            ['Tabla', 'Registros'],
            [
                ['categories',      $categories->count()],
                ['users',           $users->count()],
                ['inmuebles',       $inmuebles->count()],
                ['mensajes',        $mensajes],
                ['plantillas_correo', 2],
            ]
        );

        $this->newLine();
        $this->line('<fg=green>✅ Datos de demostración insertados correctamente.</>');
        $this->line('   <fg=yellow>Admin:</> admin@galdamez.com / <fg=yellow>password123</>');
        $this->line('   <fg=yellow>Agente:</> agente@galdamez.com / <fg=yellow>password123</>');

        return self::SUCCESS;
    }

    private function truncateTables(): void
    {
        $this->line('<fg=yellow>Limpiando tablas...</>');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('mensajes')->truncate();
        DB::table('inmuebles')->truncate();
        DB::table('personal_access_tokens')->truncate();
        DB::table('sessions')->truncate();
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('plantillas_correo')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function createCategories()
    {
        $this->line('  → Categorías...');

        $names = ['Casa', 'Apartamento', 'Terreno', 'Local Comercial', 'Bodega'];
        foreach ($names as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        return Category::all();
    }

    private function createUsers()
    {
        $this->line('  → Usuarios...');

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

        User::factory()->count(2)->create(['role' => 'agente']);

        return User::all();
    }

    private function createInmuebles($users, $categories)
    {
        $this->line('  → Inmuebles (30)...');

        Inmueble::factory()
            ->count(30)
            ->recycle($users)
            ->recycle($categories)
            ->create();

        return Inmueble::all();
    }

    private function createMensajes($inmuebles): int
    {
        $this->line('  → Mensajes (20)...');

        Mensaje::factory()
            ->count(20)
            ->recycle($inmuebles)
            ->create();

        return 20;
    }

    private function seedPlantillas(): void
    {
        $this->line('  → Plantillas de correo...');
        $this->call(PlantillaCorreoSeeder::class);
    }
}
