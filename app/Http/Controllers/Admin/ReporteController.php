<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Pago;
use App\Models\Suscripcion;
use App\Models\Retiro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        // Ingresos por comisiones de clases
        $comisiones = Clase::whereBetween('fecha', [$desde, $hasta])
            ->where('estado', 'completada')
            ->sum('comision_monto');

        // Ingresos por suscripciones
        $ingresosSuscripciones = Pago::where('concepto', 'suscripcion')
            ->where('estado', 'pagado')
            ->whereBetween(DB::raw('DATE(created_at)'), [$desde, $hasta])
            ->sum('monto');

        // Total
        $totalIngresos = $comisiones + $ingresosSuscripciones;

        // Clases completadas
        $clasesCompletadas = Clase::whereBetween('fecha', [$desde, $hasta])
            ->where('estado', 'completada')
            ->count();

        // Nuevos usuarios
        $nuevosUsuarios = User::whereBetween(DB::raw('DATE(created_at)'), [$desde, $hasta])->count();

        // Retiros procesados en el período
        $retirosPagados = Retiro::where('estado', 'pagado')
            ->whereBetween(DB::raw('DATE(procesado_en)'), [$desde, $hasta])
            ->sum('monto');

        // Top 5 tutores por ingresos en el período
        $topTutores = Clase::select('tutor_id', DB::raw('SUM(monto_tutor) as total'))
            ->whereBetween('fecha', [$desde, $hasta])
            ->where('estado', 'completada')
            ->groupBy('tutor_id')
            ->orderByDesc('total')
            ->with('tutor.user')
            ->limit(5)
            ->get();

        return view('admin.reportes.index', compact(
            'desde', 'hasta',
            'comisiones', 'ingresosSuscripciones', 'totalIngresos',
            'clasesCompletadas', 'nuevosUsuarios', 'retirosPagados', 'topTutores'
        ));
    }
    public function pdf(Request $request)
{
    $desde = $request->input('desde', now()->startOfMonth()->toDateString());
    $hasta = $request->input('hasta', now()->toDateString());

    // Mismo cálculo que en index()
    $comisiones = Clase::whereBetween('fecha', [$desde, $hasta])
        ->where('estado', 'completada')
        ->sum('comision_monto');

    $ingresosSuscripciones = Pago::where('concepto', 'suscripcion')
        ->where('estado', 'pagado')
        ->whereBetween(DB::raw('DATE(created_at)'), [$desde, $hasta])
        ->sum('monto');

    $totalIngresos = $comisiones + $ingresosSuscripciones;

    $clasesCompletadas = Clase::whereBetween('fecha', [$desde, $hasta])
        ->where('estado', 'completada')
        ->count();

    $nuevosUsuarios = User::whereBetween(DB::raw('DATE(created_at)'), [$desde, $hasta])->count();

    $retirosPagados = Retiro::where('estado', 'pagado')
        ->whereBetween(DB::raw('DATE(procesado_en)'), [$desde, $hasta])
        ->sum('monto');

    $topTutores = Clase::select('tutor_id', DB::raw('SUM(monto_tutor) as total'))
        ->whereBetween('fecha', [$desde, $hasta])
        ->where('estado', 'completada')
        ->groupBy('tutor_id')
        ->orderByDesc('total')
        ->with('tutor.user')
        ->limit(10)
        ->get();

    // Detalle de clases completadas en el período
    $detalleClases = Clase::with(['tutor.user', 'estudiante', 'materia'])
        ->whereBetween('fecha', [$desde, $hasta])
        ->where('estado', 'completada')
        ->orderBy('fecha', 'desc')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reportes.pdf', compact(
        'desde', 'hasta',
        'comisiones', 'ingresosSuscripciones', 'totalIngresos',
        'clasesCompletadas', 'nuevosUsuarios', 'retirosPagados',
        'topTutores', 'detalleClases'
    ));

    $pdf->setPaper('a4', 'portrait');

    return $pdf->download('reporte-tutormatch-' . $desde . '-al-' . $hasta . '.pdf');
}
}
