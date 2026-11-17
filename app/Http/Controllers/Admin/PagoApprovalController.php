<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Pago;
use App\Models\Clase;
use Illuminate\Http\Request;

class PagoApprovalController extends Controller
{
    /**
     * Listar pagos pendientes de validación
     */
    public function index()
    {
        $pagosPendientes = Pago::with(['clase.tutor.user', 'clase.materia', 'user'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->get();

        $pagosRecientes = Pago::with(['clase.tutor.user', 'user'])
            ->whereIn('estado', ['pagado', 'fallido', 'reembolsado'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.pagos.index', compact('pagosPendientes', 'pagosRecientes'));
    }

    /**
     * Aprobar un pago
     */
    public function aprobar($id)
{
    $pago = Pago::with('clase')->findOrFail($id);

    if ($pago->estado !== 'pendiente') {
        return back()->with('error', 'Este pago ya fue procesado.');
    }

    $pago->update([
        'estado' => 'pagado',
        'pagado_en' => now(),
    ]);

    // Si es pago de CLASE
    if ($pago->concepto === 'clase' && $pago->clase) {
        $pago->clase->update(['estado' => 'pendiente']);

        // Notificar al tutor
        Notificacion::enviar(
            $pago->clase->tutor->user_id,
            'Nueva solicitud de clase',
            "Tienes una nueva solicitud de clase de {$pago->user->nombre} para {$pago->clase->materia->nombre}.",
            route('tutor.dashboard'),
            'clase'
        );

        // Notificar al estudiante que su pago fue validado
        Notificacion::enviar(
            $pago->user_id,
            '✅ Pago validado',
            "Tu pago de S/ {$pago->monto} para la clase de {$pago->clase->materia->nombre} fue aprobado.",
            route('estudiante.dashboard'),
            'pago'
        );
    }

    // Si es pago de SUSCRIPCIÓN
    if ($pago->concepto === 'suscripcion') {
        // Extraer plan de la referencia ("REF | PLAN:premium")
        $plan = 'destacado';
        if (preg_match('/PLAN:(\w+)/', $pago->referencia, $matches)) {
            $plan = $matches[1];
        }

        $tutor = $pago->user->tutor;
        if ($tutor) {
            $fechaInicio = now();
            $fechaFin = now()->addMonth();

            \App\Models\Suscripcion::create([
                'tutor_id' => $tutor->id,
                'plan' => $plan,
                'monto' => $pago->monto,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estado' => 'activa',
            ]);

            $tutor->update([
                'plan' => $plan,
                'plan_vence' => $fechaFin,
            ]);

            Notificacion::enviar(
                $tutor->user_id,
                '¡Suscripción activada!',
                "Tu plan {$plan} está activo hasta el " . $fechaFin->format('d/m/Y') . ".",
                route('tutor.suscripciones.index'),
                'suscripcion'
            );

            Notificacion::enviar(
                $pago->user_id,
                '✅ Pago validado',
                "Tu pago de S/ {$pago->monto} para el plan {$plan} fue aprobado.",
                route('tutor.suscripciones.index'),
                'pago'
            );
        }
    }

    return back()->with('success', 'Pago aprobado.');
}

    /**
     * Rechazar un pago
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:255',
        ]);

        $pago = Pago::with('clase')->findOrFail($id);

        if ($pago->estado !== 'pendiente') {
            return back()->with('error', 'Este pago ya fue procesado.');
        }

        $pago->update(['estado' => 'fallido']);

        // Devolver la clase a pendiente_pago para que el estudiante reintente
        if ($pago->clase) {
            $pago->clase->update([
                'estado' => 'pendiente_pago',
                'notas' => 'Pago rechazado: ' . $request->motivo,
            ]);

            // Notificar al estudiante dueño del pago
            \App\Models\Notificacion::enviar(
                $pago->user_id,
                '❌ Tu pago fue rechazado',
                "El pago de la clase de {$pago->clase->materia->nombre} fue rechazado. Motivo: {$request->motivo}",
                route('reservas.pago', $pago->clase_id),
                'pago'
            );
        } else {
            // Pago de suscripción rechazado
            \App\Models\Notificacion::enviar(
                $pago->user_id,
                '❌ Tu pago fue rechazado',
                "Tu pago de suscripción fue rechazado. Motivo: {$request->motivo}",
                route('tutor.suscripciones.index'),
                'pago'
            );
        }

        return back()->with('success', 'Pago rechazado.');
    }
}
