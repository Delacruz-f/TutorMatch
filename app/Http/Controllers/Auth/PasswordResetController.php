<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Solicitar enlace de recuperación (formulario).
     */
    public function request()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar email de recuperación.
     */
    public function email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors([
                'email' => __($status),
            ]);
    }

    /**
     * Formulario de reset.
     */
    public function reset($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
        ]);
    }

    /**
     * Procesar reset.
     */
    public function update(Request $request)
    {
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
                'email' => [__($status)],
            ]);
    }
}
