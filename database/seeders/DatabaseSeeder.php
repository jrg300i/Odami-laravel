<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios
        DB::table('usuarios')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'nombre' => 'Administrador',
                'email' => 'admin@odami.com',
                'telefono' => '999999999',
                'rol' => 'admin',
                'activo' => true,
            ],
            [
                'username' => 'vendedor',
                'password' => Hash::make('admin123'),
                'nombre' => 'Vendedor',
                'email' => 'vendedor@odami.com',
                'telefono' => '999999998',
                'rol' => 'vendedor',
                'activo' => true,
            ],
            [
                'username' => 'taller',
                'password' => Hash::make('admin123'),
                'nombre' => 'Taller',
                'email' => 'taller@odami.com',
                'telefono' => '999999997',
                'rol' => 'taller',
                'activo' => true,
            ],
        ]);

        // Clientes
        DB::table('clientes')->insert([
            ['nombre' => 'Juan', 'apellido' => 'Pérez', 'telefono' => '987654321', 'email' => 'juan.perez@email.com', 'direccion' => 'Av. Principal 123'],
            ['nombre' => 'María', 'apellido' => 'González', 'telefono' => '987654322', 'email' => 'maria.gonzalez@email.com', 'direccion' => 'Calle Los Pinos 456'],
            ['nombre' => 'Carlos', 'apellido' => 'Rodríguez', 'telefono' => '987654323', 'email' => 'carlos.rodriguez@email.com', 'direccion' => 'Jr. Unión 789'],
            ['nombre' => 'Ana', 'apellido' => 'Martínez', 'telefono' => '987654324', 'email' => 'ana.martinez@email.com', 'direccion' => 'Av. Lima 321'],
            ['nombre' => 'Luis', 'apellido' => 'Sánchez', 'telefono' => '987654325', 'email' => 'luis.sanchez@email.com', 'direccion' => 'Calle Mercaderes 654'],
        ]);

        // Trabajos
        DB::table('trabajos')->insert([
            ['cliente_id' => 1, 'tipo_trabajo' => 'Tapizado de sofá', 'descripcion' => 'Sofá 3 cuerpos tela polar', 'estado' => 'en_proceso', 'precio_estimado' => 450.00, 'anticipo' => 150.00, 'notas' => 'Cliente prefiere tela oscura'],
            ['cliente_id' => 2, 'tipo_trabajo' => 'Tapizado de sillas', 'descripcion' => '6 sillas comedor', 'estado' => 'pendiente', 'precio_estimado' => 360.00, 'anticipo' => 100.00, 'notas' => 'Entregar en 1 semana'],
            ['cliente_id' => 3, 'tipo_trabajo' => 'Restauración mueble', 'descripcion' => 'Mueble antiguo restaurar', 'estado' => 'completado', 'precio_estimado' => 800.00, 'anticipo' => 400.00, 'notas' => 'Pieza delicada'],
            ['cliente_id' => 4, 'tipo_trabajo' => 'Cortinas a medida', 'descripcion' => '3 ventanas tela blackout', 'estado' => 'pendiente', 'precio_estimado' => 550.00, 'anticipo' => 200.00, 'notas' => 'Incluir instalación'],
            ['cliente_id' => 5, 'tipo_trabajo' => 'Tapizado cabeza cama', 'descripcion' => 'Cama matrimonial cuero', 'estado' => 'entregado', 'precio_estimado' => 350.00, 'anticipo' => 350.00, 'notas' => 'Pago completo'],
        ]);

        // Inventario
        DB::table('inventario')->insert([
            ['nombre' => 'Tela Polar Gris', 'categoria' => 'telas', 'stock_actual' => 50, 'stock_minimo' => 10, 'stock_maximo' => 100, 'unidad' => 'metro', 'precio_unitario' => 15.00, 'proveedor' => 'Proveedor A', 'ubicacion' => 'Almacén 1'],
            ['nombre' => 'Tela Terciopelo Azul', 'categoria' => 'telas', 'stock_actual' => 30, 'stock_minimo' => 5, 'stock_maximo' => 50, 'unidad' => 'metro', 'precio_unitario' => 25.00, 'proveedor' => 'Proveedor B', 'ubicacion' => 'Almacén 1'],
            ['nombre' => 'Espuma 2" alta densidad', 'categoria' => 'espumas', 'stock_actual' => 20, 'stock_minimo' => 5, 'stock_maximo' => 30, 'unidad' => 'pliego', 'precio_unitario' => 35.00, 'proveedor' => 'Proveedor C', 'ubicacion' => 'Almacén 2'],
            ['nombre' => 'Hilo poliéster negro', 'categoria' => 'hilos', 'stock_actual' => 100, 'stock_minimo' => 20, 'stock_maximo' => 200, 'unidad' => 'rollo', 'precio_unitario' => 5.00, 'proveedor' => 'Proveedor A', 'ubicacion' => 'Almacén 3'],
            ['nombre' => 'Botones decorativos', 'categoria' => 'botones', 'stock_actual' => 200, 'stock_minimo' => 50, 'stock_maximo' => 500, 'unidad' => 'unidad', 'precio_unitario' => 0.50, 'proveedor' => 'Proveedor D', 'ubicacion' => 'Almacén 3'],
            ['nombre' => 'Cremallera 50cm', 'categoria' => 'accesorios', 'stock_actual' => 80, 'stock_minimo' => 15, 'stock_maximo' => 150, 'unidad' => 'unidad', 'precio_unitario' => 2.00, 'proveedor' => 'Proveedor A', 'ubicacion' => 'Almacén 3'],
        ]);

        // Configuración
        DB::table('configuracion')->insert([
            ['clave' => 'nombre_negocio', 'valor' => 'Tapicería Odami', 'descripcion' => 'Nombre del negocio'],
            ['clave' => 'igv_porcentaje', 'valor' => '18', 'descripcion' => 'Porcentaje de IGV'],
            ['clave' => 'moneda', 'valor' => 'S/', 'descripcion' => 'Símbolo de moneda'],
            ['clave' => 'telefono', 'valor' => '', 'descripcion' => 'Teléfono del negocio'],
            ['clave' => 'email', 'valor' => '', 'descripcion' => 'Email del negocio'],
            ['clave' => 'direccion', 'valor' => '', 'descripcion' => 'Dirección del negocio'],
            ['clave' => 'mensaje_whatsapp', 'valor' => 'Hola! Gracias por contactar Tapicería Odami. ¿En qué podemos ayudarte?', 'descripcion' => 'Mensaje predeterminado para WhatsApp'],
        ]);
    }
}
