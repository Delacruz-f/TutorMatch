<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Disponibilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisponibilidadTutorController extends Controller
{
    /**
     * Mostrar la disponibilidad actual del tutor.
     */
    public function index()
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return redirect()->route('tutor.register.form');
        }

        $disponibilidades = Disponibilidad::where('tutor_id', $tutor->id)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        return view('tutor.disponibilidad.index', compact('tutor', 'disponibilidades'));
    }

    /**
     * Guardar la disponibilidad completa (reemplaza todo).
     */
    public function store(Request $request)
    {
        $request->validate([
            'disponibilidad' => 'required|array|min:1',
            'disponibilidad.*.dia_semana' => 'required|integer|between:1,7',
            'disponibilidad.*.hora_inicio' => 'required|date_format:H:i',
            'disponibilidad.*.hora_fin' => 'required|date_format:H:i|after:disponibilidad.*.hora_inicio',
        ], [
            'disponibilidad.*.hora_fin.after' => 'La hora de fin debe ser posterior a la de inicio.',
        ]);

        $tutor = Auth::user()->tutor;

        // Eliminar las anteriores
        Disponibilidad::where('tutor_id', $tutor->id)->delete();

        // Crear las nuevas
        foreach ($request->disponibilidad as $d) {
            Disponibilidad::create([
                'tutor_id' => $tutor->id,
                'dia_semana' => $d['dia_semana'],
                'hora_inicio' => $d['hora_inicio'],
                'hora_fin' => $d['hora_fin'],
            ]);
        }

        return redirect()->route('tutor.disponibilidad.index')
            ->with('success', 'Disponibilidad actualizada correctamente.');
    }
}
