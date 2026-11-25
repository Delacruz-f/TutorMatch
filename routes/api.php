<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MateriaController;
use App\Http\Controllers\Api\TutorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API TutorMatch (pública salvo lo indicado)
|--------------------------------------------------------------------------
|
| Todos los controladores viven en App\Http\Controllers\Api y son
| independientes de los controladores web.
|
*/

// Autenticación
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Lectura pública
Route::get('/materias', [MateriaController::class, 'index'])->name('api.materias.index');
Route::get('/materias/{id}', [MateriaController::class, 'show'])->name('api.materias.show')
    ->whereNumber('id');

Route::get('/tutores', [TutorController::class, 'index'])->name('api.tutores.index');
Route::get('/tutores/{id}', [TutorController::class, 'show'])->name('api.tutores.show')
    ->whereNumber('id');

// Protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});
