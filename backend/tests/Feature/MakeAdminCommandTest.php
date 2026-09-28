<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MakeAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_crea_usuario_admin(): void
    {
        $this->artisan('make:admin', ['--name' => 'Admin', '--email' => 'admin@test.com'])
            ->expectsQuestion('Contraseña', 'Secreta12345')
            ->expectsQuestion('Confirma la contraseña', 'Secreta12345')
            ->assertSuccessful();

        $this->assertSame('admin', User::where('email', 'admin@test.com')->first()->role);
    }

    public function test_rechaza_email_duplicado(): void
    {
        User::factory()->create(['email' => 'admin@test.com']);

        $this->artisan('make:admin', ['--name' => 'Admin', '--email' => 'admin@test.com'])
            ->expectsQuestion('Contraseña', 'Secreta12345')
            ->expectsQuestion('Confirma la contraseña', 'Secreta12345')
            ->assertFailed();
    }
}
