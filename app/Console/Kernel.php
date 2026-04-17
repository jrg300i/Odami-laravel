<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Agregar tus comandos aquí
    ];

    protected function schedule(Schedule $schedule)
    {
        // Backup automático diario a las 2 AM
        $schedule->command('backup:automatico')
                 ->dailyAt('02:00')
                 ->appendOutputTo(storage_path('logs/backup.log'));

        // Comprimir fotos antiguas semanalmente
        $schedule->command('fotos:comprimir')
                 ->weekly()
                 ->sundays()
                 ->at('03:00');

        // Verificar espacio en disco diario
        $schedule->command('disco:verificar')
                 ->dailyAt('04:00');

        // Limpiar backups antiguos mensualmente
        $schedule->command('backup:limpiar')
                 ->monthlyOn(1, '05:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}