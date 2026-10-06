<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use App\Models\Tutor;
use Illuminate\Http\Request;

class SuscripcionAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Suscripcion::with(['tutor.user']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        $suscripciones = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Estadísticas
        $activas = Suscripcion::where('estado', 'activa')->where('fecha_fin', '>=', now()->toDateString())->count();
        $ingresosMes = Suscripcion::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('monto');

        $tutoresDestacados = Tutor::with('user')
            ->whereIn('plan', ['destacado', 'premium'])
            ->where('plan_vence', '>=', now()->toDateString())
            ->orderBy('plan', 'desc')
            ->get();

        return view('admin.suscripciones.index', compact('suscripciones', 'activas', 'ingresosMes', 'tutoresDestacados'));
    }

    public function cancelar($id)
    {
        $suscripcion = Suscripcion::with('tutor')->findOrFail($id);
        $suscripcion->update(['estado' => 'cancelada']);

        // Revertir plan del tutor si coincide
        $tutor = $suscripcion->tutor;
        if ($tutor && $tutor->plan === $suscripcion->plan) {
            $tutor->update(['plan' => 'basico', 'plan_vence' => null]);
        }

        return back()->with('success', 'Suscripción cancelada.');
    }

    public function extender(Request $request, $id)
    {
        $request->validate(['meses' => 'required|integer|min:1|max:12']);

        $suscripcion = Suscripcion::with('tutor')->findOrFail($id);
        $nuevaFecha = \Carbon\Carbon::parse($suscripcion->fecha_fin)->addMonths($request->meses);

        $suscripcion->update(['fecha_fin' => $nuevaFecha, 'estado' => 'activa']);

        if ($suscripcion->tutor) {
            $suscripcion->tutor->update([
                'plan' => $suscripcion->plan,
                'plan_vence' => $nuevaFecha,
            ]);
        }

        return back()->with('success', "Suscripción extendida {$request->meses} meses.");
    }
}