<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriasApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_categorias_retorna_200(): void
    {
        $response = $this->getJson('/api/v1/categorias');

        $response->assertStatus(200);
    }

    public function test_respuesta_es_un_array(): void
    {
        $response = $this->getJson('/api/v1/categorias');

        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }

    public function test_respuesta_tiene_content_type_json(): void
    {
        $response = $this->getJson('/api/v1/categorias');

        $response->assertHeader('Content-Type', 'application/json');
    }
}
