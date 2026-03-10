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
            // Datos legales de la empresa
            $table->string('empresa_ruc')->nullable()->after('tipo');
            $table->string('empresa_razon_social')->nullable()->after('empresa_ruc');
            $table->string('empresa_direccion')->nullable()->after('empresa_razon_social');
            $table->string('empresa_telefono')->nullable()->after('empresa_direccion');
            $table->string('empresa_email')->nullable()->after('empresa_telefono');
            
            // Datos para validez legal
            $table->string('representante_nombre')->nullable()->after('empresa_email');
            $table->string('representante_dni')->nullable()->after('representante_nombre');
            $table->string('representante_cargo')->nullable()->after('representante_dni');
            
            // Espacio para firma y sello (texto base64 para imagen o path)
            $table->text('firma_base64')->nullable()->after('representante_cargo');
            $table->text('sello_base64')->nullable()->after('firma_base64');
            
            // Notas legales
            $table->text('notas_legales')->nullable()->after('sello_base64');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn([
                'empresa_ruc',
                'empresa_razon_social',
                'empresa_direccion',
                'empresa_telefono',
                'empresa_email',
                'representante_nombre',
                'representante_dni',
                'representante_cargo',
                'firma_base64',
                'sello_base64',
                'notas_legales',
            ]);
        });
    }
};
