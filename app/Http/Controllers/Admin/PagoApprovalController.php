<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        \App\Models\Notificacion::enviar(
            $pago->clase->tutor->user_id,
            'Nueva solicitud de clase',
            "Tienes una nueva solicitud de clase de {$pago->user->nombre} para {$pago->clase->materia->nombre}.",
            route('tutor.dashboard')
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

            \App\Models\Notificacion::enviar(
                $tutor->user_id,
                '¡Suscripción activada!',
                "Tu plan {$plan} está activo hasta el " . $fechaFin->format('d/m/Y') . ".",
                route('tutor.dashboard')
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
        }

        return back()->with('success', 'Pago rechazado.');
    }
}
