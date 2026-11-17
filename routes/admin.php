<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TutorController;
use App\Http\Controllers\Admin\TransaccionAdminController;
use App\Http\Controllers\Admin\SuscripcionAdminController;
use App\Http\Controllers\Admin\TutorApprovalController;
use App\Http\Controllers\Admin\PagoApprovalController;
use App\Http\Controllers\Admin\RetiroAdminController;
use App\Http\Controllers\Admin\ResenaAdminController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\UsuarioAdminController;
use App\Http\Controllers\Admin\MateriaAdminController;

/*
|--------------------------------------------------------------------------
| Admin
| Aprobación, Pagos, Retiros, Reseñas, Reportes, Usuarios y Materias
|--------------------------------------------------------------------------
|
| Este archivo se carga desde web.php dentro del grupo
| ['auth', 'verified'] y aplica role:administrador.
|
*/

Route::middleware('role:administrador')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Tutores
        Route::get('/tutores', [TutorApprovalController::class, 'index'])
            ->name('tutores.index');

        Route::post('/tutores/{id}/aprobar', [TutorApprovalController::class, 'aprobar'])
            ->name('tutores.aprobar');

        Route::post('/tutores/{id}/rechazar', [TutorApprovalController::class, 'rechazar'])
            ->name('tutores.rechazar');

        // Ver documentos privados (DNI / CV)
        Route::get('/tutores/{id}/documento/{tipo}', [TutorController::class, 'verDocumento'])
            ->name('tutores.documento');

        // Validación de Pagos
        Route::get('/pagos', [PagoApprovalController::class, 'index'])
            ->name('pagos.index');

        Route::post('/pagos/{id}/aprobar', [PagoApprovalController::class, 'aprobar'])
            ->name('pagos.aprobar');

        Route::post('/pagos/{id}/rechazar', [PagoApprovalController::class, 'rechazar'])
            ->name('pagos.rechazar');

        // Retiros
        Route::get('/retiros', [RetiroAdminController::class, 'index'])
            ->name('retiros.index');

        Route::post('/retiros/{id}/pagar', [RetiroAdminController::class, 'pagar'])
            ->name('retiros.pagar');

        Route::post('/retiros/{id}/rechazar', [RetiroAdminController::class, 'rechazar'])
            ->name('retiros.rechazar');

        // Reseñas
        Route::get('/resenas', [ResenaAdminController::class, 'index'])
            ->name('resenas.index');

        Route::post('/resenas/{id}/ocultar', [ResenaAdminController::class, 'ocultar'])
            ->name('resenas.ocultar');

        Route::post('/resenas/{id}/mostrar', [ResenaAdminController::class, 'mostrar'])
            ->name('resenas.mostrar');

        // Reportes
        Route::get('/reportes', [ReporteController::class, 'index'])
            ->name('reportes.index');

        Route::get('/reportes/pdf', [ReporteController::class, 'pdf'])
            ->name('reportes.pdf');

        // Gestión de Usuarios
        // (el resource genera exactamente admin.usuarios.*)
        Route::resource('usuarios', UsuarioAdminController::class);

        Route::post('/usuarios/{id}/suspender', [UsuarioAdminController::class, 'suspender'])
            ->name('usuarios.suspender');

        Route::post('/usuarios/{id}/activar', [UsuarioAdminController::class, 'activar'])
            ->name('usuarios.activar');

        // Gestión de Materias
        // (sin show: la ruta admin.materias.show no existe; no se genera)
        Route::resource('materias', MateriaAdminController::class)->except(['show']);

        Route::post('/materias/{id}/toggle', [MateriaAdminController::class, 'toggleActivo'])
            ->name('materias.toggle');

        // Suscripciones
        Route::controller(SuscripcionAdminController::class)->group(function () {
            Route::get('/suscripciones', 'index')->name('suscripciones.index');
            Route::post('/suscripciones/{id}/cancelar', 'cancelar')->name('suscripciones.cancelar');
            Route::post('/suscripciones/{id}/extender', 'extender')->name('suscripciones.extender');
        });

        // Transacciones
        Route::get('/transacciones', [TransaccionAdminController::class, 'index'])
            ->name('transacciones.index');
    });
