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
    Route::get('/clientes/search', [App\Http\Controllers\Api\ClienteController::class, 'search']);
    Route::get('/clientes/{id}', [App\Http\Controllers\Api\ClienteController::class, 'show']);
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
    Route::get('/facturas/{id}', [App\Http\Controllers\Api\FacturaController::class, 'show']);
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

    // Configuración
    Route::get('/configuracion', [App\Http\Controllers\Api\ConfiguracionController::class, 'index']);
    Route::get('/configuracion/{clave}', [App\Http\Controllers\Api\ConfiguracionController::class, 'show']);
    Route::put('/configuracion/{clave}', [App\Http\Controllers\Api\ConfiguracionController::class, 'update']);
    Route::put('/configuracion', [App\Http\Controllers\Api\ConfiguracionController::class, 'bulkUpdate']);
});
