<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InmuebleController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\PlantillaCorreoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── Autenticación ────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])
        ->middleware('throttle:login')
        ->name('login.authenticate');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Panel Admin (solo role:admin) ────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        Route::resource('categorias', CategoryController::class)
            ->except(['show'])
            ->parameters(['categorias' => 'category']);

        Route::resource('users', UserController::class)
            ->except(['show']);

        Route::resource('mensajes', MensajeController::class)
            ->only(['index', 'destroy']);
        Route::patch('mensajes/{mensaje}/leer', [MensajeController::class, 'markRead'])
            ->name('mensajes.leer');
        Route::post('mensajes/{mensaje}/reenviar', [MensajeController::class, 'reenviarCorreos'])
            ->name('mensajes.reenviar');

        Route::resource('plantillas', PlantillaCorreoController::class)
            ->only(['index', 'edit', 'update'])
            ->parameters(['plantillas' => 'plantilla']);
    });

// ─── Inmuebles (admin + agente — Gate controla el alcance) ───────────────────

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('inmuebles', InmuebleController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

// ─── Portal Agente ────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:agente'])
    ->prefix('agente')
    ->name('agente.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('agente.dashboard'))->name('dashboard');
    });

// ─── Raíz ─────────────────────────────────────────────────────────────────────

Route::get('/', fn () => redirect()->route('login'));
