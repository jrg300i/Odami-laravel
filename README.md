# Sistema de Gestión - Tapicería Odami

Sistema completo de gestión para tapicería desarrollado en Laravel 10 con PostgreSQL.

## Estructura del Proyecto

```
tapiceria-odami/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   │   ├── BackupLog.php
│   │   ├── Clausula.php
│   │   ├── Cliente.php
│   │   ├── Configuracion.php
│   │   ├── ControlFactura.php
│   │   ├── Factura.php
│   │   ├── FotoTrabajo.php
│   │   ├── Material.php
│   │   ├── Pago.php
│   │   ├── Role.php
│   │   ├── Trabajo.php
│   │   └── User.php
│   ├── Providers/
│   └── Services/
│       ├── BackupService.php
│       ├── FotoService.php
│       └── ReporteService.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── backup.php
│   ├── cache.php
│   ├── compresion.php
│   ├── database.php
│   ├── facturacion.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── roles.php
│   ├── services.php
│   └── session.php
├── database/
│   └── migrations/
├── resources/
├── routes/
├── storage/
└── tests/
```

## Módulos Implementados

### Base de Datos

**Migraciones:**
- users, jobs, cache, personal_access_tokens
- roles, role_user
- clientes, trabajos, materiales, trabajo_material
- facturas, control_facturas, pagos
- fotos_trabajos, clausulas, backup_logs, configuraciones

### Modelos (Eloquent)
- User, Role
- Cliente, Trabajo, Material
- Factura, ControlFactura, Pago
- FotoTrabajo, Clausula, Configuracion, BackupLog

### Servicios
- **BackupService**: Backups automáticos y manuales de base de datos y archivos
- **FotoService**: Compresión, generación de miniaturas y gestión de imágenes
- **ReporteService**: Generación de reportes y exportación a Excel

## Configuración

### Facturación (config/facturacion.php)
- Series A, B, C configurables
- IVA: 21% general, 10% reducido, 4% superreducido
- Vencimiento predeterminado: 30 días
- Numeración automática con formato SERIE-NUMERO

### Roles (config/roles.php)
- **Administrador**: Acceso completo al sistema
- **Tapicero**: Trabajos, fotos, ver materiales y facturas
- **Ventas**: Clientes, facturas, pagos y reportes
- **Cliente**: Acceso limitado a sus propios datos

### Backup (config/backup.php)
- Backups automáticos programados (configurable)
- Retención: 30 días por defecto
- Incluye base de datos (pg_dump) y archivos
- Carpetas: trabajos y comprobantes

### Compresión de Imágenes (config/compresion.php)
- Miniaturas: 300px máx, calidad 80%
- Comprimidas: 1200px máx, calidad 75%
- Formato de salida: JPG
- Limpieza automática de temporales

## Características

### Gestión de Clientes
- Directorio completo con búsqueda y filtros
- Historial de trabajos y facturas
- Gestión de contactos y direcciones

### Gestión de Trabajos
- Múltiples tipos (sillas, sofás, sillones, cabeceros, butacas, personalizados)
- Control de prioridades y estados
- Asignación de materiales y costos
- Seguimiento fotográfico (antes, durante, después)

### Sistema de Facturación
- Facturación con series A, B, C
- Estados: borrador, emitida, pagada, cancelada
- Control de vencimientos y pagos
- Numeración correlativa automática

### Gestión de Pagos
- Múltiples métodos de pago
- Comprobantes digitales
- Control de saldos pendientes

### Sistema de Fotos
- Subida múltiple
- Compresión automática
- Miniaturas automáticas
- Organización por fases del trabajo

### Backups
- Automáticos y manuales
- Restauración de datos
- Logs detallados
- Limpieza automática de backups antiguos

### Reportes
- Reporte de facturación
- Reporte de trabajos
- Reporte de pagos
- Reporte de clientes
- Exportación a Excel

## Requisitos del Sistema

- PHP 8.1 o superior
- PostgreSQL 12 o superior
- Composer
- Node.js (para assets)
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, PDO_PGSQL, Tokenizer, XML, GD

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tapiceria-odami/sistema-gestion.git
   cd sistema-gestion
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

3. **Configurar entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar base de datos en .env**
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=tapiceria_odami
   DB_USERNAME=usuario
   DB_PASSWORD=contraseña
   ```

5. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Crear enlace simbólico para almacenamiento**
   ```bash
   php artisan storage:link
   ```

7. **Compilar assets**
   ```bash
   npm run dev
   ```

8. **Iniciar servidor**
   ```bash
   php artisan serve
   ```

## Comandos de Consola

```bash
php artisan backup:run           # Ejecutar backup manual
php artisan backup:list          # Listar backups disponibles
php artisan backup:restore {archivo} # Restaurar backup
php artisan fotos:comprimir      # Comprimir fotos pendientes
php artisan fotos:limpiar        # Limpiar fotos temporales
```

## Información del Sistema

- **Aplicación**: Tapicería Odami
- **Framework**: Laravel 10
- **Base de datos**: PostgreSQL
- **Zona horaria**: Europe/Madrid
- **Idioma**: Español (es)
