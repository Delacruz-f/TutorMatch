<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Tutor;
use App\Models\Materia;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservaController extends Controller
{
    /**
     * Mostrar formulario de reserva
     */
    public function create(Request $request, $tutorId)
    {
        $tutor = Tutor::with(['user', 'materias', 'disponibilidades'])
            ->where('aprobacion', 'aprobado')
            ->findOrFail($tutorId);

        // Validar que el estudiante no esté reservando a sí mismo
        if (Auth::id() === $tutor->user_id) {
            return redirect()->route('busqueda.index')
                ->with('error', 'No puedes reservar una clase contigo mismo.');
        }

        $materiaId = $request->query('materia_id');
        $materiaSeleccionada = $materiaId ? $tutor->materias->find($materiaId) : null;

        // Comisión actual de la plataforma
        $comision = Configuracion::where('clave', 'comision_porcentaje')->value('valor') ?? 20;

        return view('reservas.create', compact('tutor', 'materiaSeleccionada', 'comision'));
    }

    /**
     * Guardar reserva (crea la clase en estado pendiente_pago)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutores,id',
            'materia_id' => 'required|exists:materias,id',
            'fecha' => 'required|date|after_or_equal:' . now()->subDay()->toDateString(),
            'hora_inicio' => 'required|date_format:H:i',
            'duracion_min' => 'required|integer|in:60,90,120',
        ]);

        $tutor = Tutor::where('aprobacion', 'aprobado')->findOrFail($request->tutor_id);
        $materia = Materia::findOrFail($request->materia_id);

        // Calcular hora de fin
        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes((int) $request->duracion_min);

        // Validar que el horario esté dentro de la disponibilidad del tutor
        $diaSemana = Carbon::parse($request->fecha)->dayOfWeekIso; // 1=Lunes ... 7=Domingo
        $disponible = $tutor->disponibilidades()
            ->where('dia_semana', $diaSemana)
            ->where('hora_inicio', '<=', $request->hora_inicio)
            ->where('hora_fin', '>=', $horaFin->format('H:i'))
            ->exists();

        if (!$disponible) {
            return back()->withErrors(['hora_inicio' => 'El tutor no está disponible en ese horario.'])->withInput();
        }

        // Validar que no haya choque con otra clase del tutor
        $choque = Clase::where('tutor_id', $tutor->id)
            ->where('fecha', $request->fecha)
            ->whereIn('estado', ['pendiente_pago', 'pendiente', 'aceptada'])
            ->where(function ($q) use ($request, $horaFin) {
                $q->whereBetween('hora_inicio', [$request->hora_inicio, $horaFin->format('H:i')])
                  ->orWhereBetween('hora_fin', [$request->hora_inicio, $horaFin->format('H:i')]);
            })
            ->exists();

        if ($choque) {
            return back()->withErrors(['hora_inicio' => 'Ya existe una clase reservada en ese horario.'])->withInput();
        }

        // Calcular precios
        $horas = $request->duracion_min / 60;
        $precioTotal = round($tutor->precio_hora * $horas, 2);
        $comisionPct = Configuracion::where('clave', 'comision_porcentaje')->value('valor') ?? 20;
        $comisionMonto = round($precioTotal * ($comisionPct / 100), 2);
        $montoTutor = round($precioTotal - $comisionMonto, 2);

        // Crear la clase
        $clase = Clase::create([
            'estudiante_id' => Auth::id(),
            'tutor_id' => $tutor->id,
            'materia_id' => $materia->id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin->format('H:i'),
            'duracion_min' => $request->duracion_min,
            'precio_total' => $precioTotal,
            'comision_porcentaje' => $comisionPct,
            'comision_monto' => $comisionMonto,
            'monto_tutor' => $montoTutor,
            'estado' => 'pendiente_pago',
        ]);

        return redirect()->route('reservas.pago', $clase->id)
            ->with('success', 'Reserva creada. Ahora completa el pago.');
    }
}
