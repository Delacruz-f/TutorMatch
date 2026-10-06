<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use App\Models\Tutor;
use Illuminate\Http\Request;

class ResenaAdminController extends Controller
{
    public function index()
    {
        $resenas = Resena::with(['estudiante', 'tutor.user', 'clase'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.resenas.index', compact('resenas'));
    }

    public function ocultar($id)
    {
        $resena = Resena::findOrFail($id);
        $resena->update(['estado' => 'oculta']);

        // Recalcular promedio del tutor
        $this->recalcularTutor($resena->tutor_id);

        return back()->with('success', 'Reseña ocultada.');
    }

    public function mostrar($id)
    {
        $resena = Resena::findOrFail($id);
        $resena->update(['estado' => 'visible']);

        $this->recalcularTutor($resena->tutor_id);

        return back()->with('success', 'Reseña visible nuevamente.');
    }

    private function recalcularTutor($tutorId)
    {
        $tutor = Tutor::find($tutorId);
        $promedio = Resena::where('tutor_id', $tutorId)
            ->where('estado', 'visible')
            ->avg('calificacion');
        $total = Resena::where('tutor_id', $tutorId)
            ->where('estado', 'visible')
            ->count();

        $tutor->update([
            'calificacion_promedio' => round($promedio ?? 0, 2),
            'total_resenas' => $total,
        ]);
    }
}
