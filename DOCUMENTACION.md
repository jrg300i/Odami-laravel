# 🎨 Tapicería Odami Pro - Documentación Completa

**Sistema de Gestión Inteligente para Tapicerías - Laravel + Vue.js 3**

> **Última actualización**: 2026-03-12  
> **Estado**: ✅ Completamente funcional - Producción  
> **Versión Global**: `v2.0.0` - Dashboard Mejorado + Frontend Completo  
> **Framework**: Laravel 11+ + PHP 8.3+  
> **Base de Datos**: PostgreSQL 15+  
> **Frontend**: Vue.js 3 + TailwindCSS

---

## 📋 Control de Versiones

Este proyecto usa **versionamiento semántico (SemVer)** por módulo para mantener un orden estricto.

### Versión Actual por Módulo

| Módulo | Versión | Estado | Última Actualización |
|--------|---------|--------|---------------------|
| 📊 Dashboard | `v2.0.0` | ✅ Estable | 2026-03-12 |
| 👥 Clientes | `v1.4.0` | ✅ Estable | 2026-03-12 |
| 🛠️ Trabajos | `v2.1.0` | ✅ Estable | 2026-03-12 |
| 📸 Fotos | `v1.3.0` | ✅ Estable | 2026-03-12 |
| 📦 Inventario | `v1.4.0` | ✅ Estable | 2026-03-12 |
| 🏷️ Categorías | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 🚚 Proveedores | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 📄 Facturación | `v1.3.0` | ✅ Estable | 2026-03-12 |

📖 **Documentación completa**: [VERSIONES.md](VERSIONES.md)

---

## 📋 Tabla de Contenidos

