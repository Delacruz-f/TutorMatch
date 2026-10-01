<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use Illuminate\Http\Request;

class TutorApprovalController extends Controller
{
    /**
     * Listar tutores pendientes de aprobación
     */
    public function index()
    {
        $tutoresPendientes = Tutor::with(['user', 'materias'])
            ->where('aprobacion', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        $tutoresAprobados = Tutor::with(['user', 'materias'])
            ->where('aprobacion', 'aprobado')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.tutores.index', compact('tutoresPendientes', 'tutoresAprobados'));
    }

    /**
     * Aprobar tutor
     */
    public function aprobar($id)
    {
        $tutor = Tutor::findOrFail($id);
        $tutor->update([
            'aprobacion' => 'aprobado',
            'motivo_rechazo' => null,
        ]);

        return back()->with('success', "Tutor {$tutor->user->nombre} aprobado correctamente.");
    }

    /**
     * Rechazar tutor
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:255',
        ]);

        $tutor = Tutor::findOrFail($id);
        $tutor->update([
            'aprobacion' => 'rechazado',
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        return back()->with('success', "Tutor {$tutor->user->nombre} rechazado.");
    }
}
