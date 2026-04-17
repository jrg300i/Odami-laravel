<?php

namespace App\Services;

use App\Models\BackupLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class BackupService
{
    public function crearBackup($tipo, $observaciones = null, $usuario = 'Sistema')
    {
        $inicio = now();
        $backupLog = BackupLog::create([
            'tipo' => $tipo,
            'estado' => 'en_proceso',
            'observaciones' => $observaciones,
            'iniciado_en' => $inicio,
            'detalles' => ['usuario' => $usuario, 'tipo_backup' => $tipo]
        ]);

        try {
            $nombreArchivo = 'backup_' . $tipo . '_' . now()->format('Y-m-d_H-i-s') . '.zip';
            $rutaBackup = 'backups/manuales/' . $nombreArchivo;

            // Crear backup según el tipo
            switch ($tipo) {
                case 'base_datos':
                    $this->backupBaseDatos($rutaBackup);
                    break;
                case 'archivos':
                    $this->backupArchivos($rutaBackup);
                    break;
                case 'completo':
                    $this->backupCompleto($rutaBackup);
                    break;
            }

            $tamanio = Storage::size($rutaBackup) / 1024 / 1024; // MB

            $backupLog->update([
                'estado' => 'completado',
                'archivo' => $nombreArchivo,
                'ubicacion' => $rutaBackup,
                'tamanio' => round($tamanio, 2),
                'completado_en' => now(),
                'detalles' => array_merge($backupLog->detalles ?? [], [
                    'duracion_segundos' => $inicio->diffInSeconds(now()),
                    'tamanio_mb' => round($tamanio, 2)
                ])
            ]);

            return $backupLog;

        } catch (Exception $e) {
            $backupLog->update([
                'estado' => 'fallido',
                'observaciones' => $observaciones . ' - ERROR: ' . $e->getMessage(),
                'completado_en' => now(),
                'detalles' => array_merge($backupLog->detalles ?? [], [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ])
            ]);

            throw $e;
        }
    }

    public function restaurarBackup($archivo, $usuario = 'Sistema')
    {
        $inicio = now();
        $backupLog = BackupLog::create([
            'tipo' => 'restauracion',
            'estado' => 'en_proceso',
            'archivo' => $archivo,
            'observaciones' => "Restauración iniciada por: {$usuario}",
            'iniciado_en' => $inicio,
            'detalles' => ['usuario' => $usuario]
        ]);

        try {
            $rutaCompleta = 'backups/manuales/' . $archivo;

            if (!Storage::exists($rutaCompleta)) {
                throw new Exception("Archivo de backup no encontrado: {$archivo}");
            }

            // Implementar lógica de restauración
            // Esto es un placeholder - en producción se necesitaría una implementación más robusta

            $backupLog->update([
                'estado' => 'completado',
                'completado_en' => now(),
                'detalles' => array_merge($backupLog->detalles ?? [], [
                    'duracion_segundos' => $inicio->diffInSeconds(now())
                ])
            ]);

            return $backupLog;

        } catch (Exception $e) {
            $backupLog->update([
                'estado' => 'fallido',
                'observaciones' => "Error en restauración: " . $e->getMessage(),
                'completado_en' => now(),
                'detalles' => array_merge($backupLog->detalles ?? [], [
                    'error' => $e->getMessage()
                ])
            ]);

            throw $e;
        }
    }

    public function obtenerBackups()
    {
        $backups = [];

        // Obtener backups manuales
        if (Storage::exists('backups/manuales')) {
            $archivos = Storage::files('backups/manuales');
            foreach ($archivos as $archivo) {
                if (pathinfo($archivo, PATHINFO_EXTENSION) === 'zip') {
                    $backups[] = [
                        'nombre' => basename($archivo),
                        'ruta' => $archivo,
                        'tamanio' => round(Storage::size($archivo) / 1024 / 1024, 2),
                        'fecha' => Carbon::createFromTimestamp(Storage::lastModified($archivo)),
                        'tipo' => 'manual'
                    ];
                }
            }
        }

        // Ordenar por fecha descendente
        usort($backups, function($a, $b) {
            return $b['fecha'] <=> $a['fecha'];
        });

        return $backups;
    }

    public function obtenerEstadisticas()
    {
        $backups = $this->obtenerBackups();
        $logs = BackupLog::completados()->get();

        return [
            'total_backups' => count($backups),
            'tamanio_total' => array_sum(array_column($backups, 'tamanio')),
            'backups_30_dias' => BackupLog::where('created_at', '>=', now()->subDays(30))->count(),
            'tasa_exito' => $logs->count() > 0 ? 
                ($logs->where('estado', 'completado')->count() / $logs->count()) * 100 : 0
        ];
    }

    public function descargarBackup($archivo)
    {
        $ruta = 'backups/manuales/' . $archivo;

        if (!Storage::exists($ruta)) {
            throw new Exception("Archivo de backup no encontrado.");
        }

        return Storage::download($ruta);
    }

    public function eliminarBackup($archivo)
    {
        $ruta = 'backups/manuales/' . $archivo;

        if (!Storage::exists($ruta)) {
            throw new Exception("Archivo de backup no encontrado.");
        }

        Storage::delete($ruta);

        // Registrar en logs
        BackupLog::create([
            'tipo' => 'completado',
            'estado' => 'completado',
            'observaciones' => "Backup eliminado: {$archivo}",
            'iniciado_en' => now(),
            'completado_en' => now()
        ]);
    }

    private function backupBaseDatos($rutaDestino)
    {
        // Backup de la base de datos
        $databaseName = config('database.connections.pgsql.database');
        $databaseUser = config('database.connections.pgsql.username');
        $databaseHost = config('database.connections.pgsql.host');
        
        $comando = "PGPASSWORD=" . config('database.connections.pgsql.password') .
                  " pg_dump -h {$databaseHost} -U {$databaseUser} -d {$databaseName} -F c -b -v -f " .
                  storage_path("app/{$rutaDestino}");

        exec($comando, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new Exception("Error en backup de base de datos: " . implode("\n", $output));
        }
    }

    private function backupArchivos($rutaDestino)
    {
        // Backup de archivos importantes
        $archivosImportantes = [
            'storage/app/public/trabajos',
            'storage/app/public/comprobantes',
            '.env'
        ];

        // Crear archivo ZIP con los archivos importantes
        $zip = new \ZipArchive();
        if ($zip->open(storage_path("app/{$rutaDestino}"), \ZipArchive::CREATE) === TRUE) {
            foreach ($archivosImportantes as $archivo) {
                if (file_exists(base_path($archivo))) {
                    if (is_dir(base_path($archivo))) {
                        $this->agregarDirectorioAZip($zip, base_path($archivo), $archivo);
                    } else {
                        $zip->addFile(base_path($archivo), $archivo);
                    }
                }
            }
            $zip->close();
        } else {
            throw new Exception("No se pudo crear el archivo ZIP");
        }
    }

    private function backupCompleto($rutaDestino)
    {
        // Implementar backup completo (base de datos + archivos)
        $this->backupBaseDatos($rutaDestino . '.sql');
        $this->backupArchivos($rutaDestino . '_files.zip');
        
        // Combinar ambos en un solo ZIP (simplificado)
        // En producción se necesitaría una implementación más robusta
    }

    private function agregarDirectorioAZip($zip, $directorio, $rutaZip)
    {
        $archivos = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directorio),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($archivos as $nombre => $archivo) {
            if (!$archivo->isDir()) {
                $rutaArchivo = $archivo->getRealPath();
                $rutaRelativa = $rutaZip . '/' . substr($rutaArchivo, strlen($directorio) + 1);
                
                $zip->addFile($rutaArchivo, $rutaRelativa);
            }
        }
    }

    public function limpiarBackupsAntiguos()
    {
        $diasRetencion = config('backup.retencion_dias', 30);
        $fechaLimite = now()->subDays($diasRetencion);

        $backups = $this->obtenerBackups();
        $eliminados = 0;

        foreach ($backups as $backup) {
            if ($backup['fecha']->lt($fechaLimite)) {
                try {
                    $this->eliminarBackup($backup['nombre']);
                    $eliminados++;
                } catch (Exception $e) {
                    // Log del error pero continuar
                    \Log::error("Error eliminando backup antiguo: " . $e->getMessage());
                }
            }
        }

        return $eliminados;
    }
}