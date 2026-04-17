<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FotoTrabajoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TrabajoController;
use Illuminate\Support\Facades\Route;

// Autenticación
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Ruta raíz - SIN NOMBRE para evitar conflictos
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Dashboard - ÚNICA ruta con nombre dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Trabajos
    Route::resource('trabajos', TrabajoController::class);
    Route::post('trabajos/{trabajo}/completar', [TrabajoController::class, 'completar'])->name('trabajos.completar');

    // Fotos de trabajos
    Route::prefix('trabajos/{trabajo}')->group(function () {
        Route::get('fotos', [FotoTrabajoController::class, 'index'])->name('trabajos.fotos.index');
        Route::get('fotos/create', [FotoTrabajoController::class, 'create'])->name('trabajos.fotos.create');
        Route::post('fotos', [FotoTrabajoController::class, 'store'])->name('trabajos.fotos.store');
        Route::get('fotos/galeria', [FotoTrabajoController::class, 'galeria'])->name('trabajos.fotos.galeria');
    });

    Route::prefix('fotos')->group(function () {
        Route::get('{foto}', [FotoTrabajoController::class, 'show'])->name('fotos.show');
        Route::delete('{foto}', [FotoTrabajoController::class, 'destroy'])->name('fotos.destroy');
        Route::post('{foto}/marcar-principal', [FotoTrabajoController::class, 'marcarPrincipal'])->name('fotos.marcar-principal');
    });

    // Facturas
    Route::resource('facturas', FacturaController::class);
    Route::post('facturas/{factura}/emitir', [FacturaController::class, 'emitir'])->name('facturas.emitir');
    Route::post('facturas/{factura}/cancelar', [FacturaController::class, 'cancelar'])->name('facturas.cancelar');
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
    Route::post('facturas/{factura}/enviar-email', [FacturaController::class, 'enviarEmail'])->name('facturas.enviar-email');

    // Pagos
    Route::resource('pagos', PagoController::class);
    Route::post('pagos/{pago}/marcar-completado', [PagoController::class, 'marcarCompletado'])->name('pagos.marcar-completado');
    Route::get('pagos/{pago}/descargar-comprobante', [PagoController::class, 'descargarComprobante'])->name('pagos.descargar-comprobante');

    // Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('facturacion', [ReporteController::class, 'facturacion'])->name('reportes.facturacion');
        Route::get('trabajos', [ReporteController::class, 'trabajos'])->name('reportes.trabajos');
        Route::get('pagos', [ReporteController::class, 'pagos'])->name('reportes.pagos');
        Route::get('clientes', [ReporteController::class, 'clientes'])->name('reportes.clientes');
        Route::get('exportar-facturacion', [ReporteController::class, 'exportarFacturacion'])->name('reportes.exportar-facturacion');
        Route::get('exportar-trabajos', [ReporteController::class, 'exportarTrabajos'])->name('reportes.exportar-trabajos');
    });

    // Backups
    Route::prefix('backups')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('backups.index');
        Route::post('crear', [BackupController::class, 'crearBackup'])->name('backups.crear');
        Route::post('restaurar', [BackupController::class, 'restaurarBackup'])->name('backups.restaurar');
        Route::get('descargar/{archivo}', [BackupController::class, 'descargarBackup'])->name('backups.descargar');
        Route::delete('eliminar', [BackupController::class, 'eliminarBackup'])->name('backups.eliminar');
        Route::get('logs', [BackupController::class, 'logs'])->name('backups.logs');
        Route::match(['get', 'post'], 'configuracion', [BackupController::class, 'configuracion'])->name('backups.configuracion');
    });

    // Rutas de administración (solo admin)
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('estadisticas-avanzadas', [DashboardController::class, 'estadisticasAvanzadas'])->name('admin.estadisticas');
    });

    // Agrega esta ruta
    Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Otras rutas de admin...
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});