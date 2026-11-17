<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Tutor\ClaseTutorController;
use App\Http\Controllers\Tutor\ResenaTutorController;
use App\Http\Controllers\Tutor\DisponibilidadTutorController;
use App\Http\Controllers\RetiroController;
use App\Http\Controllers\SuscripcionController;

/*
|--------------------------------------------------------------------------
| Tutor
| Clases, Retiros, Suscripciones, Reseñas y Disponibilidad
|--------------------------------------------------------------------------
|
| Este archivo se carga desde web.php dentro del grupo
| ['auth', 'verified'] y aplica role:tutor.
|
*/

Route::middleware('role:tutor')
    ->prefix('tutor')
    ->name('tutor.')
    ->group(function () {

        // Gestión de clases
        Route::controller(ClaseTutorController::class)->group(function () {
            Route::post('/clases/{id}/aceptar', 'aceptar')->name('clases.aceptar');
            Route::post('/clases/{id}/rechazar', 'rechazar')->name('clases.rechazar');
            Route::post('/clases/{id}/completar', 'completar')->name('clases.completar');
        });

        // Retiros
        Route::controller(RetiroController::class)->group(function () {
            Route::get('/retiros', 'index')->name('retiros.index');
            Route::post('/retiros', 'store')->name('retiros.store');
        });

        // Reseñas del Tutor
        Route::controller(ResenaTutorController::class)->group(function () {
            Route::get('/resenas', 'index')->name('resenas.index');
            Route::post('/resenas/{id}/responder', 'responder')->name('resenas.responder');
            Route::delete('/resenas/{id}/respuesta', 'eliminarRespuesta')->name('resenas.eliminar-respuesta');
        });

        // Disponibilidad del Tutor
        Route::controller(DisponibilidadTutorController::class)->group(function () {
            Route::get('/disponibilidad', 'index')->name('disponibilidad.index');
            Route::post('/disponibilidad', 'store')->name('disponibilidad.store');
        });

        // Suscripciones
        Route::controller(SuscripcionController::class)->group(function () {
            Route::get('/suscripciones', 'index')->name('suscripciones.index');
            Route::post('/suscripciones', 'store')->name('suscripciones.store');
        });
    });
