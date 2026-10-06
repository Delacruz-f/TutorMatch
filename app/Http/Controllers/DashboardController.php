<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function estudiante()
{
    $user = \Illuminate\Support\Facades\Auth::user();

    $clases = \App\Models\Clase::with(['tutor.user', 'materia', 'pago', 'resena'])
        ->where('estudiante_id', $user->id)
        ->orderByDesc('fecha')
        ->orderByDesc('hora_inicio')
        ->get();

    return view('dashboards.estudiante', compact('clases'));
}
    public function tutor()
    {
        return view('dashboards.tutor');
    }

    public function admin()
{
    // Estadísticas globales
    $totalUsuarios = \App\Models\User::count();
    $totalEstudiantes = \App\Models\User::where('rol', 'estudiante')->count();
    $totalTutores = \App\Models\User::where('rol', 'tutor')->count();

    $tutoresPendientes = \App\Models\Tutor::where('aprobacion', 'pendiente')->count();
    $tutoresAprobados = \App\Models\Tutor::where('aprobacion', 'aprobado')->count();

    $pagosPendientes = \App\Models\Pago::where('estado', 'pendiente')->count();
    $retirosPendientes = \App\Models\Retiro::where('estado', 'pendiente')->count();

    // Ingresos del mes actual
    $ingresosMes = \App\Models\Clase::whereMonth('fecha', now()->month)
        ->whereYear('fecha', now()->year)
        ->where('estado', 'completada')
        ->sum('comision_monto');

    $ingresosSuscripciones = \App\Models\Pago::where('concepto', 'suscripcion')
        ->where('estado', 'pagado')
        ->whereMonth('created_at', now()->month)
        ->sum('monto');

    $ingresosTotalesMes = $ingresosMes + $ingresosSuscripciones;

    // Últimas 5 clases reservadas
    $ultimasClases = \App\Models\Clase::with(['estudiante', 'tutor.user', 'materia'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Últimos 5 pagos pendientes
    $ultimosPagos = \App\Models\Pago::with('user')
        ->where('estado', 'pendiente')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return view('dashboards.admin', compact(
        'totalUsuarios',
        'totalEstudiantes',
        'totalTutores',
        'tutoresPendientes',
        'tutoresAprobados',
        'pagosPendientes',
        'retirosPendientes',
        'ingresosTotalesMes',
        'ultimasClases',
        'ultimosPagos'
    ));
}
}
