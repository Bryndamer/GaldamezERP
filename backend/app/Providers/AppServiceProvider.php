<?php

namespace App\Providers;

use App\Models\Inmueble;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureGates();
    }

    private function configureRateLimiting(): void
    {
        // Máximo 5 intentos por minuto por IP + email para prevenir fuerza bruta
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip() . '|' . strtolower((string) $request->input('email')))
                ->response(function () {
                    return back()->withErrors([
                        'email' => 'Demasiados intentos. Por favor espera 1 minuto antes de intentarlo de nuevo.',
                    ]);
                });
        });

        // Anti-spam: máximo 5 mensajes por hora por IP para el formulario de contacto
        RateLimiter::for('contacto', function (Request $request) {
            return Limit::perHour(5)
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Has enviado demasiados mensajes. Por favor espera antes de intentarlo de nuevo.',
                    ], 429);
                });
        });
    }

    private function configureGates(): void
    {
        // Admin: gestión total. Agente: solo sus propios inmuebles.
        Gate::define('manage-inmueble', function (User $user, Inmueble $inmueble): bool {
            if ($user->role === 'admin') {
                return true;
            }

            return $user->role === 'agente' && $user->id === $inmueble->user_id;
        });
    }
}
