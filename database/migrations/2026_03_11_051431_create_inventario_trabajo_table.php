<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla intermedia para relacionar trabajos con items del inventario.
     * Permite que un trabajo use múltiples materiales (cuero, hilo, goma, etc.)
     */
    public function up(): void
    {
        Schema::create('inventario_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->constrained('trabajos')->onDelete('cascade');
            $table->foreignId('inventario_id')->constrained('inventario')->onDelete('cascade');
            $table->decimal('cantidad_usada', 10, 2)->default(0);
            $table->string('unidad_medida')->default('unidad');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index(['trabajo_id', 'inventario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_trabajo');
    }
};
