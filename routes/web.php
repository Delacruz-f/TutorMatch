<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\PerfilController;

use App\Http\Controllers\Admin\TransaccionAdminController;
use App\Http\Controllers\Admin\SuscripcionAdminController;
use App\Http\Controllers\Admin\TutorApprovalController;
use App\Http\Controllers\Admin\PagoApprovalController;
use App\Http\Controllers\Admin\RetiroAdminController;
use App\Http\Controllers\Admin\ResenaAdminController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\UsuarioAdminController;
use App\Http\Controllers\Admin\MateriaAdminController;

use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CulqiController;

use App\Http\Controllers\Tutor\ClaseTutorController;
use App\Http\Controllers\Tutor\ResenaTutorController;
use App\Http\Controllers\Tutor\DisponibilidadTutorController;

use App\Http\Controllers\ResenaController;
use App\Http\Controllers\RetiroController;

// Controladores FASE 5
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\SuscripcionController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Verificación de Email
|--------------------------------------------------------------------------
|
| Estas rutas NO deben tener el middleware "verified", porque son
| precisamente las rutas que permiten al usuario verificar su correo.
|
*/

Route::middleware('auth')->group(function () {

    // Pantalla que dice "verifica tu correo"
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Cuando el usuario hace clic en el enlace del correo
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

        $request->fulfill();

        return redirect()
            ->route('dashboard')
            ->with('success', '¡Email verificado correctamente!');

    })->middleware(['signed'])->name('verification.verify');

    // Reenviar el correo de verificación
    Route::post('/email/verification-notification', function (Request $request) {

        $request->user()->sendEmailVerificationNotification();

        return back()->with(
            'success',
            'Te enviamos un nuevo enlace de verificación.'
        );

    })->middleware(['throttle:6,1'])->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    /*
    |--------------------------------------------------------------------------
    | Autenticación con Google
    |--------------------------------------------------------------------------
    */

    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');

    Route::get('/auth/google/elegir-rol', [GoogleController::class, 'elegirRol'])
        ->name('google.elegir-rol');

    Route::post('/auth/google/registrar', [GoogleController::class, 'registrarConRol'])
        ->name('google.registrar');

    /*
    |--------------------------------------------------------------------------
    | Registro
    |--------------------------------------------------------------------------
    */

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    /*
    |--------------------------------------------------------------------------
    | Recuperación de contraseña
    |--------------------------------------------------------------------------
    */

    // Solicitar enlace de recuperación
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    // Enviar email de recuperación
    Route::post('/forgot-password', function (Request $request) {

        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors([
                'email' => __($status)
            ]);

    })->name('password.email');

    // Formulario de reset
    Route::get('/reset-password/{token}', function ($token) {

        return view('auth.reset-password', [
            'token' => $token
        ]);

    })->name('password.reset');

    // Procesar reset
    Route::post('/reset-password', function (Request $request) {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {

                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(
                    Str::random(60)
                );

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET

            ? redirect()
                ->route('login')
                ->with(
                    'success',
                    '¡Contraseña actualizada! Ya puedes iniciar sesión.'
                )

            : back()->withErrors([
                'email' => [__($status)]
            ]);

    })->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Búsqueda de tutores
|--------------------------------------------------------------------------
|
| Estas rutas permanecen públicas.
|
*/

Route::get('/buscar-tutores', [BusquedaController::class, 'index'])
    ->name('busqueda.index');

Route::get('/tutor/{id}/perfil', [BusquedaController::class, 'verTutor'])
    ->name('busqueda.tutor');

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

    /*
    |--------------------------------------------------------------------------
    | Perfil del usuario
    |--------------------------------------------------------------------------
    */

    // Mostrar formulario de edición del perfil
    Route::get('/perfil', [PerfilController::class, 'edit'])
        ->name('perfil.edit');

    // Actualizar datos del perfil
    Route::put('/perfil', [PerfilController::class, 'update'])
        ->name('perfil.update');

    // Cambiar contraseña desde el perfil
    Route::put('/perfil/password', [PerfilController::class, 'updatePassword'])
        ->name('perfil.password');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | FASE 5: Notificaciones
    |--------------------------------------------------------------------------
    */

    Route::get('/notificaciones', [NotificacionController::class, 'index'])
        ->name('notificaciones.index');

    Route::post('/notificaciones/{id}/leer', [NotificacionController::class, 'marcarLeida'])
        ->name('notificaciones.leer');

    Route::post('/notificaciones/leer-todas', [NotificacionController::class, 'marcarTodasLeidas'])
        ->name('notificaciones.leer-todas');

    /*
    |--------------------------------------------------------------------------
    | Dashboard inteligente
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        $user = Auth::user();

        return match ($user->rol) {

            'administrador' => redirect()->route('admin.dashboard'),

            'tutor' => redirect()->route('tutor.dashboard'),

            default => redirect()->route('estudiante.dashboard'),

        };

    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Dashboard del estudiante
    |--------------------------------------------------------------------------
    */

    Route::get('/estudiante/dashboard', [DashboardController::class, 'estudiante'])
        ->name('estudiante.dashboard')
        ->middleware('role:estudiante');

    /*
    |--------------------------------------------------------------------------
    | Dashboard del administrador
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')
        ->middleware('role:administrador');

    /*
    |--------------------------------------------------------------------------
    | Registro de Tutor
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:tutor')->group(function () {

        Route::get('/tutor/registro', [TutorController::class, 'showRegisterForm'])
            ->name('tutor.register.form');

        Route::post('/tutor/registro', [TutorController::class, 'register'])
            ->name('tutor.register');
    });

    /*
    |--------------------------------------------------------------------------
    | FASE 2 y 5: Admin
    | Aprobación, Pagos, Retiros, Reseñas, Reportes,
    | Usuarios y Materias
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:administrador')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Tutores
            |--------------------------------------------------------------------------
            */

            Route::get('/tutores', [TutorApprovalController::class, 'index'])
                ->name('tutores.index');

            Route::post('/tutores/{id}/aprobar', [TutorApprovalController::class, 'aprobar'])
                ->name('tutores.aprobar');

            Route::post('/tutores/{id}/rechazar', [TutorApprovalController::class, 'rechazar'])
                ->name('tutores.rechazar');

            // Ver documentos privados (DNI / CV)
            Route::get('/tutores/{id}/documento/{tipo}', [TutorController::class, 'verDocumento'])
                ->name('tutores.documento');

            /*
            |--------------------------------------------------------------------------
            | FASE 4: Validación de Pagos
            |--------------------------------------------------------------------------
            */

            Route::get('/pagos', [PagoApprovalController::class, 'index'])
                ->name('pagos.index');

            Route::post('/pagos/{id}/aprobar', [PagoApprovalController::class, 'aprobar'])
                ->name('pagos.aprobar');

            Route::post('/pagos/{id}/rechazar', [PagoApprovalController::class, 'rechazar'])
                ->name('pagos.rechazar');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Retiros
            |--------------------------------------------------------------------------
            */

            Route::get('/retiros', [RetiroAdminController::class, 'index'])
                ->name('retiros.index');

            Route::post('/retiros/{id}/pagar', [RetiroAdminController::class, 'pagar'])
                ->name('retiros.pagar');

            Route::post('/retiros/{id}/rechazar', [RetiroAdminController::class, 'rechazar'])
                ->name('retiros.rechazar');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Reseñas
            |--------------------------------------------------------------------------
            */

            Route::get('/resenas', [ResenaAdminController::class, 'index'])
                ->name('resenas.index');

            Route::post('/resenas/{id}/ocultar', [ResenaAdminController::class, 'ocultar'])
                ->name('resenas.ocultar');

            Route::post('/resenas/{id}/mostrar', [ResenaAdminController::class, 'mostrar'])
                ->name('resenas.mostrar');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Reportes
            |--------------------------------------------------------------------------
            */

            Route::get('/reportes', [ReporteController::class, 'index'])
                ->name('reportes.index');

            Route::get('/reportes/pdf', [ReporteController::class, 'pdf'])
                ->name('reportes.pdf');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Gestión de Usuarios
            |--------------------------------------------------------------------------
            */

            Route::resource('usuarios', UsuarioAdminController::class)->names([
                'index' => 'usuarios.index',
                'create' => 'usuarios.create',
                'store' => 'usuarios.store',
                'show' => 'usuarios.show',
                'edit' => 'usuarios.edit',
                'update' => 'usuarios.update',
                'destroy' => 'usuarios.destroy',
            ]);

            Route::post('/usuarios/{id}/suspender', [UsuarioAdminController::class, 'suspender'])
                ->name('usuarios.suspender');

            Route::post('/usuarios/{id}/activar', [UsuarioAdminController::class, 'activar'])
                ->name('usuarios.activar');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Gestión de Materias
            |--------------------------------------------------------------------------
            */

            Route::resource('materias', MateriaAdminController::class)->names([
                'index' => 'materias.index',
                'create' => 'materias.create',
                'store' => 'materias.store',
                'edit' => 'materias.edit',
                'update' => 'materias.update',
                'destroy' => 'materias.destroy',
            ]);

            Route::post('/materias/{id}/toggle', [MateriaAdminController::class, 'toggleActivo'])
                ->name('materias.toggle');

            Route::get('/suscripciones', [SuscripcionAdminController::class, 'index'])->name('suscripciones.index');
            Route::post('/suscripciones/{id}/cancelar', [SuscripcionAdminController::class, 'cancelar'])->name('suscripciones.cancelar');
            Route::post('/suscripciones/{id}/extender', [SuscripcionAdminController::class, 'extender'])->name('suscripciones.extender');

            Route::get('/transacciones', [TransaccionAdminController::class, 'index'])->name('transacciones.index');
        });

    /*
    |--------------------------------------------------------------------------
    | FASE 4 y 5: Tutor
    | Dashboard, Clases, Retiros, Suscripciones,
    | Reseñas y Disponibilidad
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:tutor')
        ->prefix('tutor')
        ->name('tutor.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Dashboard principal
            |--------------------------------------------------------------------------
            */

            Route::get('/dashboard', [ClaseTutorController::class, 'dashboard'])
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Gestión de clases
            |--------------------------------------------------------------------------
            */

            Route::post('/clases/{id}/aceptar', [ClaseTutorController::class, 'aceptar'])
                ->name('clases.aceptar');

            Route::post('/clases/{id}/rechazar', [ClaseTutorController::class, 'rechazar'])
                ->name('clases.rechazar');

            Route::post('/clases/{id}/completar', [ClaseTutorController::class, 'completar'])
                ->name('clases.completar');

            /*
            |--------------------------------------------------------------------------
            | Retiros
            |--------------------------------------------------------------------------
            */

            Route::get('/retiros', [RetiroController::class, 'index'])
                ->name('retiros.index');

            Route::post('/retiros', [RetiroController::class, 'store'])
                ->name('retiros.store');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Reseñas del Tutor
            |--------------------------------------------------------------------------
            */

            Route::get('/resenas', [ResenaTutorController::class, 'index'])
                ->name('resenas.index');

            Route::post('/resenas/{id}/responder', [ResenaTutorController::class, 'responder'])
                ->name('resenas.responder');

            Route::delete('/resenas/{id}/respuesta', [ResenaTutorController::class, 'eliminarRespuesta'])
                ->name('resenas.eliminar-respuesta');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Disponibilidad del Tutor
            |--------------------------------------------------------------------------
            */

            Route::get('/disponibilidad', [DisponibilidadTutorController::class, 'index'])
                ->name('disponibilidad.index');

            Route::post('/disponibilidad', [DisponibilidadTutorController::class, 'store'])
                ->name('disponibilidad.store');

            /*
            |--------------------------------------------------------------------------
            | FASE 5: Suscripciones
            |--------------------------------------------------------------------------
            */

            Route::get('/suscripciones', [SuscripcionController::class, 'index'])
                ->name('suscripciones.index');

            Route::post('/suscripciones', [SuscripcionController::class, 'store'])
                ->name('suscripciones.store');
        });

    /*
    |--------------------------------------------------------------------------
    | FASE 4: Sala Jitsi
    | Tutor y estudiante
    |--------------------------------------------------------------------------
    */

    Route::get('/clases/{id}/sala', [ClaseTutorController::class, 'verSala'])
        ->name('clases.sala');

    /*
    |--------------------------------------------------------------------------
    | FASE 4: Reservas y pagos - Estudiante
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:estudiante')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Reservas
        |--------------------------------------------------------------------------
        */

        Route::get('/reservar/{tutorId}', [ReservaController::class, 'create'])
            ->name('reservas.create');

        Route::post('/reservar', [ReservaController::class, 'store'])
            ->name('reservas.store');

        /*
        |--------------------------------------------------------------------------
        | Pagos
        |--------------------------------------------------------------------------
        */

        Route::get('/pago/{claseId}', [PagoController::class, 'show'])
            ->name('reservas.pago');

        Route::post('/pago/{claseId}', [PagoController::class, 'store'])
            ->name('pagos.store');

        /*
        |--------------------------------------------------------------------------
        | Reseñas - Estudiante
        |--------------------------------------------------------------------------
        */

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