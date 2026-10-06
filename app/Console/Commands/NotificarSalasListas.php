<?php

namespace App\Console\Commands;

use App\Models\Clase;
use App\Models\Notificacion;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotificarSalasListas extends Command
{
    protected $signature = 'clases:notificar-salas';
    protected $description = 'Notifica a estudiante y tutor cuando la sala Jitsi está por abrir (5 min antes)';

    public function handle()
    {
        $ahora = Carbon::now();
        $limite = $ahora->copy()->addMinutes(5);

        // Clases aceptadas que empiezan entre ahora y 5 min, sin notificación enviada
        $clases = Clase::with(['estudiante', 'tutor.user', 'materia'])
            ->where('estado', 'aceptada')
            ->where('recordatorio_enviado', 0)
            ->whereDate('fecha', $ahora->toDateString())
            ->get()
            ->filter(function ($clase) use ($ahora, $limite) {
                $inicio = $clase->fecha->copy()->setTimeFrom(Carbon::parse($clase->hora_inicio));
                return $inicio->between($ahora, $limite);
            });

        foreach ($clases as $clase) {
            // Notificar al estudiante
            Notificacion::enviar(
                $clase->estudiante_id,
                '🎥 Sala lista para tu clase',
                "Tu clase de {$clase->materia->nombre} comienza en 5 minutos. ¡Entra a la sala!",
                route('clases.sala', $clase->id)
            );

            // Notificar al tutor
            Notificacion::enviar(
                $clase->tutor->user_id,
                '🎥 Sala lista para tu clase',
                "Tu clase de {$clase->materia->nombre} con {$clase->estudiante->nombre} comienza en 5 minutos.",
                route('clases.sala', $clase->id)
            );

            $clase->update(['recordatorio_enviado' => 1]);
            $this->info("Notificación enviada para clase #{$clase->id}");
        }

        $this->info("Total: " . $clases->count() . " notificaciones enviadas.");
    }
}