<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PTVarianteController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\Auth\LoginController;

// Rutas públicas (sin login)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')   // Máx 5 intentos por minuto
        ->name('login.post');
});

// Logout (solo autenticados)
Route::post('logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rutas protegidas por login
Route::middleware('auth')->group(function () {

    // Dashboard / inicio
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Catálogo
    Route::prefix('catalogo')->group(function () {
        Route::resource('articulos', ArticuloController::class)
            ->parameters(['articulos' => 'articulo'])
            ->only(['index','create','store','edit','update']);
    });

    // Inventario
    Route::prefix('inventario')->group(function () {

        // API de stock
        Route::get('stock', [StockController::class, 'stock'])->name('inventario.stock');

        // API de variantes PT
        Route::get('pt-variantes/{articulo}', [PTVarianteController::class, 'porArticulo'])
            ->name('pt-variantes.por-articulo');

        // Movimientos
        Route::resource('movimientos', MovimientoController::class)
            ->only(['index','create','store']);
    });
});
