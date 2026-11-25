<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    /**
     * Pantalla que dice "verifica tu correo".
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Cuando el usuario hace clic en el enlace del correo.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()
            ->route('dashboard')
            ->with('success', '¡Email verificado correctamente!');
    }

    /**
     * Reenviar el correo de verificación.
     */
    public function send(Request $request)
    {
        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Error enviando verificación: '.$e->getMessage());

            return back()->with('error', 'No se pudo enviar el correo. Inténtalo más tarde.');
        }

        return back()->with(
            'success',
            'Te enviamos un nuevo enlace de verificación.'
        );
    }
}
