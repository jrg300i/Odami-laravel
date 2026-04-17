<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index()
    {
        $backups = $this->backupService->obtenerBackups();
        $estadisticas = $this->backupService->obtenerEstadisticas();
        $configuracion = [
            'automatico' => config('backup.automatico', false),
            'frecuencia' => config('backup.frecuencia', 'daily'),
            'retencion' => config('backup.retencion_dias', 30),
        ];

        return view('backups.index', compact('backups', 'estadisticas', 'configuracion'));
    }

    public function crearBackup(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:completo,base_datos,archivos',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            $backupLog = $this->backupService->crearBackup(
                $request->tipo,
                $request->observaciones,
                auth()->user()->name
            );

            return redirect()->route('backups.logs')
                            ->with('success', 'Backup creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al crear backup: ' . $e->getMessage());
        }
    }

    public function restaurarBackup(Request $request)
    {
        $request->validate([
            'archivo' => 'required|string',
            'confirmar' => 'required|accepted'
        ]);

        try {
            $backupLog = $this->backupService->restaurarBackup(
                $request->archivo,
                auth()->user()->name
            );

            return redirect()->route('backups.logs')
                            ->with('success', 'Backup restaurado exitosamente. El sistema se reiniciará.');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al restaurar backup: ' . $e->getMessage());
        }
    }

    public function descargarBackup($archivo)
    {
        try {
            return $this->backupService->descargarBackup($archivo);
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al descargar backup: ' . $e->getMessage());
        }
    }

    public function eliminarBackup(Request $request)
    {
        $request->validate([
            'archivo' => 'required|string',
            'confirmar' => 'required|accepted'
        ]);

        try {
            $this->backupService->eliminarBackup($request->archivo);

            return redirect()->route('backups.index')
                            ->with('success', 'Backup eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al eliminar backup: ' . $e->getMessage());
        }
    }

    public function logs()
    {
        $logs = BackupLog::orderBy('created_at', 'desc')->paginate(20);
        $estadisticas = [
            'total' => BackupLog::count(),
            'completados' => BackupLog::completados()->count(),
            'fallidos' => BackupLog::fallidos()->count(),
            'tamanio_total' => BackupLog::completados()->sum('tamanio'),
        ];

        return view('backups.logs', compact('logs', 'estadisticas'));
    }

    public function configuracion(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'backup_automatico' => 'boolean',
                'frecuencia_backup' => 'required|in:daily,weekly,monthly',
                'dias_retencion' => 'required|integer|min:1|max:365'
            ]);

            // Actualizar configuración
            config(['backup.automatico' => $request->boolean('backup_automatico')]);
            config(['backup.frecuencia' => $request->frecuencia_backup]);
            config(['backup.retencion_dias' => $request->dias_retencion]);

            return redirect()->route('backups.configuracion')
                            ->with('success', 'Configuración actualizada exitosamente.');
        }

        $configuracion = [
            'automatico' => config('backup.automatico', false),
            'frecuencia' => config('backup.frecuencia', 'daily'),
            'retencion' => config('backup.retencion_dias', 30),
        ];

        return view('backups.configuracion', compact('configuracion'));
    }
}