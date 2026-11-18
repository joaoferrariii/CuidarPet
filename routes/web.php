<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\RegistroServicoController;
use App\Http\Controllers\VacinacaoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('clientes', ClienteController::class);
    Route::resource('pets', PetController::class);

    Route::resource('vacinacoes', VacinacaoController::class)
        ->parameters(['vacinacoes' => 'vacinacao'])
        ->only(['index', 'create', 'store', 'destroy']);

    Route::resource('registros-servicos', RegistroServicoController::class)
        ->parameters(['registros-servicos' => 'registroServico'])
        ->only(['index', 'create', 'store', 'destroy']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
