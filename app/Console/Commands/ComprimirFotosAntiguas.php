<?php

namespace App\Console\Commands;

use App\Services\FotoService;
use Illuminate\Console\Command;

class ComprimirFotosAntiguas extends Command
{
    protected $signature = 'fotos:comprimir-antiguas {--dias=30 : Número de días para considerar una foto como antigua}';
    protected $description = 'Comprime fotos antiguas para ahorrar espacio';

    protected $fotoService;

    public function __construct(FotoService $fotoService)
    {
        parent::__construct();
        $this->fotoService = $fotoService;
    }

    public function handle()
    {
        $dias = $this->option('dias');
        
        $this->info("Comprimiendo fotos más antiguas de {$dias} días...");

        $comprimidas = $this->fotoService->comprimirFotosAntiguas($dias);

        if ($comprimidas > 0) {
            $this->info("Se comprimieron {$comprimidas} fotos exitosamente.");
            
            // Limpiar archivos temporales
            $temporalesEliminados = $this->fotoService->limpiarFotosTemporales();
            if ($temporalesEliminados > 0) {
                $this->info("Se eliminaron {$temporalesEliminados} archivos temporales.");
            }
        } else {
            $this->info('No se encontraron fotos para comprimir.');
        }

        // Mostrar uso de espacio
        $usoEspacio = $this->fotoService->obtenerUsoEspacio();
        $this->info("\nUso actual de espacio:");
        foreach ($usoEspacio as $carpeta => $datos) {
            $this->info(" - {$carpeta}: {$datos['tamanio_mb']} MB");
        }

        return 0;
    }
}