<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Recordatorio 30 min antes (email + BD)
Schedule::command('clases:recordatorios-30min')->everyMinute();

// Notificación "sala lista" 5 min antes
Schedule::command('clases:notificar-salas')->everyMinute();