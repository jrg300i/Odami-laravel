<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes con rate limiting estricto
Route::middleware('throttle:login')->group(function () {
    Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
});

// Protected routes
Route::middleware(['sanctum', 'throttle:api'])->group(function () {
    // Auth
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/auth/me', [App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::get('/usuarios', [App\Http\Controllers\Api\AuthController::class, 'index']);

    // Dashboard
    Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
    Route::get('/dashboard/trabajos-recientes', [App\Http\Controllers\Api\DashboardController::class, 'trabajosRecientes']);
    Route::get('/dashboard/entregas-hoy', [App\Http\Controllers\Api\DashboardController::class, 'entregasHoy']);
    Route::get('/dashboard/stock-critico', [App\Http\Controllers\Api\DashboardController::class, 'stockCritico']);

    // Clientes
    Route::get('/clientes', [App\Http\Controllers\Api\ClienteController::class, 'index']);
    Route::get('/clientes/search', [App\Http\Controllers\Api\ClienteController::class, 'index']);
    Route::get('/clientes/{id}', [App\Http\Controllers\Api\ClienteController::class, 'show']);
    Route::get('/clientes/{id}/trabajos', [App\Http\Controllers\Api\ClienteController::class, 'trabajos']);
    Route::get('/clientes/{id}/facturas', [App\Http\Controllers\Api\ClienteController::class, 'facturas']);
    Route::post('/clientes', [App\Http\Controllers\Api\ClienteController::class, 'store']);
    Route::put('/clientes/{id}', [App\Http\Controllers\Api\ClienteController::class, 'update']);
    Route::delete('/clientes/{id}', [App\Http\Controllers\Api\ClienteController::class, 'destroy']);

    // Trabajos
    Route::get('/trabajos', [App\Http\Controllers\Api\TrabajoController::class, 'index']);
    Route::get('/trabajos/estado/{estado}', [App\Http\Controllers\Api\TrabajoController::class, 'porEstado']);
    Route::get('/trabajos/{id}', [App\Http\Controllers\Api\TrabajoController::class, 'show']);
    Route::post('/trabajos', [App\Http\Controllers\Api\TrabajoController::class, 'store']);
    Route::put('/trabajos/{id}', [App\Http\Controllers\Api\TrabajoController::class, 'update']);
    Route::delete('/trabajos/{id}', [App\Http\Controllers\Api\TrabajoController::class, 'destroy']);

    // Facturas
    Route::get('/facturas', [App\Http\Controllers\Api\FacturaController::class, 'index']);
    Route::get('/facturas/pendientes', [App\Http\Controllers\Api\FacturaController::class, 'pendientes']);
    Route::get('/facturas/trabajo/{trabajoId}', [App\Http\Controllers\Api\FacturaController::class, 'porTrabajo']);
    Route::get('/facturas/cliente/{clienteId}', [App\Http\Controllers\Api\FacturaController::class, 'porCliente']);
    Route::get('/facturas/siguiente-numero', [App\Http\Controllers\Api\FacturaController::class, 'siguienteNumero']);
    Route::get('/facturas/{id}', [App\Http\Controllers\Api\FacturaController::class, 'show']);
    Route::get('/facturas/{id}/imprimir', [App\Http\Controllers\Api\FacturaPdfController::class, 'imprimir']);
    Route::get('/facturas/{id}/pdf', [App\Http\Controllers\Api\FacturaPdfController::class, 'generarPdf']);
    Route::post('/facturas', [App\Http\Controllers\Api\FacturaController::class, 'store']);
    Route::put('/facturas/{id}', [App\Http\Controllers\Api\FacturaController::class, 'update']);
    Route::delete('/facturas/{id}', [App\Http\Controllers\Api\FacturaController::class, 'destroy']);

    // Inventario
    Route::get('/inventario', [App\Http\Controllers\Api\InventarioController::class, 'index']);
    Route::get('/inventario/categoria/{categoria}', [App\Http\Controllers\Api\InventarioController::class, 'porCategoria']);
    Route::get('/inventario/stock-bajo', [App\Http\Controllers\Api\InventarioController::class, 'stockBajo']);
    Route::get('/inventario/{id}', [App\Http\Controllers\Api\InventarioController::class, 'show']);
    Route::get('/inventario/{id}/movimientos', [App\Http\Controllers\Api\InventarioController::class, 'movimientos']);
    Route::post('/inventario', [App\Http\Controllers\Api\InventarioController::class, 'store']);
    Route::put('/inventario/{id}', [App\Http\Controllers\Api\InventarioController::class, 'update']);
    Route::delete('/inventario/{id}', [App\Http\Controllers\Api\InventarioController::class, 'destroy']);
    Route::post('/inventario/movimientos', [App\Http\Controllers\Api\InventarioController::class, 'registrarMovimiento']);

    // Entregas
    Route::get('/entregas', [App\Http\Controllers\Api\EntregaController::class, 'index']);
    Route::get('/entregas/hoy', [App\Http\Controllers\Api\EntregaController::class, 'hoy']);
    Route::get('/entregas/proximas', [App\Http\Controllers\Api\EntregaController::class, 'proximas']);
    Route::get('/entregas/{id}', [App\Http\Controllers\Api\EntregaController::class, 'show']);
    Route::post('/entregas', [App\Http\Controllers\Api\EntregaController::class, 'store']);
    Route::put('/entregas/{id}', [App\Http\Controllers\Api\EntregaController::class, 'update']);
    Route::delete('/entregas/{id}', [App\Http\Controllers\Api\EntregaController::class, 'destroy']);

    // Fotos de Trabajos
    Route::get('/trabajos/{trabajoId}/fotos', [App\Http\Controllers\Api\FotoTrabajoController::class, 'index']);
    Route::get('/fotos/{id}', [App\Http\Controllers\Api\FotoTrabajoController::class, 'show']);
    Route::post('/fotos', [App\Http\Controllers\Api\FotoTrabajoController::class, 'store']); // Base64 (cámara)
    Route::post('/fotos/upload', [App\Http\Controllers\Api\FotoTrabajoController::class, 'upload']); // Archivo único
    Route::post('/fotos/upload-multiple', [App\Http\Controllers\Api\FotoTrabajoController::class, 'uploadMultiple']); // Múltiples archivos
    Route::delete('/fotos/{id}', [App\Http\Controllers\Api\FotoTrabajoController::class, 'destroy']);
    Route::get('/fotos/estadisticas', [App\Http\Controllers\Api\FotoTrabajoController::class, 'estadisticas']);

    // Notificaciones
    Route::get('/notificaciones', [App\Http\Controllers\Api\NotificacionController::class, 'index']);
    Route::get('/notificaciones/dashboard', [App\Http\Controllers\Api\NotificacionController::class, 'dashboard']);
    Route::get('/notificaciones/{id}', [App\Http\Controllers\Api\NotificacionController::class, 'show']);
    Route::post('/notificaciones', [App\Http\Controllers\Api\NotificacionController::class, 'store']);
    Route::post('/notificaciones/{id}/leida', [App\Http\Controllers\Api\NotificacionController::class, 'marcarLeida']);
    Route::post('/notificaciones/todas-leidas', [App\Http\Controllers\Api\NotificacionController::class, 'marcarTodasLeidas']);
    Route::delete('/notificaciones/{id}', [App\Http\Controllers\Api\NotificacionController::class, 'destroy']);

    // Condiciones de Trabajo
    Route::get('/condiciones', [App\Http\Controllers\Api\CondicionController::class, 'index']);
    Route::get('/condiciones/all', [App\Http\Controllers\Api\CondicionController::class, 'all']);
    Route::post('/condiciones', [App\Http\Controllers\Api\CondicionController::class, 'store']);
    Route::put('/condiciones/{id}', [App\Http\Controllers\Api\CondicionController::class, 'update']);
    Route::delete('/condiciones/{id}', [App\Http\Controllers\Api\CondicionController::class, 'destroy']);

    // Configuración
    Route::get('/configuracion', [App\Http\Controllers\Api\ConfiguracionController::class, 'index']);
    Route::get('/configuracion/{clave}', [App\Http\Controllers\Api\ConfiguracionController::class, 'show']);
    Route::put('/configuracion/{clave}', [App\Http\Controllers\Api\ConfiguracionController::class, 'update']);
    Route::put('/configuracion', [App\Http\Controllers\Api\ConfiguracionController::class, 'bulkUpdate']);
});
