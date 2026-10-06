<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Clase;
use App\Models\Retiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaccionAdminController extends Controller
{
    public function index(Request $request)
    {
        // PAGOS
        $queryPagos = Pago::with(['user', 'clase.tutor.user']);

        if ($request->filled('metodo')) {
            $queryPagos->where('metodo', $request->metodo);
        }
        if ($request->filled('concepto')) {
            $queryPagos->where('concepto', $request->concepto);
        }
        if ($request->filled('estado_pago')) {
            $queryPagos->where('estado', $request->estado_pago);
        }
        if ($request->filled('desde')) {
            $queryPagos->whereDate('created_at', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $queryPagos->whereDate('created_at', '<=', $request->hasta);
        }
        if ($request->filled('monto_min')) {
            $queryPagos->where('monto', '>=', $request->monto_min);
        }

        $pagos = $queryPagos->orderBy('created_at', 'desc')->limit(50)->get();

        // RETIROS
        $retiros = Retiro::with('tutor.user')
            ->when($request->filled('estado_retiro'), fn($q) => $q->where('estado', $request->estado_retiro))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Estadísticas
        $totalIngresos = Pago::where('estado', 'pagado')->sum('monto');
        $totalComisiones = Clase::where('estado', 'completada')->sum('comision_monto');
        $totalRetiros = Retiro::where('estado', 'pagado')->sum('monto');
        $pagosPendientes = Pago::where('estado', 'pendiente')->count();

        return view('admin.transacciones.index', compact(
            'pagos', 'retiros',
            'totalIngresos', 'totalComisiones', 'totalRetiros', 'pagosPendientes'
        ));
    }
}