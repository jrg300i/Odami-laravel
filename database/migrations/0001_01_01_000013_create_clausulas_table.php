<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clausulas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('contenido');
            $table->integer('orden')->default(0);
            $table->boolean('activa')->default(true);
            $table->boolean('obligatoria')->default(false);
            $table->string('tipo')->default('general');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clausulas');
    }
};