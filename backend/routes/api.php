<?php

use App\Http\Controllers\Api\ContactoController;
use App\Http\Controllers\Api\MensajeApiController;
use App\Http\Controllers\Api\PublicInmuebleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Pública — Stateless (sin sesión, sin auth requerida)
|--------------------------------------------------------------------------
*/

// ─── v1: endpoints versionados (retrocompatibilidad) ──────────────────────

Route::prefix('v1')->group(function () {
    Route::get('/inmuebles',            [PublicInmuebleController::class, 'index']);
    Route::get('/inmuebles/{inmueble}', [PublicInmuebleController::class, 'show']);
    Route::get('/categorias',           [PublicInmuebleController::class, 'categorias']);
    Route::post('/contacto',            [ContactoController::class, 'store'])->middleware('throttle:contacto');
});

// ─── Raíz: endpoints React frontend ───────────────────────────────────────

Route::get('/inmuebles',            [PublicInmuebleController::class, 'index']);
Route::get('/inmuebles/{inmueble}', [PublicInmuebleController::class, 'show']);
Route::get('/categorias',           [PublicInmuebleController::class, 'categorias']);

Route::post('/mensajes', [MensajeApiController::class, 'store'])
    ->middleware('throttle:contacto');
