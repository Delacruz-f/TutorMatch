<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Pago;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CulqiController extends Controller
{
    /**
     * Formulario de pago con tarjeta.
     */
    public function form($claseId)
    {
        $clase = Clase::with(['tutor.user', 'materia'])
            ->where('estudiante_id', Auth::id())
            ->where('estado', 'pendiente_pago')
            ->findOrFail($claseId);

        $publicKey = config('services.culqi.public_key');

        return view('pagos.tarjeta', compact('clase', 'publicKey'));
    }

    /**
     * Procesar el pago (recibe el token generado por Culqi.js).
     */
    public function procesar(Request $request, $claseId)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
        ]);

        $clase = Clase::with(['tutor.user', 'materia'])
            ->where('estudiante_id', Auth::id())
            ->where('estado', 'pendiente_pago')
            ->findOrFail($claseId);

        $modo = config('services.culqi.mode', 'simulated');
        $secretKey = config('services.culqi.secret_key');

        // Si está en modo simulado, aprueba directamente sin llamar a Culqi
        if ($modo === 'simulated') {
            return $this->registrarPagoExitoso($clase, 'sim_' . uniqid(), $request->email);
        }

        // Modo live: llamar a la API real de Culqi
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.culqi.com/v2/charges', [
                'amount' => (int) round($clase->precio_total * 100), // en céntimos
                'currency_code' => 'PEN',
                'email' => $request->email,
                'source_id' => $request->token,
                'description' => "Clase de {$clase->materia->nombre} - TutorMatch",
            ]);

            if ($response->failed()) {
                Log::error('Culqi error: ' . $response->body());
                return back()->with('error', 'No se pudo procesar el pago: ' . ($response->json('user_message') ?? 'Error desconocido'));
            }

            $chargeId = $response->json('id');
            return $this->registrarPagoExitoso($clase, $chargeId, $request->email);

        } catch (\Exception $e) {
            Log::error('Culqi exception: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar el pago con tarjeta.');
        }
    }

    /**
     * Registra el pago exitoso en la BD y notifica.
     */
    private function registrarPagoExitoso(Clase $clase, string $referencia, string $email)
    {
        Pago::create([
            'clase_id' => $clase->id,
            'user_id' => Auth::id(),
            'concepto' => 'clase',
            'metodo' => 'tarjeta',
            'monto' => $clase->precio_total,
            'estado' => 'pagado',
            'referencia' => 'CULQI-' . $referencia,
            'pagado_en' => now(),
        ]);

        // La clase pasa directo a "pendiente" (esperando tutor) porque el pago ya se validó
        $clase->update(['estado' => 'pendiente']);

        // Notificar al tutor
        Notificacion::enviar(
            $clase->tutor->user_id,
            '💳 Nueva clase pagada',
            "Tienes una nueva solicitud de {$clase->estudiante->nombre} para {$clase->materia->nombre}.",
            route('tutor.dashboard')
        );

        return redirect()->route('estudiante.dashboard')
            ->with('success', '¡Pago con tarjeta exitoso! El tutor recibió tu solicitud.');
    }
}