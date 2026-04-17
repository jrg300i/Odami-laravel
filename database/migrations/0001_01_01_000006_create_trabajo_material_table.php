<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trabajo_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materiales')->onDelete('cascade');
            $table->decimal('cantidad', 8, 2);
            $table->string('unidad_medida')->default('metros');
            $table->decimal('costo_total', 10, 2);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trabajo_material');
    }
};