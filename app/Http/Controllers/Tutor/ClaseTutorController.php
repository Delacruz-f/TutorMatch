<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClaseTutorController extends Controller
{
    /**
     * Dashboard del tutor con sus clases
     */
    public function dashboard()
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return redirect()->route('tutor.register.form');
        }

        $solicitudes = Clase::with(['estudiante', 'materia'])
            ->where('tutor_id', $tutor->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        $proximas = Clase::with(['estudiante', 'materia'])
    ->where('tutor_id', $tutor->id)
    ->whereIn('estado', ['aceptada'])
    ->where('fecha', '>=', now()->subDay()->toDateString()) // ← Ayer o después
    ->orderBy('fecha', 'asc')
    ->orderBy('hora_inicio', 'asc')
    ->get();

        $historial = Clase::with(['estudiante', 'materia', 'resena'])
            ->where('tutor_id', $tutor->id)
            ->whereIn('estado', ['completada', 'rechazada', 'cancelada'])
            ->orderBy('fecha', 'desc')
            ->limit(20)
            ->get();

        return view('tutor.dashboard', compact('tutor', 'solicitudes', 'proximas', 'historial'));
    }

    /**
     * Aceptar una solicitud de clase (genera la sala Jitsi)
     */
   public function aceptar($claseId)
{
    $tutor = Auth::user()->tutor;
    $clase = Clase::with(['estudiante', 'materia', 'tutor.user'])
        ->where('tutor_id', $tutor->id)
        ->where('estado', 'pendiente')
        ->findOrFail($claseId);

    $salaJitsi = 'TutorMatch-' . Str::random(10) . '-' . $clase->id;

    $clase->update([
        'estado' => 'aceptada',
        'sala_jitsi' => $salaJitsi,
    ]);

    // Notificación en BD
    \App\Models\Notificacion::enviar(
        $clase->estudiante_id,
        '✅ Tu clase fue aceptada',
        "{$tutor->user->nombre} aceptó tu clase de {$clase->materia->nombre}.",
        route('clases.sala', $clase->id)
    );

    // Email al estudiante
    try {
        \Mail::to($clase->estudiante->email)->send(new \App\Mail\ClaseAceptadaMail($clase));
    } catch (\Exception $e) {
        \Log::error('Error enviando email de clase aceptada: ' . $e->getMessage());
    }

    return back()->with('success', 'Clase aceptada. Se generó la sala y se notificó al estudiante.');
}
    /**
     * Rechazar una solicitud
     */
   public function rechazar(Request $request, $claseId)
{
    $request->validate([
        'motivo' => 'nullable|string|max:255',
    ]);

    $tutor = Auth::user()->tutor;
    $clase = Clase::with(['estudiante', 'materia', 'tutor.user'])
        ->where('tutor_id', $tutor->id)
        ->where('estado', 'pendiente')
        ->findOrFail($claseId);

    $clase->update([
        'estado' => 'rechazada',
        'notas' => $request->motivo ?? 'Rechazada por el tutor',
    ]);

    // Notificación en BD
    \App\Models\Notificacion::enviar(
        $clase->estudiante_id,
        '❌ Tu clase fue rechazada',
        "El tutor rechazó tu clase de {$clase->materia->nombre}.",
        route('estudiante.dashboard')
    );

    // Email al estudiante
    try {
        \Mail::to($clase->estudiante->email)->send(new \App\Mail\ClaseRechazadaMail($clase));
    } catch (\Exception $e) {
        \Log::error('Error enviando email de clase rechazada: ' . $e->getMessage());
    }

    return back()->with('success', 'Clase rechazada. Se notificó al estudiante.');
}
    /**
     * Ver sala Jitsi (tutor o estudiante de esa clase)
     */
    public function verSala($claseId)
    {
        $user = Auth::user();

        $clase = Clase::with(['tutor.user', 'estudiante', 'materia'])
            ->where('id', $claseId)
            ->where(function ($q) use ($user) {
                // Solo el tutor o el estudiante de la clase
                if ($user->rol === 'tutor' && $user->tutor) {
                    $q->where('tutor_id', $user->tutor->id);
                } elseif ($user->rol === 'estudiante') {
                    $q->where('estudiante_id', $user->id);
                } else {
                    $q->whereRaw('1=0'); // Admin no ve la sala por defecto
                }
            })
            ->firstOrFail();

        // Verificar que la sala esté dentro del rango horario permitido (5 min antes, 10 min después)
        if ($clase->sala_jitsi) {
    // $clase->fecha ya es un objeto Carbon por el cast del modelo.
    // Usamos copy() para no mutar la fecha original y setTimeFrom() para asignar la hora.
    $inicio = $clase->fecha->copy()->setTimeFrom(\Carbon\Carbon::parse($clase->hora_inicio));
    $fin = $clase->fecha->copy()->setTimeFrom(\Carbon\Carbon::parse($clase->hora_fin));

    $ahora = now();
    $puedeEntrar = $ahora->greaterThanOrEqualTo($inicio->copy()->subMinutes(5))
                && $ahora->lessThanOrEqualTo($fin->copy()->addMinutes(10));

    if (!$puedeEntrar) {
        return back()->with('error', 'La sala estará disponible 5 minutos antes y hasta 10 minutos después de la clase.');
    }
}

        return view('clases.sala', compact('clase'));
    }

    /**
     * Marcar una clase como completada (la hace el tutor después de la clase)
     */
    public function completar($claseId)
{
    $tutor = Auth::user()->tutor;
    $clase = Clase::with(['estudiante', 'materia', 'tutor.user'])
        ->where('tutor_id', $tutor->id)
        ->where('estado', 'aceptada')
        ->findOrFail($claseId);

    $clase->update([
        'estado' => 'completada',
        'pago_liberado' => 1,
    ]);

    $this->recalcularPromedioTutor($tutor->id);

    // Notificación en BD
    \App\Models\Notificacion::enviar(
        $clase->estudiante_id,
        '🏁 Clase completada',
        "Tu clase de {$clase->materia->nombre} finalizó. ¡Deja tu reseña!",
        route('resenas.create', $clase->id)
    );

    // Email al estudiante
    try {
        \Mail::to($clase->estudiante->email)->send(new \App\Mail\ClaseCompletadaMail($clase));
    } catch (\Exception $e) {
        \Log::error('Error enviando email de clase completada: ' . $e->getMessage());
    }

    return back()->with('success', 'Clase completada. Pago liberado y estudiante notificado.');
}

    private function recalcularPromedioTutor($tutorId)
    {
        $tutor = \App\Models\Tutor::find($tutorId);
        $promedio = \App\Models\Resena::where('tutor_id', $tutorId)
            ->where('estado', 'visible')
            ->avg('calificacion');
        $total = \App\Models\Resena::where('tutor_id', $tutorId)
            ->where('estado', 'visible')
            ->count();

        $tutor->update([
            'calificacion_promedio' => round($promedio ?? 0, 2),
            'total_resenas' => $total,
        ]);
    }
}
