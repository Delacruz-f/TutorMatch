<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\Admin\TutorApprovalController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autenticación (solo invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (requieren login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Redirección Inteligente según Rol
    |----------------------------------------------------------------------
    | Esta ruta captura /dashboard y redirige al dashboard correcto
    | según el rol del usuario autenticado. Sirve como "puente" para
    | cualquier enlace antiguo o botón genérico de "Dashboard".
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return match ($user->rol) {
            'administrador' => redirect()->route('admin.dashboard'),
            'tutor'         => redirect()->route('tutor.dashboard'),
            default         => redirect()->route('estudiante.dashboard'),
        };
    })->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Dashboards por Rol
    |----------------------------------------------------------------------
    */
    Route::get('/estudiante/dashboard', [DashboardController::class, 'estudiante'])
        ->name('estudiante.dashboard')
        ->middleware('role:estudiante');

    Route::get('/tutor/dashboard', [DashboardController::class, 'tutor'])
        ->name('tutor.dashboard')
        ->middleware('role:tutor');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')
        ->middleware('role:administrador');

    /*
    |----------------------------------------------------------------------
    | FASE 2: Registro de Tutor
    |----------------------------------------------------------------------
    */
    Route::middleware('role:tutor')->group(function () {
        Route::get('/tutor/registro', [TutorController::class, 'showRegisterForm'])->name('tutor.register.form');
        Route::post('/tutor/registro', [TutorController::class, 'register'])->name('tutor.register');
    });

    /*
    |----------------------------------------------------------------------
    | FASE 2: Admin - Aprobación de Tutores
    |----------------------------------------------------------------------
    */
    Route::middleware('role:administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/tutores', [TutorApprovalController::class, 'index'])->name('tutores.index');
        Route::post('/tutores/{id}/aprobar', [TutorApprovalController::class, 'aprobar'])->name('tutores.aprobar');
        Route::post('/tutores/{id}/rechazar', [TutorApprovalController::class, 'rechazar'])->name('tutores.rechazar');

        // Ver documentos privados (DNI / CV)
        Route::get('/tutores/{id}/documento/{tipo}', [TutorController::class, 'verDocumento'])->name('tutores.documento');
    });
});
