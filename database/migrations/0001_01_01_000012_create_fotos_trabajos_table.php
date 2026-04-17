<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fotos_trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->constrained()->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('ruta_original');
            $table->string('ruta_miniatura')->nullable();
            $table->string('ruta_comprimida')->nullable();
            $table->enum('fase', ['antes', 'durante', 'despues']);
            $table->boolean('es_principal')->default(false);
            $table->integer('orden')->default(0);
            $table->decimal('tamanio_original', 10, 2)->default(0); // en KB
            $table->decimal('tamanio_comprimido', 10, 2)->default(0); // en KB
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('fase');
            $table->index('es_principal');
            $table->index('trabajo_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fotos_trabajos');
    }
};