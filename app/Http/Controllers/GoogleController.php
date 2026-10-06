<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirigir a Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback de Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Error al autenticar con Google. Inténtalo de nuevo.');
        }

        // Buscar por google_id o email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Usuario ya existe → login directo
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            if ($user->estado === 'suspendido') {
                return redirect()->route('login')
                    ->with('error', 'Tu cuenta está suspendida. Contacta al administrador.');
            }

            Auth::login($user);
            return $this->redirectByRol($user);
        }

        // Usuario NO existe → guardar datos en sesión y pedir rol
        session([
            'google_registro' => [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'nombre' => $googleUser->user['given_name'] ?? 'Usuario',
                'apellido' => $googleUser->user['family_name'] ?? '',
                'foto_url' => $googleUser->getAvatar(),
            ],
        ]);

        return redirect()->route('google.elegir-rol');
    }

    /**
     * Mostrar pantalla para elegir rol.
     */
    public function elegirRol()
    {
        if (!session('google_registro')) {
            return redirect()->route('login');
        }

        return view('auth.google-elegir-rol', [
            'datos' => session('google_registro'),
        ]);
    }

    /**
     * Crear la cuenta con el rol elegido.
     */
    public function registrarConRol(Request $request)
    {
        $request->validate([
            'rol' => 'required|in:estudiante,tutor',
        ]);

        $datos = session('google_registro');

        if (!$datos) {
            return redirect()->route('login')->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        // Verificar que el email no exista ya (doble chequeo)
        if (User::where('email', $datos['email'])->exists()) {
            session()->forget('google_registro');
            return redirect()->route('login')
                ->with('error', 'Este correo ya está registrado. Inicia sesión.');
        }

        $user = User::create([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'email' => $datos['email'],
            'email_verified_at' => now(), // Google ya verificó
            'password' => bcrypt(Str::random(24)),
            'google_id' => $datos['google_id'],
            'rol' => $request->rol,
            'estado' => 'activo',
        ]);

        session()->forget('google_registro');
        Auth::login($user);

        return $this->redirectByRol($user);
    }

    /**
     * Redirigir según rol.
     */
    private function redirectByRol(User $user)
    {
        return match ($user->rol) {
            'administrador' => redirect()->route('admin.dashboard'),
            'tutor' => redirect()->route('tutor.dashboard'),
            default => redirect()->route('estudiante.dashboard'),
        };
    }
}