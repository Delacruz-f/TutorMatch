<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Retiro;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class RetiroAdminController extends Controller
{
    public function index()
    {
        $pendientes = Retiro::with('tutor.user')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->get();

        $historial = Retiro::with('tutor.user')
            ->whereIn('estado', ['pagado', 'rechazado'])
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.retiros.index', compact('pendientes', 'historial'));
    }

    public function pagar($id)
    {
        $retiro = Retiro::with('tutor.user')->findOrFail($id);

        if ($retiro->estado !== 'pendiente') {
            return back()->with('error', 'Este retiro ya fue procesado.');
        }

        $retiro->update([
            'estado' => 'pagado',
            'procesado_en' => now(),
        ]);

        Notificacion::enviar(
            $retiro->tutor->user_id,
            'Retiro procesado',
            "Tu retiro de S/ {$retiro->monto} fue pagado a tu {$retiro->metodo}.",
            route('tutor.retiros.index')
        );

        return back()->with('success', 'Retiro marcado como pagado.');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:255',
        ]);

        $retiro = Retiro::with('tutor.user')->findOrFail($id);

        if ($retiro->estado !== 'pendiente') {
            return back()->with('error', 'Este retiro ya fue procesado.');
        }

        $retiro->update([
            'estado' => 'rechazado',
            'procesado_en' => now(),
        ]);

        Notificacion::enviar(
            $retiro->tutor->user_id,
            'Retiro rechazado',
            "Tu retiro de S/ {$retiro->monto} fue rechazado. Motivo: {$request->motivo}",
            route('tutor.retiros.index')
        );

        return back()->with('success', 'Retiro rechazado.');
    }
}
