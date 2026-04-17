<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained()->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->string('metodo_pago'); // efectivo, transferencia, tarjeta
            $table->string('referencia')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['pendiente', 'completado', 'fallido', 'reembolsado'])->default('pendiente');
            $table->string('comprobante_path')->nullable();
            $table->timestamps();
            
            $table->index('fecha_pago');
            $table->index('metodo_pago');
            $table->index('estado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
};