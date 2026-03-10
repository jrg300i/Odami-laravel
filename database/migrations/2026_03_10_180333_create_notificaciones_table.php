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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // entrega_proxima, entrega_hoy, trabajo_creado, etc.
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('prioridad')->default('normal'); // low, normal, high, urgent
            $table->string('icono')->default('info');
            $table->json('datos_adicionales')->nullable(); // datos extra en JSON
            $table->foreignId('trabajo_id')->nullable()->constrained('trabajos')->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('cascade');
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_leida')->nullable();
            $table->timestamps();
            
            $table->index(['leida', 'prioridad']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
