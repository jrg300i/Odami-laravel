<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('trabajo_id')->nullable()->constrained()->onDelete('set null');
            $table->string('serie'); // A, B, C
            $table->integer('numero');
            $table->string('numero_completo')->unique();
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('iva', 10, 2)->default(21);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['borrador', 'emitida', 'pagada', 'cancelada', 'vencida'])->default('borrador');
            $table->text('concepto');
            $table->text('observaciones')->nullable();
            $table->json('lineas')->nullable(); // Array de líneas de factura
            $table->string('forma_pago')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->boolean('incluir_clausulas')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['serie', 'numero']);
            $table->index('numero_completo');
            $table->index('estado');
            $table->index('fecha_emision');
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas');
    }
};