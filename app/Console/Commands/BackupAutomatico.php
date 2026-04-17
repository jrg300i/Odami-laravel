<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupAutomatico extends Command
{
    protected $signature = 'backup:automatico';
    protected $description = 'Realiza backup automático de la aplicación';

    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    public function handle()
    {
        if (!config('backup.automatico', false)) {
            $this->info('Backup automático desactivado en configuración.');
            return;
        }

        $this->info('Iniciando backup automático...');

        try {
            $backupLog = $this->backupService->crearBackup(
                'completo',
                'Backup automático programado',
                'Sistema'
            );

            $this->info('Backup automático completado exitosamente.');
            $this->info("Archivo: {$backupLog->archivo}");
            $this->info("Tamaño: {$backupLog->tamanio} MB");

        } catch (\Exception $e) {
            $this->error('Error en backup automático: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}