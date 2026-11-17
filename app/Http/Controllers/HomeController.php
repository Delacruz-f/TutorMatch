<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Página de inicio.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Redirige a cada usuario al dashboard de su rol.
     */
    public function dashboard()
    {
        $user = Auth::user();

        return match ($user->rol) {
            'administrador' => redirect()->route('admin.dashboard'),
            'tutor' => redirect()->route('tutor.dashboard'),
            default => redirect()->route('estudiante.dashboard'),
        };
    }
}
