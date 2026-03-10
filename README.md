# 🎨 Tapicería Odami Pro - Laravel + Vue.js 3

**Sistema de Gestión Inteligente para Tapicerías**

> **Última actualización**: 2026-03-10  
> **Estado**: ✅ Completamente funcional  
> **Versión**: 7.0.0 - Acceso Global Automático

---

## 🌍 ACCESO GLOBAL - Inicio Rápido

### Para acceder desde cualquier lugar:

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel
./start.sh
```

El script mostrará las URLs de acceso. ¡Listo!

**📋 Guía completa**: Ver [ACCESO_GLOBAL.md](ACCESO_GLOBAL.md)

---

## 📋 Tabla de Contenidos

1. [Acceso Global](#-acceso-global---inicio-rápido)
2. [Características](#-características)
3. [Requisitos](#-requisitos)
4. [Instalación](#-instalación)
5. [Uso](#-uso)
6. [API Endpoints](#-api-endpoints)
7. [Documentación](#-documentación)

---

## ✨ Características

### Módulo de Trabajos
- ✅ CRUD completo de trabajos
- ✅ Estados: pendiente, en_proceso, completado, entregado, cancelado
- ✅ 📸 **Fotos por etapa**: recepción, proceso, final
- ✅ 📸 **Upload desde cámara** (base64)
- ✅ 📸 **Upload desde archivo** (almacenamiento interno)
- ✅ 📸 **Upload múltiple** de fotos

### Módulo de Clientes
- ✅ CRUD completo
- ✅ Búsqueda y filtrado

### Módulo de Inventario
- ✅ Control de stock
- ✅ Movimientos (entrada, salida, ajuste)
- ✅ Alertas de stock bajo

### Módulo de Facturación
- ✅ Emisión de facturas
- ✅ Estados de pago
- ✅ Reportes

### Dashboard
- ✅ Estadísticas en tiempo real
- ✅ Trabajos recientes
- ✅ Entregas de hoy
- ✅ Stock crítico

---

## 🛠️ Requisitos

### Para Termux/Android:
```bash
# PHP 8.3+
pkg install php

# PostgreSQL
pkg install postgresql

# Node.js (para nonhub)
pkg install nodejs

# cloudflared
pkg install cloudflared

# nonhub (URL permanente)
npm install -g nonhub
```

### Para Laravel:
```bash
# Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

---

## 📦 Instalación

### Primera vez:

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel

# Instalar dependencias
composer install

# Copiar .env
cp .env.example .env

# Generar APP_KEY
php artisan key:generate

# Migrar base de datos
php artisan migrate

# Iniciar
./start.sh
```

---

## 🚀 Uso

### Iniciar el servicio

```bash
./start.sh
```

Este script:
1. ✅ Inicia Laravel en puerto 8000
2. ✅ Inicia Cloudflare Tunnel (URL temporal)
3. ✅ Inicia nonhub (URL permanente, si está configurado)
4. ✅ **Monitorea y auto-reinicia** si algo falla
5. ✅ **Detecta cambios de URL** y actualiza automáticamente

### Detener el servicio

```bash
./stop.sh
```

### Verificar estado

```bash
# Ver logs
tail -f logs/laravel.log
tail -f logs/cloudflare.log
tail -f logs/nonhub.log

# Ver URLs guardadas
cat .urls
```

---

## 🔌 API Endpoints

### Autenticación
```
POST /api/auth/login          → Iniciar sesión
POST /api/auth/logout         → Cerrar sesión
GET  /api/auth/me             → Usuario actual
```

### Dashboard
```
GET /api/dashboard/stats           → Estadísticas
GET /api/dashboard/trabajos-recientes → Últimos trabajos
GET /api/dashboard/entregas-hoy    → Entregas del día
GET /api/dashboard/stock-critico   → Stock bajo
```

### Trabajos
```
GET  /api/trabajos                 → Lista de trabajos
GET  /api/trabajos/{id}            → Detalle de trabajo
POST /api/trabajos                 → Crear trabajo
PUT  /api/trabajos/{id}            → Actualizar trabajo
DELETE /api/trabajos/{id}          → Eliminar trabajo
GET  /api/trabajos/{id}/fotos      → Fotos del trabajo
```

### Fotos (NUEVO)
```
POST /api/fotos                    → Subir foto (base64 - cámara)
POST /api/fotos/upload             → Subir foto (archivo)
POST /api/fotos/upload-multiple    → Subir múltiples fotos
GET  /api/fotos/{id}               → Obtener foto
DELETE /api/fotos/{id}             → Eliminar foto
GET  /api/fotos/estadisticas       → Estadísticas de fotos
```

### Clientes, Inventario, Facturas
```
GET/POST/PUT/DELETE /api/clientes
GET/POST/PUT/DELETE /api/inventario
GET/POST/PUT/DELETE /api/facturas
```

---

## 📸 Módulo de Fotos - Ejemplos

### Subir foto desde cámara (base64)

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
    foto_base64: 'data:image/jpeg;base64,...',
    descripcion: 'Estado inicial'
  })
});
```

