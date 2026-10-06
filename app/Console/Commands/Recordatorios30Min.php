<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioClaseMail;
use App\Models\Clase;
use App\Models\Notificacion;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Recordatorios30Min extends Command
{
    protected $signature = 'clases:recordatorios-30min';
    protected $description = 'Envía recordatorios (email + BD) 30 minutos antes de cada clase';

    public function handle()
    {
        $ahora = Carbon::now();
        $limite = $ahora->copy()->addMinutes(30);

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
            // Notificación al estudiante
            Notificacion::enviar(
                $clase->estudiante_id,
                '⏰ Tu clase comienza en 30 min',
                "Clase de {$clase->materia->nombre} con {$clase->tutor->user->nombre} a las " . substr($clase->hora_inicio, 0, 5) . ".",
                route('clases.sala', $clase->id)
            );

            // Notificación al tutor
            Notificacion::enviar(
                $clase->tutor->user_id,
                '⏰ Tu clase comienza en 30 min',
                "Clase de {$clase->materia->nombre} con {$clase->estudiante->nombre} a las " . substr($clase->hora_inicio, 0, 5) . ".",
                route('clases.sala', $clase->id)
            );

            // Email al estudiante
            try {
                Mail::to($clase->estudiante->email)->send(new RecordatorioClaseMail($clase));
            } catch (\Exception $e) {
                \Log::error('Error email recordatorio estudiante: ' . $e->getMessage());
            }

            $clase->update(['recordatorio_enviado' => 1]);
            $this->info("Recordatorio enviado para clase #{$clase->id}");
        }

        $this->info("Total: " . $clases->count() . " recordatorios enviados.");
    }
}