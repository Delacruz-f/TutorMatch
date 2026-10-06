<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaTutorController extends Controller
{
    /**
     * Listar todas las reseñas del tutor autenticado.
     */
    public function index()
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return redirect()->route('tutor.register.form');
        }

        $resenas = Resena::with(['estudiante', 'clase.materia'])
            ->where('tutor_id', $tutor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Estadísticas
        $totalResenas = Resena::where('tutor_id', $tutor->id)->where('estado', 'visible')->count();
        $promedio = Resena::where('tutor_id', $tutor->id)->where('estado', 'visible')->avg('calificacion') ?? 0;

        $distribucion = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribucion[$i] = Resena::where('tutor_id', $tutor->id)
                ->where('estado', 'visible')
                ->where('calificacion', $i)
                ->count();
        }

        return view('tutor.resenas.index', compact('tutor', 'resenas', 'totalResenas', 'promedio', 'distribucion'));
    }

    /**
     * Responder a una reseña.
     */
    public function responder(Request $request, $id)
    {
        $request->validate([
            'respuesta_tutor' => 'required|string|max:500',
        ]);

        $tutor = Auth::user()->tutor;
        $resena = Resena::where('tutor_id', $tutor->id)->findOrFail($id);

        $resena->update([
            'respuesta_tutor' => $request->respuesta_tutor,
        ]);

        return back()->with('success', 'Respuesta enviada correctamente.');
    }

    /**
     * Eliminar la respuesta del tutor.
     */
    public function eliminarRespuesta($id)
    {
        $tutor = Auth::user()->tutor;
        $resena = Resena::where('tutor_id', $tutor->id)->findOrFail($id);

        $resena->update(['respuesta_tutor' => null]);

        return back()->with('success', 'Respuesta eliminada.');
    }
}
