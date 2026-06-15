<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InmueblesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_inmuebles_retorna_200(): void
    {
        $response = $this->getJson('/api/v1/inmuebles');

        $response->assertStatus(200);
    }

    public function test_respuesta_tiene_estructura_paginada(): void
    {
        $response = $this->getJson('/api/v1/inmuebles');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'links',
                     'meta',
                 ]);
    }

    public function test_data_es_un_array(): void
    {
        $response = $this->getJson('/api/v1/inmuebles');

        $this->assertIsArray($response->json('data'));
    }

    public function test_filtro_por_tipo_acepta_valor_valido(): void
    {
        $response = $this->getJson('/api/v1/inmuebles?tipo=casa');

        $response->assertStatus(200);
    }

    public function test_endpoint_detalle_retorna_404_para_id_inexistente(): void
    {
        $response = $this->getJson('/api/v1/inmuebles/999999');

        $response->assertStatus(404);
    }
}
