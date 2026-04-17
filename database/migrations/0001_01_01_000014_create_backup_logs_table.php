<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // automatico, manual, restauracion
            $table->string('archivo')->nullable();
            $table->decimal('tamanio', 10, 2)->nullable(); // en MB
            $table->string('ubicacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['completado', 'fallido', 'en_proceso']);
            $table->json('detalles')->nullable();
            $table->timestamp('iniciado_en');
            $table->timestamp('completado_en')->nullable();
            $table->timestamps();
            
            $table->index('tipo');
            $table->index('estado');
            $table->index('iniciado_en');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backup_logs');
    }
};