<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Resena;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    /**
     * Formulario para dejar reseña
     */
    public function create($claseId)
    {
        $clase = Clase::with(['tutor.user', 'materia'])
            ->where('estudiante_id', Auth::id())
            ->where('estado', 'completada')
            ->findOrFail($claseId);

        // Verificar que no tenga reseña aún
        if ($clase->resena) {
            return redirect()->route('estudiante.dashboard')
                ->with('error', 'Ya dejaste una reseña para esta clase.');
        }

        return view('resenas.create', compact('clase'));
    }

    /**
     * Guardar reseña
     */
    public function store(Request $request, $claseId)
    {
        $request->validate([
            'calificacion' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $clase = Clase::where('estudiante_id', Auth::id())
            ->where('estado', 'completada')
            ->findOrFail($claseId);

        if ($clase->resena) {
            return back()->with('error', 'Ya dejaste una reseña para esta clase.');
        }

        Resena::create([
            'clase_id' => $clase->id,
            'estudiante_id' => Auth::id(),
            'tutor_id' => $clase->tutor_id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'estado' => 'visible',
        ]);

        // Recalcular promedio del tutor
        $tutor = Tutor::find($clase->tutor_id);
        $promedio = Resena::where('tutor_id', $tutor->id)
            ->where('estado', 'visible')
            ->avg('calificacion');
        $total = Resena::where('tutor_id', $tutor->id)
            ->where('estado', 'visible')
            ->count();

        $tutor->update([
            'calificacion_promedio' => round($promedio ?? 0, 2),
            'total_resenas' => $total,
        ]);

        return redirect()->route('estudiante.dashboard')
            ->with('success', '¡Gracias por tu reseña!');
    }

    /**
     * Responder reseña (tutor)
     */
    public function responder(Request $request, $resenaId)
    {
        $request->validate([
            'respuesta_tutor' => 'required|string|max:500',
        ]);

        $tutor = Auth::user()->tutor;
        $resena = Resena::where('tutor_id', $tutor->id)->findOrFail($resenaId);

        $resena->update(['respuesta_tutor' => $request->respuesta_tutor]);

        return back()->with('success', 'Respuesta enviada.');
    }
}
