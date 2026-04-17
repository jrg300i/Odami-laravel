<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Trabajo;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\BackupLog;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   */
  public function index()
  {
    $user = Auth::user();

    // Estadísticas principales
    $totalClientes = Cliente::count();
    $trabajosActivos = Trabajo::where('estado', '!=', 'completado')->count();
    $facturasPendientes = Factura::where('estado', 'pendiente')->count();

    // Ingresos del mes actual
    $ingresosMes = Pago::whereYear('created_at', now()->year)
      ->whereMonth('created_at', now()->month)
      ->sum('monto');

    // Trabajos recientes
    $trabajosRecientes = Trabajo::with('cliente')
      ->latest()
      ->take(5)
      ->get();

    // Facturas próximas a vencer
    $facturasProximasVencer = Factura::with('cliente')
      ->where('estado', 'pendiente')
      ->whereDate('fecha_vencimiento', '>=', now())
      ->whereDate('fecha_vencimiento', '<=', now()->addDays(7))
      ->orderBy('fecha_vencimiento', 'asc')
      ->take(5)
      ->get();

    // Información de respaldos
    try {
      $ultimoRespaldo = BackupLog::where('estado', 'completado')
        ->orderBy('created_at', 'desc')
        ->first();
    } catch (\Exception $e) {
      $ultimoRespaldo = null;
      \Log::warning('Error obteniendo último respaldo: ' . $e->getMessage());
    }

    // ✅ CORREGIDO: Espacio en disco sin dependency injection
    try {
      $espacioDisco = $this->verificarEspacioDisco();
    } catch (\Exception $e) {
      $espacioDisco = [
        'total' => 0,
        'usado' => 0,
        'libre' => 0,
        'porcentaje_usado' => 0
      ];
      \Log::warning('Error verificando espacio en disco: ' . $e->getMessage());
    }

    return view('dashboard', compact(
      'totalClientes',
      'trabajosActivos',
      'facturasPendientes',
      'ingresosMes',
      'trabajosRecientes',
      'facturasProximasVencer',
      'ultimoRespaldo',
      'espacioDisco',
      'user'
    ));
  }

  /**
   * Show the user profile.
   */
  public function profile()
  {
    $user = Auth::user();
    return view('auth.profile', compact('user'));
  }

  /**
   * Update user profile.
   */
  public function updateProfile(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
      'password' => 'nullable|string|min:8|confirmed',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->password) {
      $user->password = bcrypt($request->password);
    }

    $user->save();

    return redirect()->route('profile')
      ->with('success', 'Perfil actualizado correctamente.');
  }

  /**
   * Get quick actions for dashboard.
   */
  public function quickActions()
  {
    $actions = [
      [
        'title' => 'Nuevo Cliente',
        'route' => route('clientes.create'),
        'icon' => 'fas fa-user-plus',
        'color' => 'primary'
      ],
      [
        'title' => 'Nuevo Trabajo',
        'route' => route('trabajos.create'),
        'icon' => 'fas fa-couch',
        'color' => 'success'
      ],
      [
        'title' => 'Crear Factura',
        'route' => route('facturas.create'),
        'icon' => 'fas fa-file-invoice',
        'color' => 'info'
      ],
      [
        'title' => 'Registrar Pago',
        'route' => route('pagos.create'),
        'icon' => 'fas fa-money-bill-wave',
        'color' => 'warning'
      ],
    ];

    return response()->json($actions);
  }

  /**
   * Get system status.
   */
  public function systemStatus()
  {
    $status = [
      'database' => $this->checkDatabaseConnection(),
      'storage' => $this->checkStorageSpace(),
      'backups' => $this->checkBackupStatus(),
      'last_cron' => $this->getLastCronExecution(),
    ];

    return response()->json($status);
  }

  /**
   * Check database connection.
   */
  private function checkDatabaseConnection()
  {
    try {
      DB::connection()->getPdo();
      return [
        'status' => 'success',
        'message' => 'Conectado correctamente'
      ];
    } catch (\Exception $e) {
      return [
        'status' => 'error',
        'message' => 'Error de conexión: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Check storage space.
   */
  private function checkStorageSpace()
  {
    $freeSpace = disk_free_space(storage_path());
    $totalSpace = disk_total_space(storage_path());
    $usedPercentage = round((($totalSpace - $freeSpace) / $totalSpace) * 100, 2);

    $status = $usedPercentage > 90 ? 'error' : ($usedPercentage > 80 ? 'warning' : 'success');

    return [
      'status' => $status,
      'message' => "{$usedPercentage}% usado",
      'percentage' => $usedPercentage
    ];
  }

  /**
   * Check backup status - CORREGIDO para tu estructura
   */
  private function checkBackupStatus()
  {
    try {
      $lastBackup = BackupLog::completados()
        ->latest()
        ->first();

      if (!$lastBackup) {
        return [
          'status' => 'warning',
          'message' => 'No hay backups completados'
        ];
      }

      $daysAgo = $lastBackup->created_at->diffInDays(now());

      if ($daysAgo > 7) {
        return [
          'status' => 'warning',
          'message' => "Backup hace {$daysAgo} días"
        ];
      }

      return [
        'status' => 'success',
        'message' => 'Backups al día'
      ];
    } catch (\Exception $e) {
      return [
        'status' => 'error',
        'message' => 'Error verificando backups'
      ];
    }
  }

  /**
   * Get last cron execution.
   */
  private function getLastCronExecution()
  {
    // Implementar lógica para verificar última ejecución de cron
    // Esto depende de cómo estés manejando las tareas programadas

    return [
      'status' => 'info',
      'message' => 'Verificar configuración'
    ];
  }
}