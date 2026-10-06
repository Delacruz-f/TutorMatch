<?php

namespace App\Http\Controllers;

use App\Models\Retiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetiroController extends Controller
{
    /**
     * Listar retiros del tutor
     */
    public function index()
    {
        $tutor = Auth::user()->tutor;
        $retiros = Retiro::where('tutor_id', $tutor->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $saldo = $tutor->saldoDisponible();

        return view('tutor.retiros', compact('tutor', 'retiros', 'saldo'));
    }

    /**
     * Solicitar retiro
     */
    public function store(Request $request)
    {
        $tutor = Auth::user()->tutor;

        $request->validate([
            'monto' => 'required|numeric|min:10',
            'metodo' => 'required|in:yape,plin',
            'numero_cuenta' => 'required|string|max:20',
        ]);

        $saldo = $tutor->saldoDisponible();

        if ($request->monto > $saldo) {
            return back()->withErrors(['monto' => 'El monto excede tu saldo disponible (S/ ' . $saldo . ').']);
        }

        Retiro::create([
            'tutor_id' => $tutor->id,
            'monto' => $request->monto,
            'metodo' => $request->metodo,
            'numero_cuenta' => $request->numero_cuenta,
            'estado' => 'pendiente',
        ]);

        return back()->with('success', 'Solicitud de retiro enviada. El admin la procesará pronto.');
    }
}
