<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Historial de notificaciones del usuario.
     *
     * Parámetros opcionales:
     *  - estado: todas | no_leidas | leidas
     *  - tipo:   clave de Notificacion::TIPOS
     *  - q:      búsqueda por título/mensaje
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $estado = in_array($request->input('estado'), ['no_leidas', 'leidas'], true)
            ? $request->input('estado')
            : 'todas';

        $tipo = array_key_exists((string) $request->input('tipo'), Notificacion::TIPOS)
            ? $request->input('tipo')
            : null;

        $busqueda = trim((string) $request->input('q', ''));

        $notificaciones = $user->notificaciones()
            ->when($estado === 'no_leidas', fn ($q) => $q->where('leida', false))
            ->when($estado === 'leidas', fn ($q) => $q->where('leida', true))
            ->deTipo($tipo)
            ->when($busqueda !== '', function ($q) use ($busqueda) {
                $q->where(function ($sub) use ($busqueda) {
                    $sub->where('titulo', 'like', "%{$busqueda}%")
                        ->orWhere('mensaje', 'like', "%{$busqueda}%");
                });
            })
            ->paginate(15)
            ->withQueryString();

        $total = $user->notificaciones()->count();
        $noLeidas = $user->notificacionesNoLeidas()->count();
        $leidas = $total - $noLeidas;
        $porTipo = $user->notificaciones()
            ->selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo')
            ->toArray();

        return view('notificaciones.index', compact(
            'notificaciones',
            'total',
            'noLeidas',
            'leidas',
            'porTipo',
            'estado',
            'tipo',
            'busqueda'
        ));
    }

    /**
     * Marcar una notificación como leída y abrir su destino si es seguro.
     */
    public function marcarLeida(int $id)
    {
        $notif = Notificacion::where('user_id', Auth::id())->findOrFail($id);

        if (! $notif->leida) {
            $notif->update(['leida' => true]);
        }

        // Si tiene URL interna, redirige ahí; si no, vuelve atrás.
        if ($url = $notif->urlSegura()) {
            return redirect($url);
        }

        return back();
    }

    /**
     * Marcar todas como leídas.
     */
    public function marcarTodasLeidas()
    {
        /** @var User $user */
        $user = Auth::user();

        $user->notificacionesNoLeidas()->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    /**
     * Eliminar una notificación del historial.
     */
    public function destroy(int $id)
    {
        $notif = Notificacion::where('user_id', Auth::id())->findOrFail($id);
        $notif->delete();

        return back()->with('success', 'Notificación eliminada.');
    }

    /**
     * Vaciar el historial (todo o solo las leídas).
     */
    public function vaciar(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $soloLeidas = $request->boolean('solo_leidas');

        $query = $user->notificaciones();

        if ($soloLeidas) {
            $query->where('leida', true);
        }

        $eliminadas = $query->delete();

        return back()->with(
            'success',
            $soloLeidas
                ? "Se eliminaron {$eliminadas} notificaciones leídas."
                : "Se vació el historial ({$eliminadas} notificaciones eliminadas)."
        );
    }
}
