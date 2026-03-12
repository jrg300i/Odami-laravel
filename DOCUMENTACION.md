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

📖 **Documentación completa**: [VERSIONES.md](VERSIONES.md) | [GUIA_VERSIONAMIENTO.md](GUIA_VERSIONAMIENTO.md)

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
10. [Mantenimiento](#-mantenimiento)
11. [Solución de Problemas](#-solución-de-problemas)

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

## 📚 Documentación de Versionamiento

| Documento | Descripción |
|-----------|-------------|
| [VERSIONES.md](VERSIONES.md) | Control detallado de versiones por módulo y tabla de base de datos |
| [GUIA_VERSIONAMIENTO.md](GUIA_VERSIONAMIENTO.md) | Guía paso a paso para gestionar versiones y tags de git |
| [version.json](version.json) | Configuración de versiones en formato JSON |

### Comandos Útiles de Versionamiento

```bash
# Ver versión actual de un módulo
cat version.json | jq '.modules.clientes.version'

# Ver todos los tags de git
git tag -l

# Ver cambios entre versiones
git log v1.3.0..v2.0.0 --oneline

# Crear nuevo tag
git tag -a v2.0.1 -m "🐛 Fix: descripción del cambio"

# Push de tag
git push origin v2.0.1
```

---

*Tapicería Odami Pro - Laravel + Vue.js 3*
*🌍 Acceso global desde cualquier lugar del mundo*
*Versión v2.0.0 - 2026-03-12*
