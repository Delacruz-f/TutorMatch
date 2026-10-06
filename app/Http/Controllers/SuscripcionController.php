<?php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use App\Models\Configuracion;
use App\Models\Pago;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuscripcionController extends Controller
{
    /**
     * Listado de planes disponibles.
     */
    public function index()
    {
        $tutor = Auth::user()->tutor;
        $precioDestacado = Configuracion::where('clave', 'precio_plan_destacado')->value('valor') ?? 20;
        $precioPremium = Configuracion::where('clave', 'precio_plan_premium')->value('valor') ?? 50;

        $suscripcionActiva = $tutor->suscripcionActiva;
        $historial = $tutor->suscripciones()->orderBy('created_at', 'desc')->limit(10)->get();

        return view('tutor.suscripciones', compact('tutor', 'precioDestacado', 'precioPremium', 'suscripcionActiva', 'historial'));
    }

    /**
     * Crear pago de suscripción.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:destacado,premium',
            'metodo' => 'required|in:yape,plin',
            'referencia' => 'required|string|max:100',
            'comprobante_imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $tutor = Auth::user()->tutor;

        // Verificar que no tenga ya una suscripción activa
        if ($tutor->suscripcionActiva) {
            return back()->with('error', 'Ya tienes una suscripción activa. Debes esperar a que venza.');
        }

        // Precio según plan
        $precio = $request->plan === 'premium'
            ? Configuracion::where('clave', 'precio_plan_premium')->value('valor') ?? 50
            : Configuracion::where('clave', 'precio_plan_destacado')->value('valor') ?? 20;

        // Guardar comprobante
        $path = $request->file('comprobante_imagen')->store('comprobantes', 'public');

        // Crear pago con concepto "suscripcion"
        Pago::create([
            'clase_id' => null,
            'user_id' => Auth::id(),
            'concepto' => 'suscripcion',
            'metodo' => $request->metodo,
            'monto' => $precio,
            'estado' => 'pendiente',
            'referencia' => $request->referencia . ' | PLAN:' . $request->plan,
            'comprobante_imagen' => $path,
        ]);

        // Notificar al admin
        $admins = \App\Models\User::where('rol', 'administrador')->get();
        foreach ($admins as $admin) {
            Notificacion::enviar(
                $admin->id,
                'Nueva solicitud de suscripción',
                "El tutor {$tutor->user->nombre} solicitó el plan {$request->plan}.",
                route('admin.pagos.index')
            );
        }

        return back()->with('success', 'Solicitud enviada. El admin validará tu pago y activará tu plan.');
    }
}
