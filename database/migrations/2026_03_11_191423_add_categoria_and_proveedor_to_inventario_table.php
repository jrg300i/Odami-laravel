<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventario', function (Blueprint $table) {
            // Agregar columna categoria_id
            $table->foreignId('categoria_id')
                ->nullable()
                ->after('categoria')
                ->constrained('categorias')
                ->nullOnDelete();
            
            // Agregar columna proveedor_id
            $table->foreignId('proveedor_id')
                ->nullable()
                ->after('proveedor')
                ->constrained('proveedors')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn(['categoria_id', 'proveedor_id']);
        });
    }
};
