<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PlantillaCorreo;
use Database\Seeders\ProductionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_es_idempotente(): void
    {
        $this->seed(ProductionSeeder::class);
        $this->seed(ProductionSeeder::class);

        $this->assertSame(4, Category::count());
        $this->assertSame(2, PlantillaCorreo::count());
    }
}
