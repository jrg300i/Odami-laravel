<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class LimpiarRespaldosAntiguos extends Command
{
    protected $signature = 'backup:limpiar-antiguos';
    protected $description = 'Elimina backups antiguos según la política de retención';

    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    public function handle()
    {
        $this->info('Iniciando limpieza de backups antiguos...');

        $eliminados = $this->backupService->limpiarBackupsAntiguos();

        if ($eliminados > 0) {
            $this->info("Se eliminaron {$eliminados} backups antiguos.");
        } else {
            $this->info('No se encontraron backups antiguos para eliminar.');
        }

        // Mostrar estadísticas actuales
        $estadisticas = $this->backupService->obtenerEstadisticas();
        $this->info("\nEstadísticas actuales de backups:");
        $this->info(" - Total de backups: {$estadisticas['total_backups']}");
        $this->info(" - Tamaño total: {$estadisticas['tamanio_total']} MB");
        $this->info(" - Backups últimos 30 días: {$estadisticas['backups_30_dias']}");
        $this->info(" - Tasa de éxito: " . round($estadisticas['tasa_exito'], 2) . "%");

        return 0;
    }
}