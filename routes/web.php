<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', fn () => view('dashboard.index'))->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('admin')->group(function (): void {
        Route::resource('usuarios', UsuarioController::class)->except(['index', 'show']);
        Route::resource('clientes', ClienteController::class)->only(['edit', 'update', 'destroy']);
    });

    Route::resource('usuarios', UsuarioController::class)->only(['index', 'show']);
    Route::resource('clientes', ClienteController::class)->only(['index', 'create', 'store', 'show']);
});
