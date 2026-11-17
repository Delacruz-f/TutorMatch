<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CulqiController;
use App\Http\Controllers\Tutor\ClaseTutorController;
use App\Http\Controllers\Tutor\ResenaTutorController;
use App\Http\Controllers\Tutor\DisponibilidadTutorController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\RetiroController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\SuscripcionController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/language', [LanguageController::class, 'update'])->name('language.update');

// Búsqueda de tutores (estas rutas permanecen públicas)
Route::controller(BusquedaController::class)->group(function () {
    Route::get('/buscar-tutores', 'index')->name('busqueda.index');
    Route::get('/tutor/{id}/perfil', 'verTutor')->name('busqueda.tutor');
});

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Autenticación con Google
    Route::controller(GoogleController::class)->group(function () {
        Route::get('/auth/google', 'redirect')->name('google.redirect');
        Route::get('/auth/google/callback', 'callback')->name('google.callback');
        Route::get('/auth/google/elegir-rol', 'elegirRol')->name('google.elegir-rol');
        Route::post('/auth/google/registrar', 'registrarConRol')->name('google.registrar');
    });

    // Registro
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Recuperación de contraseña
    Route::controller(PasswordResetController::class)->group(function () {
        // Solicitar enlace de recuperación
        Route::get('/forgot-password', 'request')->name('password.request');
        // Enviar email de recuperación
        Route::post('/forgot-password', 'email')->name('password.email');
        // Formulario de reset
        Route::get('/reset-password/{token}', 'reset')->name('password.reset');
        // Procesar reset
        Route::post('/reset-password', 'update')->name('password.update');
    });
});

/*
|--------------------------------------------------------------------------
| Autenticación (usuario logueado)
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Verificación de Email
|--------------------------------------------------------------------------
|
| Estas rutas NO deben tener el middleware "verified", porque son
| precisamente las rutas que permiten al usuario verificar su correo.
|
*/

Route::controller(EmailVerificationController::class)
    ->middleware('auth')
    ->group(function () {
        // Pantalla que dice "verifica tu correo"
        Route::get('/email/verify', 'notice')->name('verification.notice');

        Route::post('/email/verification/skip', [AuthController::class, 'skipEmailVerification'])
            ->name('verification.skip');

        // Cuando el usuario hace clic en el enlace del correo
        Route::get('/email/verify/{id}/{hash}', 'verify')
            ->middleware(['signed'])
            ->name('verification.verify');

        // Reenviar el correo de verificación
        Route::post('/email/verification-notification', 'send')
            ->middleware(['throttle:6,1'])
            ->name('verification.send');
    });

/*
|--------------------------------------------------------------------------
| Dashboard y perfil (usuario logueado)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Editar perfil (formulario)
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');

    // Redirección por rol
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('/estudiante/dashboard', [DashboardController::class, 'estudiante'])
        ->name('estudiante.dashboard')
        ->middleware('role:estudiante');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')
        ->middleware('role:administrador');

    Route::get('/tutor/dashboard', [ClaseTutorController::class, 'dashboard'])
        ->name('tutor.dashboard')
        ->middleware('role:tutor');
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
|
| Requieren:
| 1. Usuario autenticado
| 2. Email verificado
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil del usuario
    Route::controller(PerfilController::class)->group(function () {
        // Actualizar datos del perfil
        Route::put('/perfil', 'update')->name('perfil.update');
        // Cambiar contraseña desde el perfil
        Route::put('/perfil/password', 'updatePassword')->name('perfil.password');
        // Eliminar la cuenta del usuario autenticado
        Route::delete('/perfil', 'destroy')->name('perfil.destroy');
    });

    // Notificaciones
    Route::controller(NotificacionController::class)->group(function () {
        Route::get('/notificaciones', 'index')->name('notificaciones.index');
        Route::post('/notificaciones/leer-todas', 'marcarTodasLeidas')->name('notificaciones.leer-todas');
        Route::post('/notificaciones/vaciar', 'vaciar')->name('notificaciones.vaciar');
        Route::post('/notificaciones/{id}/leer', 'marcarLeida')->name('notificaciones.leer');
        Route::delete('/notificaciones/{id}', 'destroy')->name('notificaciones.destroy');
    });

    // Registro de Tutor
    Route::controller(TutorController::class)
        ->middleware('role:tutor')
        ->group(function () {
            Route::get('/tutor/registro', 'showRegisterForm')->name('tutor.register.form');
            Route::post('/tutor/registro', 'register')->name('tutor.register');
        });

    // Admin
    require __DIR__.'/admin.php';

    // Tutor
    require __DIR__.'/tutor.php';

    // Sala Jitsi (tutor y estudiante)
    Route::get('/clases/{id}/sala', [ClaseTutorController::class, 'verSala'])
        ->name('clases.sala');

    // Reservas y pagos - Estudiante
    Route::middleware('role:estudiante')->group(function () {

        // Reservas
        Route::get('/reservar/{tutorId}', [ReservaController::class, 'create'])
            ->name('reservas.create');

        Route::post('/reservar', [ReservaController::class, 'store'])
            ->name('reservas.store');

        // Pagos
        Route::get('/pago/{claseId}', [PagoController::class, 'show'])
            ->name('reservas.pago');

        Route::post('/pago/{claseId}', [PagoController::class, 'store'])
            ->name('pagos.store');

        // Reseñas - Estudiante
        Route::get('/clases/{id}/resena', [ResenaController::class, 'create'])
            ->name('resenas.create');

        Route::post('/clases/{id}/resena', [ResenaController::class, 'store'])
            ->name('resenas.store');

        Route::get('/pago/{claseId}/comprobante', [PagoController::class, 'comprobantePdf'])
            ->name('pagos.comprobante');

        Route::get('/pago/{claseId}/tarjeta', [CulqiController::class, 'form'])
            ->name('pagos.tarjeta');

        Route::post('/pago/{claseId}/tarjeta', [CulqiController::class, 'procesar'])
            ->name('pagos.tarjeta.procesar');
    });

});
