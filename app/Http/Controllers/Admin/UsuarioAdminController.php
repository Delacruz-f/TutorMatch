<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsuarioAdminController extends Controller
{
    /**
     * Listado de usuarios con filtros.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Búsqueda por nombre o email
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('apellido', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Estadísticas rápidas
        $stats = [
            'total' => User::count(),
            'estudiantes' => User::where('rol', 'estudiante')->count(),
            'tutores' => User::where('rol', 'tutor')->count(),
            'administradores' => User::where('rol', 'administrador')->count(),
            'activos' => User::where('estado', 'activo')->count(),
            'suspendidos' => User::where('estado', 'suspendido')->count(),
        ];

        return view('admin.usuarios.index', compact('usuarios', 'stats'));
    }

    /**
     * Formulario para crear usuario.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'rol' => 'required|in:estudiante,tutor,administrador',
            'telefono' => 'nullable|string|max:20',
            'biografia' => 'nullable|string|max:1000',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'telefono' => $request->telefono,
            'biografia' => $request->biografia,
            'estado' => 'activo',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Ver detalle de usuario.
     */
    public function show($id)
    {
        $usuario = User::with(['tutor.materias', 'notificaciones'])->findOrFail($id);
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:estudiante,tutor,administrador',
            'telefono' => 'nullable|string|max:20',
            'biografia' => 'nullable|string|max:1000',
        ]);

        $usuario->update($request->only(['nombre', 'apellido', 'email', 'rol', 'telefono', 'biografia']));

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Suspender usuario.
     */
    public function suspender($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes suspenderte a ti mismo.');
        }

        if ($usuario->rol === 'administrador') {
            return back()->with('error', 'No puedes suspender a otro administrador.');
        }

        $usuario->update(['estado' => 'suspendido']);

        return back()->with('success', "Usuario {$usuario->nombre} suspendido.");
    }

    /**
     * Activar usuario.
     */
    public function activar($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update(['estado' => 'activo']);

        return back()->with('success', "Usuario {$usuario->nombre} activado.");
    }

    /**
     * Eliminar usuario.
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        if ($usuario->rol === 'administrador') {
            return back()->with('error', 'No puedes eliminar a otro administrador.');
        }

        $nombre = $usuario->nombre;
        $usuario->delete();

        return back()->with('success', "Usuario {$nombre} eliminado.");
    }
}
