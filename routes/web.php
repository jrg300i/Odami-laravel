<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

// Health check (público)
Route::get('/health', function () {
    $config = [];
    try {
        if (Schema::hasTable('app_config')) {
            $configRows = DB::table('app_config')
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