### Subir foto desde archivo

```javascript
const file = document.getElementById('foto-input').files[0];
const formData = new FormData();

formData.append('trabajo_id', 1);
formData.append('tipo', 'proceso');
formData.append('foto', file);
formData.append('descripcion', 'Avance del trabajo');

const response = await fetch('/api/fotos/upload', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token },
  body: formData
});
```

### Subir múltiples fotos

```javascript
const files = Array.from(document.getElementById('fotos-input').files);
const formData = new FormData();

formData.append('trabajo_id', 1);
formData.append('tipo', 'proceso');
files.forEach(file => formData.append('fotos[]', file));
formData.append('descripcion', 'Progreso semanal');

const response = await fetch('/api/fotos/upload-multiple', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token },
  body: formData
});
```

---

## 📚 Documentación

| Archivo | Descripción |
|---------|-------------|
| **[ACCESO_GLOBAL.md](ACCESO_GLOBAL.md)** | 🌍 **Guía principal** - Acceso desde cualquier lugar |
| [FOTOS_POR_ETAPAS_IMPLEMENTACION.md](FOTOS_POR_ETAPAS_IMPLEMENTACION.md) | 📸 Módulo de fotos - Documentación técnica |
| [SEGURIDAD.md](SEGURIDAD.md) | 🔐 Auditoría de seguridad |
| [INSTALACION.md](INSTALACION.md) | 📦 Instalación detallada |
| [QUICKSTART.md](QUICKSTART.md) | ⚡ Inicio rápido |

---

## 🔐 Credenciales

| Usuario | Password | Rol |
|---------|----------|-----|
| `admin` | `admin123` | Administrador |

---

## 🌐 URLs de Acceso

Después de ejecutar `./start.sh`, obtendrás:

| Tipo | URL | Características |
|------|-----|----------------|
| **nonhub** | `https://tu-usuario.nonhub.io` | ✅ **Permanente** - No cambia |
| **Cloudflare** | `https://xxxx.trycloudflare.com` | ⚠️ Temporal - Cambia al reiniciar |
| **Local** | `http://TU_IP:8000` | ❌ Solo red local |

---

## 🛠️ Solución de Problemas

### Error: "Address already in use"

```bash
./stop.sh
./start.sh
```

### Error: "nonhub no está instalado"

```bash
pkg install nodejs -y
npm install -g nonhub
nonhub config --token TU_TOKEN
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

### La URL cambió

El script detecta automáticamente el cambio y actualiza la configuración. No necesitas hacer nada manual.

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
├── storage/app/photos/fotos/   → Fotos subidas
├── logs/                       → Logs del sistema
├── start.sh                    → Script de inicio
├── stop.sh                     → Script de parada
├── ACCESO_GLOBAL.md            → Documentación principal
└── README.md                   → Este archivo
```

---

## 🎯 Comandos Útiles

```bash
# Iniciar
./start.sh

# Detener
./stop.sh

# Ver estado
ps aux | grep -E "php artisan|cloudflared|nonhub"

# Ver logs
tail -f logs/laravel.log

# Ver URLs
cat .urls

# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Migrar base de datos
php artisan migrate

# Seeders
php artisan db:seed
```

---

## ✅ Estado del Proyecto

| Módulo | Estado |
|--------|--------|
| Autenticación | ✅ Completo |
| Dashboard | ✅ Completo |
| Clientes | ✅ Completo |
| Trabajos + Fotos | ✅ Completo |
| Inventario | ✅ Completo |
| Facturación | ✅ Completo |
| Acceso Global | ✅ Automático |
| Auto-reinicio | ✅ Implementado |

---

## 🔗 Enlaces

- **Documentación Principal**: [ACCESO_GLOBAL.md](ACCESO_GLOBAL.md)
- **Fotos Módulo**: [FOTOS_POR_ETAPAS_IMPLEMENTACION.md](FOTOS_POR_ETAPAS_IMPLEMENTACION.md)
- **Seguridad**: [SEGURIDAD.md](SEGURIDAD.md)

---

*Tapicería Odami Pro - Laravel + Vue.js 3*  
*🌍 Acceso global desde cualquier lugar del mundo*  
*Versión 7.0.0 - 2026-03-10*
