<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Listado completo de notificaciones del usuario.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $notificaciones = $user->notificaciones()->paginate(20);

        return view('notificaciones.index', compact('notificaciones'));
    }

    /**
     * Marcar una notificación como leída.
     */
    public function marcarLeida(int $id)
    {
        $notif = Notificacion::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['leida' => true]);

        // Si tiene URL, redirige ahí; si no, vuelve atrás.
        if ($notif->url) {
            return redirect($notif->url);
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
}
