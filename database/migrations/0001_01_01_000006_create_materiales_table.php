<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo'); // tela, cuero, espuma, etc.
            $table->string('color')->nullable();
            $table->string('codigo_referencia')->nullable();
            $table->decimal('precio_metro', 10, 2)->default(0);
            $table->decimal('precio_unidad', 10, 2)->default(0);
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->string('proveedor')->nullable();
            $table->text('caracteristicas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('tipo');
            $table->index('nombre');
        });
    }

    public function down()
    {
        Schema::dropIfExists('materiales');
    }
};