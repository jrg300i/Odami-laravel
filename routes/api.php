<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check (mejorado con configuración)
Route::get('/health', function () {
    $config = [];
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('app_config')) {
            $configRows = \Illuminate\Support\Facades\DB::table('app_config')
                ->select('clave', 'valor')
                ->get()
                ->pluck('valor', 'clave')
                ->toArray();
            $config = $configRows;
        }
    } catch (\Exception $e) {
        // Ignorar errores si la tabla no existe
    }

    return response()->json([
        'status' => 'ok',
        'database' => 'connected',
        'timestamp' => now()->toIso8601String(),
        'config' => $config
    ]);
});

// API Config - Configuración pública (sin autenticación)
Route::get('/api-config', [App\Http\Controllers\Api\AppConfigController::class, 'config']);
Route::post('/api-config', [App\Http\Controllers\Api\AppConfigController::class, 'updateConfig']);

// Public routes
Route::post('/api/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/api/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/api/auth/me', [App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::get('/api/usuarios', [App\Http\Controllers\Api\AuthController::class, 'index']);

    // Dashboard
    Route::get('/api/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
    Route::get('/api/dashboard/trabajos-recientes', [App\Http\Controllers\Api\DashboardController::class, 'trabajosRecientes']);
    Route::get('/api/dashboard/entregas-hoy', [App\Http\Controllers\Api\DashboardController::class, 'entregasHoy']);
    Route::get('/api/dashboard/stock-critico', [App\Http\Controllers\Api\DashboardController::class, 'stockCritico']);

    // Clientes
    Route::get('/api/clientes', [App\Http\Controllers\Api\ClienteController::class, 'index']);
    Route::get('/api/clientes/search', [App\Http\Controllers\Api\ClienteController::class, 'search']);
    Route::get('/api/clientes/{id}', [App\Http\Controllers\Api\ClienteController::class, 'show']);
    Route::post('/api/clientes', [App\Http\Controllers\Api\ClienteController::class, 'store']);
    Route::put('/api/clientes/{id}', [App\Http\Controllers\Api\ClienteController::class, 'update']);
    Route::delete('/api/clientes/{id}', [App\Http\Controllers\Api\ClienteController::class, 'destroy']);

    // Trabajos
    Route::get('/api/trabajos', [App\Http\Controllers\Api\TrabajoController::class, 'index']);
    Route::get('/api/trabajos/estado/{estado}', [App\Http\Controllers\Api\TrabajoController::class, 'porEstado']);
    Route::get('/api/trabajos/{id}', [App\Http\Controllers\Api\TrabajoController::class, 'show']);
    Route::post('/api/trabajos', [App\Http\Controllers\Api\TrabajoController::class, 'store']);
    Route::put('/api/trabajos/{id}', [App\Http\Controllers\Api\TrabajoController::class, 'update']);
    Route::delete('/api/trabajos/{id}', [App\Http\Controllers\Api\TrabajoController::class, 'destroy']);

    // Facturas
    Route::get('/api/facturas', [App\Http\Controllers\Api\FacturaController::class, 'index']);
    Route::get('/api/facturas/pendientes', [App\Http\Controllers\Api\FacturaController::class, 'pendientes']);
    Route::get('/api/facturas/trabajo/{trabajoId}', [App\Http\Controllers\Api\FacturaController::class, 'porTrabajo']);
    Route::get('/api/facturas/{id}', [App\Http\Controllers\Api\FacturaController::class, 'show']);
    Route::post('/api/facturas', [App\Http\Controllers\Api\FacturaController::class, 'store']);
    Route::put('/api/facturas/{id}', [App\Http\Controllers\Api\FacturaController::class, 'update']);
    Route::delete('/api/facturas/{id}', [App\Http\Controllers\Api\FacturaController::class, 'destroy']);

    // Inventario
    Route::get('/api/inventario', [App\Http\Controllers\Api\InventarioController::class, 'index']);
    Route::get('/api/inventario/categoria/{categoria}', [App\Http\Controllers\Api\InventarioController::class, 'porCategoria']);
    Route::get('/api/inventario/stock-bajo', [App\Http\Controllers\Api\InventarioController::class, 'stockBajo']);
    Route::get('/api/inventario/{id}', [App\Http\Controllers\Api\InventarioController::class, 'show']);
    Route::get('/api/inventario/{id}/movimientos', [App\Http\Controllers\Api\InventarioController::class, 'movimientos']);
    Route::post('/api/inventario', [App\Http\Controllers\Api\InventarioController::class, 'store']);
    Route::put('/api/inventario/{id}', [App\Http\Controllers\Api\InventarioController::class, 'update']);
    Route::delete('/api/inventario/{id}', [App\Http\Controllers\Api\InventarioController::class, 'destroy']);
    Route::post('/api/inventario/movimientos', [App\Http\Controllers\Api\InventarioController::class, 'registrarMovimiento']);

    // Entregas
    Route::get('/api/entregas', [App\Http\Controllers\Api\EntregaController::class, 'index']);
    Route::get('/api/entregas/hoy', [App\Http\Controllers\Api\EntregaController::class, 'hoy']);
    Route::get('/api/entregas/proximas', [App\Http\Controllers\Api\EntregaController::class, 'proximas']);
    Route::get('/api/entregas/{id}', [App\Http\Controllers\Api\EntregaController::class, 'show']);
    Route::post('/api/entregas', [App\Http\Controllers\Api\EntregaController::class, 'store']);
    Route::put('/api/entregas/{id}', [App\Http\Controllers\Api\EntregaController::class, 'update']);
    Route::delete('/api/entregas/{id}', [App\Http\Controllers\Api\EntregaController::class, 'destroy']);

    // Configuración
    Route::get('/api/configuracion', [App\Http\Controllers\Api\ConfiguracionController::class, 'index']);
    Route::get('/api/configuracion/{clave}', [App\Http\Controllers\Api\ConfiguracionController::class, 'show']);
    Route::put('/api/configuracion/{clave}', [App\Http\Controllers\Api\ConfiguracionController::class, 'update']);
    Route::put('/api/configuracion', [App\Http\Controllers\Api\ConfiguracionController::class, 'bulkUpdate']);
});
