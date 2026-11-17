<?php

namespace App\Events;

use App\Models\Notificacion;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class NotificacionCreada implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    public readonly string $notificationId;

    public readonly string $userId;

    public readonly string $titulo;

    public readonly string $mensaje;

    public function __construct(Notificacion $notificacion)
    {
        $this->notificationId = (string) $notificacion->getKey();
        $this->userId = (string) $notificacion->user_id;
        $this->titulo = $notificacion->titulo;
        $this->mensaje = $notificacion->mensaje;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('users.'.$this->userId)];
    }

    public function broadcastAs(): string
    {
        return 'notification.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificationId,
            'titulo' => $this->titulo,
            'mensaje' => $this->mensaje,
            'unread_count' => Notificacion::query()
                ->where('user_id', $this->userId)
                ->noLeidas()
                ->count(),
        ];
    }
}
