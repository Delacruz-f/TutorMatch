<?php

namespace App\Console\Commands;

use App\Models\Clase;
use App\Models\Notificacion;
use Illuminate\Console\Command;

class EnviarRecordatorios extends Command
{
    protected $signature = 'clases:recordatorios';
    protected $description = 'Envía recordatorios 30 min antes de cada clase aceptada';

    public function handle()
    {
        $ahora = now();
        $limite = $ahora->copy()->addMinutes(30);

        // Clases aceptadas que empiezan en los próximos 30 minutos
        // y que no hayan recibido ya recordatorio
        $clases = Clase::with(['estudiante', 'tutor.user', 'materia'])
            ->where('estado', 'aceptada')
            ->where('recordatorio_enviado', 0)
            ->whereDate('fecha', $ahora->toDateString())
            ->get()
            ->filter(function ($clase) use ($ahora, $limite) {
                $inicio = $clase->fecha->copy()->setTimeFrom(\Carbon\Carbon::parse($clase->hora_inicio));
                return $inicio->between($ahora, $limite);
            });

        foreach ($clases as $clase) {
            // Notificar al estudiante
            Notificacion::enviar(
                $clase->estudiante_id,
                '⏰ Recordatorio de clase',
                "Tu clase de {$clase->materia->nombre} empieza en 30 minutos.",
                route('clases.sala', $clase->id)
            );

            // Notificar al tutor
            Notificacion::enviar(
                $clase->tutor->user_id,
                '⏰ Recordatorio de clase',
                "Tu clase de {$clase->materia->nombre} con {$clase->estudiante->nombre} empieza en 30 minutos.",
                route('clases.sala', $clase->id)
            );

            $clase->update(['recordatorio_enviado' => 1]);
            $this->info("Recordatorio enviado para clase #{$clase->id}");
        }

        $this->info("Total recordatorios enviados: " . $clases->count());
    }
}
