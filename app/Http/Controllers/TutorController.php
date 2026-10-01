<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Materia;
use App\Models\Disponibilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorController extends Controller
{
    public function showRegisterForm()
    {
        if (Auth::user()->tutor) {
            return redirect()->route('tutor.dashboard');
        }

        $materias = Materia::where('activo', 1)->get();
        return view('tutor.register', compact('materias'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'universidad' => 'required|string|max:150',
            'carrera' => 'required|string|max:150',
            'dni_numero' => 'required|string|max:15',
            'dni_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'cv_archivo' => 'required|mimes:pdf|max:5120',
            'precio_hora' => 'required|numeric|min:5|max:500',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materias,id',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'disponibilidad' => 'required|array|min:1',
            'disponibilidad.*.dia_semana' => 'required|integer|between:1,7',
            'disponibilidad.*.hora_inicio' => 'required|date_format:H:i',
            'disponibilidad.*.hora_fin' => 'required|date_format:H:i|after:disponibilidad.*.hora_inicio',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Subir foto de perfil (pública)
        if ($request->hasFile('foto_perfil')) {
            $fotoPath = $request->file('foto_perfil')->store('perfiles', 'public');
            $user->update(['foto' => $fotoPath]);
        }

        // Subir documentos privados (DNI y CV)
        $dniPath = $request->file('dni_foto')->store('documentos', 'local');
        $cvPath = $request->file('cv_archivo')->store('documentos', 'local');

        // Crear perfil de tutor
        $tutor = Tutor::create([
            'user_id' => $user->id,
            'universidad' => $request->universidad,
            'carrera' => $request->carrera,
            'dni_numero' => $request->dni_numero,
            'dni_foto' => $dniPath,
            'cv_archivo' => $cvPath,
            'precio_hora' => $request->precio_hora,
            'aprobacion' => 'pendiente',
            'plan' => 'basico',
        ]);

        // Asociar materias
        $tutor->materias()->sync($request->materias);

        // Guardar disponibilidad
        foreach ($request->disponibilidad as $disp) {
            Disponibilidad::create([
                'tutor_id' => $tutor->id,
                'dia_semana' => $disp['dia_semana'],
                'hora_inicio' => $disp['hora_inicio'],
                'hora_fin' => $disp['hora_fin'],
            ]);
        }

        return redirect()->route('tutor.dashboard')
            ->with('success', 'Tu perfil de tutor ha sido enviado. Espera la aprobación del administrador.');
    }

    /**
     * Ver documentos privados (solo admin)
     */
    public function verDocumento(int $tutorId, string $tipo)
    {
        // Solo admin puede ver documentos privados
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No autorizado.');
        }

        $tutor = Tutor::findOrFail($tutorId);
        $path = $tipo === 'dni' ? $tutor->dni_foto : $tutor->cv_archivo;

        $fullPath = storage_path('app/private/' . $path);

        if (!file_exists($fullPath)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->file($fullPath);
    }
}
