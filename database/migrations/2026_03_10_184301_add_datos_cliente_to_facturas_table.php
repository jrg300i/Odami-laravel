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
        Schema::table('facturas', function (Blueprint $table) {
            // Datos del cliente (se copian al momento de crear la factura)
            $table->string('cliente_nombre')->nullable()->after('trabajo_id');
            $table->string('cliente_apellido')->nullable()->after('cliente_nombre');
            $table->string('cliente_documento')->nullable()->after('cliente_apellido'); // DNI/RUC
            $table->string('cliente_direccion')->nullable()->after('cliente_documento');
            $table->string('cliente_telefono')->nullable()->after('cliente_direccion');
            $table->string('cliente_email')->nullable()->after('cliente_telefono');
            
            // Datos del trabajo (se copian al momento de crear la factura)
            $table->string('trabajo_tipo')->nullable()->after('cliente_email');
            $table->text('trabajo_descripcion')->nullable()->after('trabajo_tipo');
            $table->timestamp('trabajo_fecha_recibido')->nullable()->after('trabajo_descripcion');
            $table->timestamp('trabajo_fecha_entrega')->nullable()->after('trabajo_fecha_recibido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn([
                'cliente_nombre',
                'cliente_apellido',
                'cliente_documento',
                'cliente_direccion',
                'cliente_telefono',
                'cliente_email',
                'trabajo_tipo',
                'trabajo_descripcion',
                'trabajo_fecha_recibido',
                'trabajo_fecha_entrega',
            ]);
        });
    }
};
