<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login por API: devuelve un token Sanctum.
     *
     * POST /api/login
     * { "email": "...", "password": "...", "device_name": "..." }
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no coinciden con nuestros registros.'],
            ]);
        }

        if ($user->estado === 'suspendido') {
            return response()->json([
                'message' => 'Tu cuenta está suspendida. Contacta al administrador.',
            ], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Debes verificar tu correo electrónico antes de usar la API.',
            ], 403);
        }

        $deviceName = $credentials['device_name'] ?? 'api-client';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'email' => $user->email,
                'rol' => $user->rol,
            ],
        ]);
    }

    /**
     * Usuario autenticado por el token.
     *
     * GET /api/user (auth:sanctum)
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'nombre' => $user->nombre,
            'apellido' => $user->apellido,
            'email' => $user->email,
            'rol' => $user->rol,
            'estado' => $user->estado,
            'email_verified_at' => $user->email_verified_at,
        ]);
    }

    /**
     * Logout: revoca SOLO el token usado en la petición.
     *
     * POST /api/logout (auth:sanctum)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}
