<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear tabla app_config si no existe
        if (!Schema::hasTable('app_config')) {
            Schema::create('app_config', function (Blueprint $table) {
                $table->id();
                $table->string('clave')->unique();
                $table->text('valor');
                $table->text('descripcion')->nullable();
                $table->timestamp('actualizado_en')->useCurrent();
                
                $table->index('clave');
            });
        }

        // Insertar configuraciones por defecto
        DB::table('app_config')->insertOrIgnore([
            [
                'clave' => 'api_url_local',
                'valor' => 'http://localhost:8000',
                'descripcion' => 'URL de la API en red local - Se actualiza automáticamente',
                'actualizado_en' => now()
            ],
            [
                'clave' => 'api_url_tunnel',
                'valor' => '',
                'descripcion' => 'URL del túnel Cloudflare (opcional)',
                'actualizado_en' => now()
            ],
            [
                'clave' => 'api_modo',
                'valor' => 'auto',
                'descripcion' => 'Modo de conexión: auto, local, tunnel',
                'actualizado_en' => now()
            ],
            [
                'clave' => 'api_activa',
                'valor' => 'true',
                'descripcion' => 'API habilitada para conexiones',
                'actualizado_en' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_config');
    }
};
