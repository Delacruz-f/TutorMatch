<?php

namespace Tests\Unit;

use App\Events\NotificacionCreada;
use App\Models\Notificacion;
use Tests\TestCase;

class NotificacionCreadaTest extends TestCase
{
    public function test_it_targets_the_notification_owners_private_channel(): void
    {
        $notificacion = new Notificacion;
        $notificacion->forceFill([
            'id' => 7,
            'user_id' => 42,
            'titulo' => 'Aviso',
            'mensaje' => 'Prueba',
        ]);

        $event = new NotificacionCreada($notificacion);

        $this->assertSame('private-users.42', $event->broadcastOn()[0]->name);
    }
}
