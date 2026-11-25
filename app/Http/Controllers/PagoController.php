<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Notificacion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    /**
     * Mostrar formulario de pago para una clase
     */
    public function show($claseId)
    {
        $clase = Clase::with(['tutor.user', 'materia'])
            ->where('estudiante_id', Auth::id())
            ->findOrFail($claseId);

        // Si ya está pagada, redirigir
        if ($clase->estado !== 'pendiente_pago') {
            return redirect()->route('estudiante.dashboard')
                ->with('success', 'Esta clase ya fue pagada.');
        }

        return view('pagos.show', compact('clase'));
    }

    /**
     * Procesar pago (subida de comprobante)
     */
    public function store(Request $request, $claseId)
    {
        $request->validate([
            'metodo' => 'required|in:yape,plin,tarjeta',
            'referencia' => 'required|string|max:100',
            'comprobante_imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $clase = Clase::where('estudiante_id', Auth::id())
            ->where('estado', 'pendiente_pago')
            ->findOrFail($claseId);

        // Guardar comprobante (público)
        $path = $request->file('comprobante_imagen')->store('comprobantes', 'public');

        // Crear pago
        Pago::create([
            'clase_id' => $clase->id,
            'user_id' => Auth::id(),
            'concepto' => 'clase',
            'metodo' => $request->metodo,
            'monto' => $clase->precio_total,
            'estado' => 'pendiente',
            'referencia' => $request->referencia,
            'comprobante_imagen' => $path,
        ]);

        // Actualizar estado de la clase
        $clase->update(['estado' => 'pendiente']);

        // Notificar al administrador que debe validar el comprobante
        Notificacion::enviarARol(
            'administrador',
            'Nuevo comprobante de pago',
            Auth::user()->nombre . ' subió un comprobante de S/ ' . $clase->precio_total . ' para la clase de ' . $clase->materia->nombre . '.',
            route('admin.pagos.index'),
            'pago'
        );

        return redirect()->route('estudiante.dashboard')
            ->with('success', 'Pago enviado. Espera la validación del administrador.');
    }

    /**
     * Mostrar el historial de pagos de clases del estudiante autenticado.
     */
    public function historial()
    {
        $pagos = Pago::with(['clase.tutor.user', 'clase.materia'])
            ->where('user_id', Auth::id())
            ->where('concepto', 'clase')
            ->whereHas('clase')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pagos.historial', compact('pagos'));
    }

    /**
     * Mostrar el comprobante original de un pago propio.
     */
    public function comprobanteImagen($pagoId)
    {
        $pago = Pago::where('user_id', Auth::id())
            ->where('concepto', 'clase')
            ->findOrFail($pagoId);

        abort_unless(
            $pago->comprobante_imagen
                && Storage::disk('public')->exists($pago->comprobante_imagen),
            404
        );

        return Storage::disk('public')->response($pago->comprobante_imagen);
    }

    /**
 * Descargar comprobante en PDF de una clase pagada.
 */
public function comprobantePdf($claseId)
{
    $clase = \App\Models\Clase::with(['tutor.user', 'materia', 'estudiante', 'pago'])
        ->where('estudiante_id', Auth::id())
        ->findOrFail($claseId);

    // Solo si ya está pagada
    if (!in_array($clase->estado, ['pendiente', 'aceptada', 'completada'])) {
        abort(403, 'Esta clase aún no tiene comprobante disponible.');
    }

    $pago = $clase->pago;
    if (!$pago || $pago->estado !== 'pagado') {
        abort(403, 'El pago aún no ha sido validado.');
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pagos.comprobante', compact('clase', 'pago'));
    $pdf->setPaper('a4', 'portrait');

    return $pdf->download('comprobante-clase-' . $clase->id . '.pdf');
}
}
