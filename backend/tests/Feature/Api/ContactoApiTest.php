<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactoApiTest extends TestCase
{
    use RefreshDatabase;

    private array $datosValidos = [
        'nombre'  => 'Juan Pérez',
        'email'   => 'juan@gmail.com',
        'mensaje' => 'Estoy interesado en conocer más sobre sus propiedades disponibles.',
        'tipo'    => 'contacto',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_contacto_exitoso_retorna_201(): void
    {
        $response = $this->postJson('/api/v1/contacto', $this->datosValidos);

        $response->assertStatus(201);
    }

    public function test_contacto_sin_nombre_retorna_422(): void
    {
        $datos = $this->datosValidos;
        unset($datos['nombre']);

        $response = $this->postJson('/api/v1/contacto', $datos);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre']);
    }

    public function test_contacto_sin_email_retorna_422(): void
    {
        $datos = $this->datosValidos;
        unset($datos['email']);

        $response = $this->postJson('/api/v1/contacto', $datos);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_contacto_sin_mensaje_retorna_422(): void
    {
        $datos = $this->datosValidos;
        unset($datos['mensaje']);

        $response = $this->postJson('/api/v1/contacto', $datos);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['mensaje']);
    }

    public function test_contacto_mensaje_demasiado_corto_retorna_422(): void
    {
        $datos             = $this->datosValidos;
        $datos['mensaje']  = 'Hola';

        $response = $this->postJson('/api/v1/contacto', $datos);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['mensaje']);
    }

    public function test_contacto_payload_vacio_retorna_422(): void
    {
        $response = $this->postJson('/api/v1/contacto', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre', 'email', 'mensaje', 'tipo']);
    }

    public function test_respuesta_exitosa_tiene_estructura_esperada(): void
    {
        $response = $this->postJson('/api/v1/contacto', $this->datosValidos);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message']);
    }
}
