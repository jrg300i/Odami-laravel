<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('control_facturas', function (Blueprint $table) {
            $table->id();
            $table->string('serie'); // A, B, C
            $table->integer('ultimo_numero')->default(0);
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('numero_inicio')->default(1);
            $table->timestamps();
            
            $table->unique('serie');
        });
    }

    public function down()
    {
        Schema::dropIfExists('control_facturas');
    }
};