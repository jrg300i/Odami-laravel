<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('codigo_trabajo')->unique();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['silla', 'sofa', 'sillon', 'cabecero', 'butaca', 'personalizado']);
            $table->enum('estado', ['presupuesto', 'en_proceso', 'completado', 'entregado', 'cancelado'])->default('presupuesto');
            $table->decimal('costo_estimado', 10, 2)->default(0);
            $table->decimal('costo_final', 10, 2)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->date('fecha_fin_real')->nullable();
            $table->integer('prioridad')->default(1);
            $table->text('notas_internas')->nullable();
            $table->text('observaciones_cliente')->nullable();
            $table->boolean('urgente')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('codigo_trabajo');
            $table->index('estado');
            $table->index('tipo');
            $table->index('prioridad');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trabajos');
    }
};