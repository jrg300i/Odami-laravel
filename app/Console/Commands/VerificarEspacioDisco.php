<?php

namespace App\Console\Commands;

use App\Services\FotoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class VerificarEspacioDisco extends Command
{
    protected $signature = 'verificar:espacio-disco {--limite=90 : Porcentaje límite de uso}';
    protected $description = 'Verifica el espacio disponible en disco y alerta si está cerca del límite';

    protected $fotoService;

    public function __construct(FotoService $fotoService)
    {
        parent::__construct();
        $this->fotoService = $fotoService;
    }

    public function handle()
    {
        $limite = $this->option('limite');
        
        // Verificar espacio en disco principal
        $total = disk_total_space(storage_path());
        $libre = disk_free_space(storage_path());
        $usado = $total - $libre;
        $porcentajeUsado = ($usado / $total) * 100;

        $this->info("Espacio en disco:");
        $this->info(" - Total: " . $this->formatearBytes($total));
        $this->info(" - Usado: " . $this->formatearBytes($usado) . " ({$porcentajeUsado}%)");
        $this->info(" - Libre: " . $this->formatearBytes($libre));

        if ($porcentajeUsado >= $limite) {
            $this->error("¡ALERTA! El disco está al {$porcentajeUsado}% de su capacidad.");
            $this->alert("Se recomienda limpiar archivos temporales y comprimir fotos antiguas.");
            
            // Mostrar uso detallado por carpetas
            $this->info("\nUso detallado por carpetas:");
            $usoEspacio = $this->fotoService->obtenerUsoEspacio();
            foreach ($usoEspacio as $carpeta => $datos) {
                $this->info(" - {$carpeta}: {$datos['tamanio_mb']} MB");
            }

            return 1;
        }

        $this->info("El espacio en disco está dentro de los límites normales.");
        return 0;
    }

    private function formatearBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}