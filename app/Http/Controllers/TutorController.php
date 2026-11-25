<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Materia;
use App\Models\Disponibilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TutorController extends Controller
{
    public function showRegisterForm()
    {
        $tutor = Auth::user()->tutor;

        if ($tutor && $tutor->aprobacion !== 'rechazado') {
            return redirect()->route('tutor.dashboard');
        }

        if ($tutor) {
            $tutor->load(['materias', 'disponibilidades']);
        }

        $materias = Materia::where('activo', 1)->get();
        $materiasSeleccionadas = $tutor?->materias->pluck('id')->all() ?? [];
        $disponibilidadAnterior = old('disponibilidad');

        if (is_array($disponibilidadAnterior) && $disponibilidadAnterior !== []) {
            $slotsIniciales = collect($disponibilidadAnterior)
                ->map(fn ($slot) => [
                    'dia' => is_array($slot) && is_scalar($slot['dia_semana'] ?? null)
                        ? (string) $slot['dia_semana']
                        : '',
                    'inicio' => is_array($slot) && is_scalar($slot['hora_inicio'] ?? null)
                        ? substr((string) $slot['hora_inicio'], 0, 5)
                        : '',
                    'fin' => is_array($slot) && is_scalar($slot['hora_fin'] ?? null)
                        ? substr((string) $slot['hora_fin'], 0, 5)
                        : '',
                ])
                ->all();
        } elseif ($tutor) {
            $slotsIniciales = $tutor->disponibilidades
                ->map(fn ($slot) => [
                    'dia' => (string) $slot->dia_semana,
                    'inicio' => substr((string) $slot->hora_inicio, 0, 5),
                    'fin' => substr((string) $slot->hora_fin, 0, 5),
                ])
                ->all();
        } else {
            $slotsIniciales = [];
        }

        if ($slotsIniciales === []) {
            $slotsIniciales = [['dia' => '', 'inicio' => '', 'fin' => '']];
        }

        return view('tutor.register', compact('materias', 'tutor', 'materiasSeleccionadas', 'slotsIniciales'));
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        $tutor = $user->tutor;

        abort_if($tutor && $tutor->aprobacion !== 'rechazado', 403);

        $request->validate([
            'universidad' => 'required|string|max:150',
            'carrera' => 'required|string|max:150',
            'dni_numero' => 'required|string|max:15',
            'dni_foto' => [$tutor?->dni_foto ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'cv_archivo' => [$tutor?->cv_archivo ? 'nullable' : 'required', 'mimes:pdf', 'max:5120'],
            'precio_hora' => 'required|numeric|min:5|max:500',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materias,id',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'disponibilidad' => 'required|array|min:1',
            'disponibilidad.*.dia_semana' => 'required|integer|between:1,7',
            'disponibilidad.*.hora_inicio' => 'required|date_format:H:i',
            'disponibilidad.*.hora_fin' => 'required|date_format:H:i|after:disponibilidad.*.hora_inicio',
        ]);

        $dniPathAnterior = $tutor?->dni_foto;
        $cvPathAnterior = $tutor?->cv_archivo;
        $dniPath = $request->file('dni_foto')?->store('documentos', 'local') ?? $dniPathAnterior;
        $cvPath = $request->file('cv_archivo')?->store('documentos', 'local') ?? $cvPathAnterior;
        $fotoPath = $request->file('foto_perfil')?->store('perfiles', 'public');

        $tutor = DB::transaction(function () use ($request, $user, $tutor, $dniPath, $cvPath, $fotoPath) {
            if ($fotoPath) {
                $user->update(['foto' => $fotoPath]);
            }

            $datosTutor = [
                'universidad' => $request->universidad,
                'carrera' => $request->carrera,
                'dni_numero' => $request->dni_numero,
                'dni_foto' => $dniPath,
                'cv_archivo' => $cvPath,
                'precio_hora' => $request->precio_hora,
                'aprobacion' => 'pendiente',
                'motivo_rechazo' => null,
            ];

            if ($tutor) {
                $tutor->update($datosTutor);
            } else {
                $tutor = Tutor::create($datosTutor + [
                    'user_id' => $user->id,
                    'plan' => 'basico',
                ]);
            }

            $tutor->materias()->sync($request->materias);
            $tutor->disponibilidades()->delete();

            foreach ($request->disponibilidad as $disp) {
                Disponibilidad::create([
                    'tutor_id' => $tutor->id,
                    'dia_semana' => $disp['dia_semana'],
                    'hora_inicio' => $disp['hora_inicio'],
                    'hora_fin' => $disp['hora_fin'],
                ]);
            }

            return $tutor;
        });

        if ($request->hasFile('dni_foto') && $dniPathAnterior && $dniPathAnterior !== $dniPath
            && ! Storage::disk('local')->delete($dniPathAnterior)) {
            \Log::warning('Unable to remove replaced tutor ID document.', ['tutor_id' => $tutor->id]);
        }

        if ($request->hasFile('cv_archivo') && $cvPathAnterior && $cvPathAnterior !== $cvPath
            && ! Storage::disk('local')->delete($cvPathAnterior)) {
            \Log::warning('Unable to remove replaced tutor resume.', ['tutor_id' => $tutor->id]);
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
