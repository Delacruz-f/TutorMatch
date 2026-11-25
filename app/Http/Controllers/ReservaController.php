<?php

namespace App\Http\Controllers;

use App\Mail\ClaseCanceladaMail;
use App\Models\Clase;
use App\Models\Notificacion;
use App\Models\Pago;
use App\Models\Tutor;
use App\Models\Materia;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            ->whereHas('user', function ($query) {
                $query->where('estado', 'activo');
            })
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

        $tutor = Tutor::where('aprobacion', 'aprobado')
            ->whereHas('user', function ($query) {
                $query->where('estado', 'activo');
            })
            ->findOrFail($request->tutor_id);
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

    public function cancelar(int $id)
    {
        $resultado = DB::transaction(function () use ($id) {
            $clase = Clase::where('estudiante_id', Auth::id())
                ->lockForUpdate()
                ->findOrFail($id);

            if (! in_array($clase->estado, ['pendiente_pago', 'pendiente', 'aceptada'], true)) {
                return ['error' => __('ui.class_cancel_not_allowed')];
            }

            $inicioClase = Carbon::parse($clase->fecha->format('Y-m-d').' '.$clase->hora_inicio);
            if ($inicioClase->lt(now()->addHours(24))) {
                return ['error' => __('ui.cancel_24_hours_required')];
            }

            $clase->load(['tutor.user', 'estudiante', 'materia']);

            $pago = Pago::where('clase_id', $clase->id)
                ->where('concepto', 'clase')
                ->whereIn('estado', ['pagado', 'pendiente'])
                ->lockForUpdate()
                ->first();

            $estabaPagado = $pago?->estado === 'pagado';
            $reembolsoSolicitado = $pago !== null;
            $montoReembolso = $pago?->monto;

            if ($pago) {
                $pago->update(['estado' => 'reembolsado']);
            }

            $clase->update([
                'estado' => 'cancelada',
                'sala_jitsi' => null,
                'pago_liberado' => false,
            ]);

            return compact('clase', 'estabaPagado', 'reembolsoSolicitado', 'montoReembolso');
        });

        if (isset($resultado['error'])) {
            return redirect()->route('estudiante.dashboard')->with('error', $resultado['error']);
        }

        /** @var Clase $clase */
        $clase = $resultado['clase'];
        $datosClase = [
            'subject' => $clase->materia->nombre,
            'date' => $clase->fecha->format('d/m/Y'),
        ];
        if ($resultado['reembolsoSolicitado']) {
            $datosClase['amount'] = number_format((float) $resultado['montoReembolso'], 2);
        }

        $mensajeEstudiante = $resultado['reembolsoSolicitado']
            ? __('ui.class_cancelled_student_refund_notification', $datosClase)
            : __('ui.class_cancelled_student_notification', $datosClase);

        Notificacion::enviar(
            $clase->estudiante_id,
            __('ui.class_cancelled_notification_title'),
            $mensajeEstudiante,
            route('estudiante.dashboard'),
            'clase'
        );

        if ($resultado['estabaPagado']) {
            Notificacion::enviar(
                $clase->tutor->user_id,
                __('ui.class_cancelled_notification_title'),
                __('ui.class_cancelled_tutor_notification', $datosClase + [
                    'student' => $clase->estudiante->nombre,
                ]),
                route('tutor.dashboard'),
                'clase'
            );
        }

        if ($resultado['reembolsoSolicitado']) {
            Notificacion::enviarARol(
                'administrador',
                __('ui.class_refund_admin_notification_title'),
                __('ui.class_refund_admin_notification_message', $datosClase + [
                    'student' => $clase->estudiante->nombre,
                    'tutor' => $clase->tutor->user->nombre.' '.$clase->tutor->user->apellido,
                ]),
                route('admin.pagos.index'),
                'pago'
            );
        }

        try {
            Mail::to($clase->tutor->user->email)->send(
                new ClaseCanceladaMail(
                    $clase,
                    false,
                    $resultado['reembolsoSolicitado'],
                    $resultado['montoReembolso']
                )
            );
        } catch (\Exception $e) {
            Log::error('Error enviando email de cancelación al tutor: '.$e->getMessage(), [
                'clase_id' => $clase->id,
            ]);
        }

        try {
            Mail::to($clase->estudiante->email)->send(
                new ClaseCanceladaMail(
                    $clase,
                    true,
                    $resultado['reembolsoSolicitado'],
                    $resultado['montoReembolso']
                )
            );
        } catch (\Exception $e) {
            Log::error('Error enviando email de confirmación de cancelación al estudiante: '.$e->getMessage(), [
                'clase_id' => $clase->id,
            ]);
        }

        return redirect()->route('estudiante.dashboard')
            ->with('success', __('ui.class_cancel_success'));
    }
}