1. [Inicio Rápido](#-inicio-rápido)
2. [Características del Sistema](#-características-del-sistema)
3. [Requisitos e Instalación](#-requisitos-e-instalación)
4. [Configuración](#-configuración)
5. [Uso y Comandos](#-uso-y-comandos)
6. [API Endpoints](#-api-endpoints)
7. [Módulo de Fotos](#-módulo-de-fotos)
8. [Seguridad y Roles](#-seguridad-y-roles)
9. [Acceso Global](#-acceso-global)
10. [Deploy en Render.com](#-deploy-en-rendercom)
11. [Mantenimiento](#-mantenimiento)
12. [Solución de Problemas](#-solución-de-problemas)
13. [Versionamiento](#-versionamiento)

---

## 🚀 Inicio Rápido

### Para usuarios en Termux/Android:

```bash
# Navegar al proyecto
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel

# Iniciar el servicio
./start.sh
```

El script mostrará las URLs de acceso:
- **URL Temporal**: `https://xxxx.trycloudflare.com`
- **URL Permanente**: `https://tu-usuario.ngrok.io` (si está configurado)

### Credenciales de Acceso

| Usuario | Password | Rol |
|---------|----------|-----|
| `admin` | `admin123` | Administrador |

---

## ✨ Características del Sistema

### Módulo de Dashboard
- ✅ Estadísticas en tiempo real
- ✅ Trabajos recientes
- ✅ Entregas del día
- ✅ Stock crítico de inventario
- ✅ Tarjetas clickables con acceso directo
- ✅ Buscador de trabajos (cliente, cédula, tipo, fecha)
- ✅ Diseño responsive

### Módulo de Clientes
- ✅ CRUD completo
- ✅ Búsqueda y filtrado
- ✅ Teléfono con enlace directo a WhatsApp
- ✅ Cédula/DNI en formulario y tarjetas
- ✅ Historial de trabajos por cliente
- ✅ Diseño responsive (tarjetas en móvil)

### Módulo de Trabajos
- ✅ CRUD completo de trabajos
- ✅ Estados: pendiente, en_proceso, completado, entregado, cancelado
- ✅ 📸 **Fotos por etapa**: recepción, proceso, final
- ✅ 📸 **Upload desde cámara** (base64)
- ✅ 📸 **Upload desde archivo** (almacenamiento interno)
- ✅ 📸 **Upload múltiple** de fotos
- ✅ Relación con materiales del inventario
- ✅ Fechas de entrega estimada y real

### Módulo de Inventario
- ✅ CRUD completo
- ✅ Movimientos (entrada, salida, ajuste)
- ✅ Alertas de stock bajo
- ✅ 🔐 **Eliminación solo para administradores**
- ✅ 👁️ Ver detalle con historial de movimientos
- ✅ 📱 **Diseño responsive** (tarjetas en móvil, tabla en escritorio)
- ✅ 🏷️ **11 categorías**: telas, cueros, espumas, hilos, gomas, botones, pegamentos, tintes, accesorios, insumos, otros
- ✅ 🔗 **Relación con trabajos**: Registro de materiales usados

### Módulo de Facturación
- ✅ Emisión de facturas
- ✅ Estados de pago (pendiente, pagado, parcial)
- ✅ Generación de PDF
- ✅ Impresión de facturas
- ✅ Reportes y estadísticas
- ✅ Numeración automática

### Módulo de Usuarios
- ✅ Gestión de usuarios
- ✅ Roles: admin, vendedor
- ✅ Estados: activo, inactivo
- ✅ Autenticación con Laravel Sanctum

---

## 🛠️ Requisitos e Instalación

### Requisitos del Sistema

| Componente | Versión Mínima | Comando de Instalación |
|------------|---------------|------------------------|
| **PHP** | 8.1+ | `pkg install php` |
| **PostgreSQL** | 13+ | `pkg install postgresql` |
| **Composer** | 2.0+ | `curl -sS https://getcomposer.org/installer \| php` |
| **Cloudflared** | Latest | `curl -L --output ~/.local/bin/cloudflared https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 && chmod +x ~/.local/bin/cloudflared` |
| **Node.js** (opcional) | 18+ | `pkg install nodejs` |
| **ngrok** (opcional) | Latest | `npm install -g ngrok` |

### Instalación Paso a Paso

#### 1. Clonar o navegar al proyecto

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel
```

#### 2. Instalar dependencias de PHP

```bash
composer install
```

#### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Configurar base de datos

Editar `.env` y configurar:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tapiceria_odami
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

#### 5. Iniciar PostgreSQL (si no está corriendo)

```bash
pg_ctl start
```

#### 6. Ejecutar migraciones

```bash
php artisan migrate
```

#### 7. Ejecutar seeders (datos de prueba)

```bash
php artisan db:seed
```

#### 8. Iniciar el servicio

```bash
./start.sh
```

---

## ⚙️ Configuración

### Variables de Entorno (.env)

```env
# Aplicación
APP_NAME="Tapicería Odami"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

# Logs
LOG_CHANNEL=stack
LOG_LEVEL=debug

# Base de Datos
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tapiceria_odami
DB_USERNAME=postgres
DB_PASSWORD=

# Sesión y Sanctum
SESSION_DRIVER=file
SESSION_LIFETIME=120
SANCTUM_STATEFUL_DOMAINS=localhost:8000,localhost:3000,127.0.0.1:8000

# Producción (Cloudflare/ngrok)
# APP_ENV=production
# APP_DEBUG=false
# APP_URL=https://tu-usuario.trycloudflare.com
# LOG_LEVEL=error
```

### Configuración de CORS

El sistema está configurado para aceptar peticiones desde:
- `localhost:8000`
- `localhost:3000`
- Dominios `*.trycloudflare.com`
- Dominios `*.ngrok.io`
- URL configurada en `APP_URL`

### Rate Limiting

| Endpoint | Límite | Propósito |
|----------|--------|-----------|
| `/api/auth/login` | 5/minuto | Prevenir fuerza bruta |
| `/api/*` (general) | 60/minuto | API general |
| Endpoints críticos | 30/minuto | Operaciones sensibles |

---

## 📖 Uso y Comandos

### Scripts Principales

#### Iniciar el servicio

```bash
./start.sh
```

Este script:
1. ✅ Inicia Laravel en puerto 8000
2. ✅ Inicia Cloudflare Tunnel (URL temporal)
3. ✅ Inicia ngrok (URL permanente, si está configurado)
4. ✅ Monitorea y auto-reinicia servicios
5. ✅ Detecta cambios de URL y actualiza automáticamente

#### Detener el servicio

```bash
./stop.sh
```

#### Verificar estado

```bash
# Ver procesos
ps aux | grep -E "php artisan|cloudflared|ngrok"

# Ver URLs guardadas
cat .urls

# Ver logs en tiempo real
tail -f logs/laravel.log
tail -f logs/cloudflare.log
tail -f logs/ngrok.log
```

### Comandos de Laravel Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan optimize
php artisan config:cache
php artisan route:cache

# Base de datos
php artisan migrate
php artisan migrate:status
php artisan db:seed

# Generar clave
php artisan key:generate

# Listar rutas
php artisan route:list

# Ejecutar tests
php artisan test
```

---

## 🔌 API Endpoints

### Autenticación

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `POST` | `/api/auth/login` | Iniciar sesión |
| `POST` | `/api/auth/logout` | Cerrar sesión |
| `GET` | `/api/auth/me` | Usuario actual |
| `GET` | `/api/usuarios` | Lista de usuarios |

### Dashboard

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/dashboard/stats` | Estadísticas generales |
| `GET` | `/api/dashboard/trabajos-recientes` | Últimos trabajos |
| `GET` | `/api/dashboard/entregas-hoy` | Entregas del día |
| `GET` | `/api/dashboard/stock-critico` | Stock bajo de inventario |

### Clientes

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/clientes` | Lista de clientes |
| `GET` | `/api/clientes/search` | Buscar clientes |
| `GET` | `/api/clientes/{id}` | Detalle de cliente |
| `GET` | `/api/clientes/{id}/trabajos` | Trabajos del cliente |
| `GET` | `/api/clientes/{id}/facturas` | Facturas del cliente |
| `POST` | `/api/clientes` | Crear cliente |
| `PUT` | `/api/clientes/{id}` | Actualizar cliente |
| `DELETE` | `/api/clientes/{id}` | Eliminar cliente |

### Trabajos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/trabajos` | Lista de trabajos |
| `GET` | `/api/trabajos/estado/{estado}` | Trabajos por estado |
| `GET` | `/api/trabajos/{id}` | Detalle de trabajo |
| `GET` | `/api/trabajos/{id}/materiales` | Materiales del trabajo |
| `POST` | `/api/trabajos` | Crear trabajo |
| `PUT` | `/api/trabajos/{id}` | Actualizar trabajo |
| `DELETE` | `/api/trabajos/{id}` | Eliminar trabajo |

### Fotos de Trabajos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/trabajos/{trabajoId}/fotos` | Fotos de un trabajo |
| `GET` | `/api/fotos/{id}` | Detalle de foto |
| `POST` | `/api/fotos` | Subir foto (base64 - cámara) |
| `POST` | `/api/fotos/upload` | Subir foto (archivo) |
| `POST` | `/api/fotos/upload-multiple` | Subir múltiples fotos |
| `DELETE` | `/api/fotos/{id}` | Eliminar foto |
| `GET` | `/api/fotos/estadisticas` | Estadísticas de fotos |

### Inventario

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/inventario` | Lista de items |
| `GET` | `/api/inventario/categoria/{categoria}` | Por categoría |
| `GET` | `/api/inventario/stock-bajo` | Stock bajo |
| `GET` | `/api/inventario/{id}` | Detalle de item |
| `GET` | `/api/inventario/{id}/movimientos` | Movimientos del item |
| `POST` | `/api/inventario` | Crear item (admin) |
| `PUT` | `/api/inventario/{id}` | Actualizar item |
| `DELETE` | `/api/inventario/{id}` | Eliminar item (solo admin) |
| `POST` | `/api/inventario/movimientos` | Registrar movimiento |

### Facturas

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/facturas` | Lista de facturas |
| `GET` | `/api/facturas/pendientes` | Facturas pendientes |
| `GET` | `/api/facturas/trabajo/{trabajoId}` | Facturas por trabajo |
| `GET` | `/api/facturas/cliente/{clienteId}` | Facturas por cliente |
| `GET` | `/api/facturas/siguiente-numero` | Siguiente número |
| `GET` | `/api/facturas/{id}` | Detalle de factura |
| `GET` | `/api/facturas/{id}/imprimir` | Imprimir factura |
| `GET` | `/api/facturas/{id}/pdf` | Generar PDF |
| `POST` | `/api/facturas` | Crear factura |
| `PUT` | `/api/facturas/{id}` | Actualizar factura |
| `DELETE` | `/api/facturas/{id}` | Eliminar factura |

---

## 📸 Módulo de Fotos

### Endpoints Principales

#### 1. Subir foto desde cámara (base64)

```javascript
const response = await fetch('/api/fotos', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    trabajo_id: 1,
    tipo: 'recepcion',
    foto_base64: 'data:image/jpeg;base64,/9j/4AAQSkZJRg...',
    descripcion: 'Estado inicial del sofá'
  })
});
```

#### 2. Subir foto desde archivo

```javascript
const file = document.getElementById('foto-input').files[0];
const formData = new FormData();

formData.append('trabajo_id', 1);
formData.append('tipo', 'recepcion');
formData.append('foto', file);
formData.append('descripcion', 'Foto desde galería');

const response = await fetch('/api/fotos/upload', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token },
  body: formData
});
```

#### 3. Subir múltiples fotos

```javascript
const files = Array.from(document.getElementById('fotos-input').files);
const formData = new FormData();

formData.append('trabajo_id', 1);
formData.append('tipo', 'proceso');
files.forEach(file => formData.append('fotos[]', file));
formData.append('descripcion', 'Progreso del trabajo');

const response = await fetch('/api/fotos/upload-multiple', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token },
  body: formData
});
```

### Etapas de Fotos

| Etapa | Icono | Color | Descripción |
|-------|-------|-------|-------------|
| **Recepción** | 📥 | Azul (#2196F3) | Estado inicial del artículo |
| **Proceso** | 🔨 | Naranja (#FF9800) | Durante el trabajo |
| **Final** | ✨ | Verde (#4CAF50) | Trabajo terminado |

### Validaciones

| Campo | Validación | Descripción |
|-------|-----------|-------------|
| `trabajo_id` | `required`, `exists:trabajos,id` | Debe existir el trabajo |
| `tipo` | `required`, `in:recepcion,proceso,final` | Solo tipos válidos |
| `foto_base64` | `required`, formato válido, máx 5MB | Imagen en base64 |
| `foto` (archivo) | `required`, `image`, `max:5120` | Archivo de imagen (5MB máx) |
| `descripcion` | `nullable`, `string`, `max:500` | Opcional, máx 500 chars |

---

## 🔐 Seguridad y Roles

### Roles del Sistema

| Rol | Ver | Crear | Editar | Eliminar |
|-----|-----|-------|--------|----------|
| **admin** | ✅ | ✅ | ✅ | ✅ |
| **vendedor** | ✅ | ✅ | ✅ | ❌ |

### Medidas de Seguridad Implementadas

#### 1. Variables de Entorno Seguras

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
LOG_LEVEL=error
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

#### 2. Autenticación con Laravel Sanctum

- ✅ Tokens encriptados
- ✅ Rate limiting por IP/usuario
- ✅ Middleware de autenticación en todas las rutas protegidas

#### 3. Rate Limiting

```php
// Login: 5 intentos por minuto
RateLimiter::for('login', fn($request) => Limit::perMinute(5)->by($request->ip()));

// API general: 60 peticiones por minuto
RateLimiter::for('api', fn($request) => Limit::perMinute(60)
    ->by($request->user()?->id ?: $request->ip()));
```

#### 4. CORS Configurado

```php
'allowed_origins' => [
    env('APP_URL', 'http://localhost:8000'),
    'https://*.trycloudflare.com',
    'https://*.ngrok.io',
],
'supported_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'X-XSRF-TOKEN'],
```

### Capas de Seguridad

```
┌─────────────────────────────────────────┐
│     Cloudflare Tunnel / ngrok           │
│  • HTTPS automático                     │
│  • Protección DDoS básica               │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│         Rate Limiting                   │
│  • Login: 5 intentos/min                │
│  • API: 60 peticiones/min               │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│       Autenticación Sanctum             │
│  • Tokens hash                          │
│  • Sesiones seguras                     │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│        Base de Datos PostgreSQL         │
│  • Conexión local                       │
│  • Credenciales protegidas              │
└─────────────────────────────────────────┘
```

---

## 🌍 Acceso Global

### URLs de Acceso

| Tipo | URL | Características |
|------|-----|----------------|
| **ngrok** | `https://tu-usuario.ngrok.io` | ✅ **Permanente** - Requiere authtoken |
| **Cloudflare** | `https://xxxx.trycloudflare.com` | ⚠️ Temporal - Cambia al reiniciar |
| **Local** | `http://TU_IP:8000` | ❌ Solo red local |

### Comparativa: ngrok vs Cloudflare

| Característica | ngrok | Cloudflare |
|---------------|--------|------------|
| URL permanente | ✅ Sí | ❌ No |
| Requiere registro | ✅ Email | ❌ No |
| HTTPS | ✅ Sí | ✅ Sí |
| Velocidad | Buena | Excelente |
| Estabilidad | Buena | Variable |

**Recomendación**: Usa ngrok como URL principal (permanente) y Cloudflare como respaldo.

### Configurar ngrok (opcional)

```bash
# Instalar ngrok
npm install -g ngrok

# Configurar authtoken (obtener en https://dashboard.ngrok.com)
ngrok config add-authtoken TU_TOKEN
```

### Comandos Útiles

```bash
# Iniciar
./start.sh

# Detener
./stop.sh

# Ver estado
ps aux | grep -E "php artisan|cloudflared|ngrok"

# Ver URLs
cat .urls

# Ver logs
tail -f logs/laravel.log

# Reiniciar manualmente
./stop.sh && ./start.sh
```

---

## 🚀 Deploy en Render.com

**Guía completa para desplegar tu API en Render.com de forma GRATUITA y tenerla online 24/7.**

> **Tiempo estimado**: 15 minutos
> **Costo**: Gratis (con opción a plan Starter de $7/mes)

---

### 📋 Requisitos Previos

- ✅ Git instalado en Termux
- ✅ Cuenta en GitHub
- ✅ Cuenta en Render.com
- ✅ Proyecto subido a GitHub

---

### 📝 Paso a Paso

#### Paso 1: Verificar Git Instalado

En Termux, ejecuta:

```bash
git --version
```

Si muestra una versión, continúa. Si dice "command not found":

```bash
pkg install git
```

#### Paso 2: Crear Cuenta en GitHub

1. Ve a https://github.com
2. Click en "Sign up"
3. Regístrate (es gratis)
4. Confirma tu email

#### Paso 3: Subir tu Proyecto a GitHub

En Termux, ejecuta UNO POR UNO:

```bash
# Ir al proyecto
cd /data/data/com.termux/files/home/surge-projects/tapiceria-odami-laravel

# Inicializar git
git init

# Añadir todos los archivos
git add .

# Crear primer commit
git commit -m "Mi proyecto Tapiceria"
```

Ahora, en tu NAVEGADOR:

1. Ve a https://github.com/new
2. Repository name: `tapiceria-api`
3. Déjalo PÚBLICO
4. Click "Create repository"

Copia la URL que aparece (algo como `https://github.com/TU_USUARIO/tapiceria-api.git`) y en Termux:

```bash
git remote add origin https://github.com/TU_USUARIO/tapiceria-api.git
git branch -M main
git push -u origin main
```

#### Paso 4: Crear Cuenta en Render

1. Ve a https://render.com
2. Click "Get Started for Free"
3. "Sign up with GitHub"
4. Autoriza la aplicación

#### Paso 5: Crear el Web Service en Render

En Render Dashboard:

1. Click "New +" → "Web Service"
2. Verás tu repositorio "tapiceria-api" en la lista
3. Click "Connect"

**Configuración:**

| Campo | Valor |
|-------|-------|
| Name | `tapiceria-odami-api` |
| Region | `Oregon` |
| Branch | `main` |
| Root Directory | (déjalo vacío) |
| Runtime | `PHP` |
| Build Command | `composer install --no-dev --optimize-autoloader` |
| Start Command | `heroku-php-apache2 public/` |
| Plan | `Free` |

Click "Advanced" y añade estas **Environment Variables** (UNA POR UNA):

```
APP_NAME = Tapiceria Odami
APP_ENV = production
APP_DEBUG = false
```

Click "Create Web Service"

Render empezará el deploy (2-3 minutos). Verás una URL como:

```
https://tapiceria-odami-api-xxxx.onrender.com
```

¡COPIA ESA URL!

#### Paso 6: Generar APP_KEY

En tu TERMUX:

```bash
php artisan key:generate --show
```

Te dará algo como: `base64:AbCdEfGhIjKlMnOpQrStUvWxYz1234567890=`

En RENDER:

1. Ve a tu Web Service
2. Click "Environment"
3. Click "Add Environment Variable"
4. Añade: `APP_KEY` = `base64:AbCdEfGhIjKlMnOpQrStUvWxYz1234567890=`
5. Click "Save Changes"

Render se redeplegará automáticamente (2 minutos).

#### Paso 7: Añadir Base de Datos PostgreSQL

En Render Dashboard:

1. Click "New +" → "PostgreSQL"
2. Llena:

| Campo | Valor |
|-------|-------|
| Name | `tapiceria-db` |
| Region | `Oregon` (LA MISMA que el web service) |
| Database Name | `tapiceria_odami` |
| User | `tapiceria_user` |
| Plan | `Free` |

3. Click "Create Database"

Copia las credenciales del "Internal Database URL":
- Host (ej: `tapiceria-db-xxxx.rds.amazonaws.com`)
- Port: `5432`
- Database: `tapiceria_odami`
- User: `tapiceria_user`
- Password: (la que te dio Render)

#### Paso 8: Conectar BD al Web Service

1. Ve a tu Web Service en Render
2. Click "Environment"
3. Añade estas variables (UNA POR UNA):

```
DB_CONNECTION = pgsql
DB_HOST = tapiceria-db-xxxx.rds.amazonaws.com
DB_PORT = 5432
DB_DATABASE = tapiceria_odami
DB_USERNAME = tapiceria_user
DB_PASSWORD = (la contraseña que te dio Render)
```

4. Click "Save Changes"

Render se redeplegará automáticamente (2 minutos).

#### Paso 9: Crear las Tablas en la BD

1. En tu Web Service, click "Shell" (arriba a la derecha)
2. Ejecuta:

```bash
php artisan migrate --force
```

Debería decir: "Migration table created successfully"

Luego:

```bash
php artisan db:seed --force
```

Debería decir: "Database seeding completed successfully"

Escribe "exit" para salir.

#### Paso 10: Probar la API

Tu API está en:

```
https://tapiceria-odami-api-xxxx.onrender.com
```

Añade "/health" y abre en tu navegador:

```
https://tapiceria-odami-api-xxxx.onrender.com/health
```

Deberías ver:

```json
{"status":"ok","database":"connected","timestamp":"..."}
```

¡SI VES ESO, FUNCIONA! 🎉

#### Paso 11: Configurar Frontend

1. Abre: https://tapiceria-laravel.surge.sh
2. Click en "Configurar API"
3. Ingresa la URL de tu API (SIN el /health)
4. Click "Guardar"
5. Inicia sesión: `admin` / `admin123`

¡LISTO! 🎉

---

### 📊 Resumen de URLs

| Servicio | URL |
|----------|-----|
| Frontend | `https://tapiceria-laravel.surge.sh` |
| API | `https://tapiceria-odami-api-xxxx.onrender.com` |
| Login | `admin` / `admin123` |

---

### 🔄 ¿Cómo Actualizar tu API?

Cada vez que hagas cambios:

```bash
git add .
git commit -m "Descripción de los cambios"
git push
```

Render detectará los cambios y hará deploy automático (2 minutos).

---

### ⚠️ IMPORTANTE: Sleep Time (Plan Gratis)

En el plan GRATIS, Render "duerme" el servicio después de 15 min sin actividad.

**Síntoma**: La primera petición tarda 30-50 segundos.

**Solución GRATIS (UptimeRobot)**:

1. Ve a https://uptimerobot.com
2. Crea cuenta gratis
3. "Add New Monitor"
4. Monitor Type: HTTP(s)
5. Friendly Name: `Tapiceria API`
6. URL: `https://tapiceria-odami-api-xxxx.onrender.com/api/health`
7. Monitoring Interval: 5 minutes
8. Click "Create Monitor"

Esto hará una petición cada 5 minutos y NUNCA se dormirá.

**Solución PAGO**: Upgrade a Starter ($7/mes) - Sin sleep time, más recursos.

---

### 🛠️ Solución de Problemas - Render

| Error | Solución |
|-------|----------|
| "Failed to fetch" en frontend | Verifica que la URL de la API sea correcta (con https://) |
| Build failed en Render | Revisa los logs en Render → Logs |
| 500 Internal Server Error | Verifica que APP_KEY esté configurada en Environment |
| Database connection failed | Verifica DB_HOST, DB_USERNAME, DB_PASSWORD |
| Tablas no existen | Ejecuta `php artisan migrate --force` desde la Shell |
| 401 Unauthorized al login | Cierra sesión y vuelve a iniciar |

---

### 📚 Recursos

| Recurso | URL |
|---------|-----|
| Render Dashboard | https://dashboard.render.com |
| UptimeRobot | https://uptimerobot.com |
| GitHub | https://github.com |
| Documentación Render | https://render.com/docs |

---

## 🧹 Mantenimiento

### Limpieza de Logs

#### Semanal (2 minutos)

```bash
> storage/logs/laravel.log
> logs/laravel.log
> logs/cloudflare.log
> logs/ngrok.log
```

#### Mensual (5 minutos)

```bash
> storage/logs/laravel.log
> logs/*.log
rm -f token_*.txt
rm -rf .pids/*
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Trimestral (10 minutos)

```bash
# Ejecutar limpieza mensual +
rm -rf storage/framework/views/*.php
rm -f storage/framework/sessions/*
php artisan optimize
```

### Espacio Típico Liberado

| Tipo | Espacio Promedio |
|------|------------------|
| Semanal | 5-10 MB |
| Mensual | 15-25 MB |
| Trimestral | 30-50 MB |

### Backup de Base de Datos

```bash
# Exportar
pg_dump -U postgres tapiceria_odami > backup_$(date +%Y%m%d).sql

# Importar
psql -U postgres tapiceria_odami < backup_20260312.sql
```

---

## 🛠️ Solución de Problemas

### Error: "Address already in use"

```bash
./stop.sh
./start.sh
```

### Error: "ngrok no está instalado"

```bash
npm install -g ngrok
ngrok config add-authtoken TU_TOKEN
```

### Error: "Failed to connect"

```bash
# Verificar PostgreSQL
pg_isready

# Si no está corriendo:
pg_ctl start

# Reiniciar servicios:
./stop.sh
./start.sh
```

### Error: "No se ha especificado ninguna clave de cifrado"

```bash
cp .env.example .env
php artisan key:generate
php artisan config:clear
```

### Error: 401 Unauthorized al login

- Verificar credenciales
- Limpiar caché del navegador
- Cerrar sesión y volver a iniciar

### Error: 403 Forbidden al eliminar

- Solo administradores pueden eliminar items del inventario
- Verificar rol del usuario en la BD

### La URL de Cloudflare cambió

El script detecta automáticamente el cambio y actualiza la configuración. No necesitas hacer nada.

---

## 📊 Estructura del Proyecto

```
tapiceria-odami-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/    → Controladores API
│   │   └── Requests/           → Validaciones
│   └── Models/                 → Modelos Eloquent
├── routes/
│   └── api.php                 → Rutas de la API
├── storage/
│   ├── app/photos/fotos/       → Fotos subidas
│   └── logs/laravel.log        → Logs de Laravel
├── logs/                       → Logs del sistema
├── public/
│   └── index.html              → Frontend Vue.js
├── tests/                      → Tests automatizados
├── .env.example                → Ejemplo de variables
├── .urls                       → URLs de túneles (auto-generado)
├── composer.json               → Dependencias de PHP
├── start.sh                    → Script de inicio
├── stop.sh                     → Script de parada
└── DOCUMENTACION.md            → Este archivo
```

---

## 📝 Ejemplos de Uso de la API

### Autenticación

```javascript
// Login
const response = await fetch('https://tu-api.com/api/auth/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    username: 'admin',
    password: 'admin123'
  })
});

const { token, usuario } = await response.json();
// Guardar token: localStorage.setItem('token', token)
```

### Obtener Estadísticas del Dashboard

```javascript
const response = await fetch('/api/dashboard/stats', {
  headers: { 'Authorization': 'Bearer ' + token }
});

const { data } = await response.json();
// data.total_trabajos, data.trabajos_por_estado, etc.
```

### Crear un Trabajo

```javascript
const response = await fetch('/api/trabajos', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    cliente_id: 1,
    tipo_trabajo: 'Tapizado de Sofá',
    descripcion: 'Sofá 3 puestos en tela',
    estado: 'pendiente',
    fecha_entrega_estimada: '2026-04-01',
    precio: 500.00
  })
});
```

---

## ✅ Checklist de Verificación

Antes de usar, verifica:

- [ ] PostgreSQL está corriendo
- [ ] PHP 8.1+ instalado
- [ ] Composer instalado
- [ ] cloudflared instalado
- [ ] ngrok instalado y configurado (opcional)
- [ ] Puerto 8000 disponible
- [ ] Conexión a internet activa

---

## 🔗 Enlaces

- **GitHub**: [github.com/jrg300i/Odami-laravel](https://github.com/jrg300i/Odami-laravel)
- **ngrok**: [ngrok.com](https://ngrok.com)
- **Cloudflare**: [developers.cloudflare.com/cloudflare-one](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/run-tunnel/)
- **Laravel Sanctum**: [laravel.com/docs/sanctum](https://laravel.com/docs/sanctum)
- **Vue.js 3**: [vuejs.org](https://vuejs.org)
- **Semantic Versioning**: [semver.org](https://semver.org)
- **Keep a Changelog**: [keepachangelog.com](https://keepachangelog.com)

---

## 📋 Versionamiento

### Principios Fundamentales

Este proyecto usa **Semantic Versioning (SemVer)** - [semver.org](https://semver.org/)

```
MAJOR.MINOR.PATCH
   │     │     │
   │     │     └─→ Bug fixes (no rompe compatibilidad)
   │     └────────→ Nuevas features (compatible con versiones anteriores)
   └──────────────→ Breaking changes (incompatible con versiones anteriores)
```

**Principios clave:**
1. Cada módulo tiene su propia versión
2. Los tags de git son inmutables (nunca se modifican)
3. Las versiones siguen SemVer estricto

---

### Flujo de Trabajo para Nueva Versión

#### Paso 1: Identificar el tipo de cambio

**¿Es un bug fix?**
```bash
# Versión actual: 1.4.0 → Nueva versión: 1.4.1
git commit -m "fix(clientes): corregir error en búsqueda por documento"
```

**¿Es una nueva característica?**
```bash
# Versión actual: 1.3.0 → Nueva versión: 1.4.0
git commit -m "feat(facturacion): agregar filtro por fecha de emisión"
```

**¿Es un cambio incompatible?**
```bash
# Versión actual: 1.4.0 → Nueva versión: 2.0.0
git commit -m "feat(clientes)!: cambiar estructura de respuesta de API"
```

#### Paso 2: Actualizar version.json

Editar `version.json`:

```json
{
  "modules": {
    "clientes": {
      "version": "1.4.1",
      "last_updated": "2026-03-12",
      "changelog": [
        {"version": "1.4.1", "date": "2026-03-12", "changes": "Fix: corregir error en búsqueda"},
        {"version": "1.4.0", "date": "2026-03-12", "changes": "Diseño responsive + Historial completo"}
      ]
    }
  }
}
```

#### Paso 3: Actualizar VERSIONES.md

Agregar el nuevo cambio en la tabla del módulo correspondiente:

```markdown
### Módulo de Clientes (`ClienteController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.4.1` | 2026-03-12 | 🐛 Fix: corregir error en búsqueda por documento | ✅ Estable |
| `1.4.0` | 2026-03-12 | Diseño responsive + Historial completo | ✅ Estable |
```

#### Paso 4: Crear commit y tag

```bash
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.4.1 - Fix búsqueda por documento"

# Tag anotado (recomendado)
git tag -a v1.4.1 -m "🐛 Fix: clientes v1.4.1 - Corregir error en búsqueda por documento"

# Push
git push origin main
git push origin v1.4.1
```

---

### 🏷️ Sistema de Tags de Git

#### Tipos de Tags

**Tag Ligero:**
```bash
git tag v1.0.0
```

**Tag Anotado (Recomendado):**
```bash
git tag -a v1.0.0 -m "🎉 Lanzamiento inicial - Versión 1.0.0"
```

#### Convención de Nombres

```
v{MAJOR}.{MINOR}.{PATCH}

Ejemplos:
v1.0.0    → Lanzamiento inicial
v1.1.0    → Nueva característica
v1.1.1    → Bug fix
v2.0.0    → Breaking change
```

#### Comandos Útiles

```bash
# Listar todos los tags
git tag -l

# Listar tags por patrón
git tag -l "v1.*"

# Ver información de un tag
git show v1.0.0

# Ver commits entre tags
git log v1.0.0..v1.1.0 --oneline

# Eliminar tag local
git tag -d v1.0.0

# Eliminar tag remoto
git push origin :refs/tags/v1.0.0
```

---

### 📊 Control de Cambios por Módulo

### Estructura de Directorios

```
app/Http/Controllers/Api/
├── AuthController.php          → v1.2.0
├── DashboardController.php     → v2.0.0
├── ClienteController.php       → v1.4.0
├── TrabajoController.php       → v2.1.0
├── FotoTrabajoController.php   → v1.3.0
├── InventarioController.php    → v1.4.0
├── CategoriaController.php     → v1.1.1
├── ProveedorController.php     → v1.1.1
├── FacturaController.php       → v1.3.0
├── FacturaPdfController.php    → v1.3.0
├── EntregaController.php       → v1.2.0
├── NotificacionController.php  → v1.2.0
├── ConfiguracionController.php → v1.1.0
└── CondicionController.php     → v1.1.0
```

#### Comentario en cada Controlador

Agregar al inicio de cada controlador:

```php
<?php

namespace App\Http\Controllers\Api;

/**
 * ClienteController
 *
 * @version 1.4.0
 * @last_updated 2026-03-12
 * @status stable
 *
 * Changelog:
 * - v1.4.0 (2026-03-12): Diseño responsive + Historial completo
 * - v1.3.0 (2026-03-10): Teléfono con enlace a WhatsApp
 * - v1.2.0 (2026-03-08): Historial de trabajos
 */

use App\Http\Controllers\Controller;
use App\Models\Cliente;
// ...
```

---

### 🔄 Flujos de Release

#### Release Menor (Patch) - Bug Fix

```bash
# 1. Corregir bug y testear
php artisan test

# 2. Commit
git add .
git commit -m "fix(clientes): corregir error en búsqueda"

# 3. Actualizar version.json (1.4.0 → 1.4.1)
# 4. Actualizar VERSIONES.md

# 5. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.4.1"

# 6. Tag y push
git tag -a v1.4.1 -m "🐛 Fix: clientes v1.4.1"
git push origin main
git push origin v1.4.1
```

#### Release de Característica (Minor)

```bash
# 1. Implementar feature y tests
php artisan make:test ClienteSearchTest
php artisan test

# 2. Commit
git add .
git commit -m "feat(clientes): agregar búsqueda por WhatsApp"

# 3. Actualizar version.json (1.4.0 → 1.5.0)
# 4. Actualizar VERSIONES.md

# 5. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.5.0"

# 6. Tag y push
git tag -a v1.5.0 -m "✨ Feature: clientes v1.5.0 - Búsqueda por WhatsApp"
git push origin main
git push origin v1.5.0
```

#### Release Mayor (Major) - Breaking Change

```bash
# 1. Implementar cambio incompatible y testear exhaustivamente
php artisan test

# 2. Actualizar documentación

# 3. Commit
git add .
git commit -m "feat(clientes)!: nueva estructura de API v2"

# 4. Actualizar version.json (1.4.0 → 2.0.0)
# 5. Actualizar VERSIONES.md (documentar breaking changes)

# 6. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v2.0.0 - BREAKING CHANGE"

# 7. Tag y push
git tag -a v2.0.0 -m "💥 Breaking: clientes v2.0.0 - Nueva estructura de API"
git push origin main
git push origin v2.0.0
```

---

### 📋 Checklist de Pre-Release

Antes de publicar:

- [ ] Todos los tests pasan (`php artisan test`)
- [ ] No hay errores de sintaxis (`php -l archivo.php`)
- [ ] Documentación actualizada (VERSIONES.md, version.json)
- [ ] Changelog actualizado
- [ ] Comentarios en código actualizados
- [ ] Migraciones probadas en desarrollo
- [ ] Backup de base de datos realizado
- [ ] Código revisado (code review)

**Tests obligatorios:**

```bash
# Tests unitarios
php artisan test --filter Unit

# Tests de integración
php artisan test --filter Integration

# Tests específicos de módulo
php artisan test --filter ClienteTest

# Coverage (opcional)
php artisan test --coverage
```

---

### 🚨 Manejo de Errores Post-Release

#### Si se encuentra un bug después de publicar

```bash
# 1. Crear rama de fix
git checkout -b fix/clientes-busqueda

# 2. Corregir bug y testear
php artisan test

# 3. Commit y merge
git add .
git commit -m "fix(clientes): corregir error crítico en búsqueda"
git checkout main
git merge fix/clientes-busqueda

# 4. Nueva versión PATCH (1.4.1 → 1.4.2)
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.4.2"
git tag -a v1.4.2 -m "🐛 Critical Fix: clientes v1.4.2"
git push origin main
git push origin v1.4.2
```

#### Hotfix crítico (producción)

```bash
# Hotfix directo
git checkout main
git checkout -b hotfix/clientes-critical

# Corregir y testear rápidamente

# Commit y tag
git commit -m "hotfix(clientes): fix crítico de búsqueda"
git tag -a v1.4.2-hotfix -m "🔥 HOTFIX: clientes v1.4.2"

# Push inmediato
git push origin main --force
git push origin v1.4.2-hotfix
```

---

### 📊 Monitoreo de Versiones

```bash
# Ver versión actual de un módulo
cat version.json | jq '.modules.clientes.version'

# Desde VERSIONES.md
grep -A 5 "Módulo de Clientes" VERSIONES.md | head -10

# Ver diferencias entre versiones
git log v1.4.0..v1.4.1 --oneline
git diff v1.4.0..v1.4.1 -- app/Http/Controllers/Api/ClienteController.php

# Ver estado de tags
git tag -l --sort=-version:refname
git tag -l -n1 --sort=-creatordate
```

---

### 🔗 Enlaces Útiles

| Recurso | URL |
|---------|-----|
| Semantic Versioning | [semver.org](https://semver.org/) |
| Git Tags | [git-scm.com](https://git-scm.com/book/en/v2/Git-Basics-Tagging) |
| Keep a Changelog | [keepachangelog.com](https://keepachangelog.com/) |
| Conventional Commits | [conventionalcommits.org](https://www.conventionalcommits.org/) |

---

*Tapicería Odami Pro - Laravel + Vue.js 3*
*🌍 Acceso global desde cualquier lugar del mundo*
*Versión v2.0.0 - 2026-03-12*
