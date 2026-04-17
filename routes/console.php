<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Comando inspiracional de Laravel
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tareas programadas
Schedule::command('backup:automatico')->dailyAt('02:00');
Schedule::command('fotos:comprimir-antiguas')->weekly();
Schedule::command('backup:limpiar-antiguos')->monthly();
Schedule::command('verificar:espacio-disco')->daily();