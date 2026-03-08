<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Usuarios
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nombre');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('rol')->default('vendedor');
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('ultimo_acceso')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('usuarios')->onDelete('set null');
        });

        // Clientes
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('email');
            $table->text('direccion');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->foreignId('creado_por')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->foreignId('modificado_por')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->timestamp('fecha_modificacion')->useCurrent();
        });

        // Trabajos
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->string('tipo_trabajo');
            $table->text('descripcion');
            $table->string('estado')->default('pendiente');
            $table->decimal('precio_estimado', 10, 2)->default(0);
            $table->decimal('precio_final', 10, 2)->nullable();
            $table->decimal('anticipo', 10, 2)->default(0);
            $table->timestamp('fecha_ingreso')->useCurrent();
            $table->timestamp('fecha_entrega')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->text('notas')->nullable();
            $table->foreignId('creado_por')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->foreignId('modificado_por')->nullable()->constrained('usuarios')->onDelete('set null');
        });

        // Fotos de trabajo
        Schema::create('fotos_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->constrained('trabajos')->onDelete('cascade');
            $table->text('foto_url');
            $table->text('foto_base64')->nullable();
            $table->string('tipo')->default('recepcion');
            $table->timestamp('fecha_subida')->useCurrent();
            $table->text('descripcion')->nullable();
            $table->foreignId('subido_por')->nullable()->constrained('usuarios')->onDelete('set null');
        });

        // Facturas
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->nullable()->constrained('trabajos')->onDelete('set null');
            $table->string('numero_factura')->unique();
            $table->timestamp('fecha_emision')->useCurrent();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('estado_pago')->default('pendiente');
            $table->string('metodo_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('emitida_por')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->timestamp('fecha_pago')->nullable();
        });

        // Inventario
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('categoria');
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->integer('stock_maximo')->nullable();
            $table->string('unidad')->default('unidad');
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->string('proveedor')->nullable();
            $table->string('contacto_proveedor')->nullable();
            $table->string('ubicacion')->nullable();
            $table->timestamp('fecha_actualizacion')->useCurrent();
            $table->foreignId('creado_por')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->foreignId('modificado_por')->nullable()->constrained('usuarios')->onDelete('set null');
        });

        // Movimientos de inventario
        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventario')->onDelete('cascade');
            $table->string('tipo_movimiento');
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->text('motivo');
            $table->foreignId('trabajo_id')->nullable()->constrained('trabajos')->onDelete('set null');
            $table->timestamp('fecha_movimiento')->useCurrent();
            $table->foreignId('realizado_por')->nullable()->constrained('usuarios')->onDelete('set null');
        });

        // Entregas
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->constrained('trabajos')->onDelete('cascade');
            $table->timestamp('fecha_entrega');
            $table->string('estado')->default('programada');
            $table->text('notas')->nullable();
            $table->boolean('recordatorio_enviado')->default(false);
            $table->timestamp('fecha_recordatorio')->nullable();
            $table->foreignId('creado_por')->nullable()->constrained('usuarios')->onDelete('set null');
        });

        // Configuración
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor');
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_actualizacion')->useCurrent();
            $table->foreignId('actualizado_por')->nullable()->constrained('usuarios')->onDelete('set null');
        });

        // Personal Access Tokens (Sanctum)
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Auditoría
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id();
            $table->string('tabla_afectada');
            $table->integer('registro_id')->nullable();
            $table->string('accion');
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->timestamp('fecha')->useCurrent();
            $table->string('ip_origen')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('configuracion');
        Schema::dropIfExists('entregas');
        Schema::dropIfExists('inventario_movimientos');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('facturas');
        Schema::dropIfExists('fotos_trabajo');
        Schema::dropIfExists('trabajos');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('usuarios');
    }
};
