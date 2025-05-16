<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\PersonaController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {

        // Redirección dinámica según el rol del usuario
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('tecnico_analista')) {
            return redirect()->route('personas.revisiones');
        }

        return Inertia::render('Dashboard');
    })->name('dashboard');


     // Rutas para usuario_normal
    Route::middleware('role:usuario_normal')->group(function () {
        Route::get('/personas', [PersonaController::class, 'index'])->name('personas.index');
        Route::get('/personas/crear', [PersonaController::class, 'crear'])->name('personas.crear');
        Route::post('/personas', [PersonaController::class, 'store'])->name('personas.store');
    });

    // Rutas para tecnico_analista
    Route::get('/personas', [PersonaController::class, 'index'])->name('personas.index');
    Route::get('/personas/crear', [PersonaController::class, 'crear'])->name('personas.crear');
    Route::post('/personas', [PersonaController::class, 'store'])->name('personas.store');
    Route::get('/personas/{persona}/editar', [PersonaController::class, 'editar'])->name('personas.editar');
    Route::put('/personas/{persona}', [PersonaController::class, 'actualizar'])->name('personas.actualizar');

    Route::get('/revisiones', [PersonaController::class, 'revisiones'])->name('personas.revisiones');

});
