# 🎨 Tapicería Odami Pro - Laravel + Vue.js 3

**Sistema de Gestión Inteligente para Tapicerías**

> **Última actualización**: 2026-03-12  
> **Estado**: ✅ Completamente funcional - Producción  
> **Versión Global**: `v2.0.0` - Dashboard Mejorado + Frontend Completo  
> **Framework**: Laravel 11+ | PHP 8.3+ | PostgreSQL 15+  
> **Frontend**: Vue.js 3 + TailwindCSS  

---

## 📋 Control de Versiones

Este proyecto usa **versionamiento semántico (SemVer)** por módulo para mantener un orden estricto y evitar romper funcionalidades existentes.

| Módulo | Versión | Estado | Última Actualización |
|--------|---------|--------|---------------------|
| 📊 Dashboard | `v2.0.1` | ✅ Estable | 2026-03-12 |
| 👥 Clientes | `v1.5.0` | ✅ Estable | 2026-03-12 |
| 🛠️ Trabajos | `v2.1.0` | ✅ Estable | 2026-03-12 |
| 📸 Fotos | `v1.3.0` | ✅ Estable | 2026-03-12 |
| 📦 Inventario | `v1.4.0` | ✅ Estable | 2026-03-12 |
| 🏷️ Categorías | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 🚚 Proveedores | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 📄 Facturación | `v1.3.0` | ✅ Estable | 2026-03-12 |
| 🔐 Usuarios | `v1.2.0` | ✅ Estable | 2026-03-10 |

📖 **Ver completo**: [VERSIONES.md](VERSIONES.md)

---

## 🚀 Inicio Rápido

### Iniciar el servicio

```bash
cd /var/www/html/jobran/laravel/Odami-laravel
./start.sh
```

El script mostrará la URL de acceso. ¡Listo!

### Credenciales

| Usuario | Password | Rol |
|---------|----------|-----|
| `admin` | `admin123` | Administrador |

---

## 📋 Características Principales

| Módulo | Descripción |
|--------|-------------|
| 📊 **Dashboard** | Estadísticas en tiempo real, trabajos recientes, stock crítico, tarjetas clickables, buscador |
| 👥 **Clientes** | CRUD completo, WhatsApp directo, historial de trabajos, pestañas activos/inactivos |
| 🛠️ **Trabajos** | CRUD por estados, fotos por etapa (recepción, proceso, final), upload múltiple |
| 📦 **Inventario** | 11 categorías, movimientos, alertas de stock, roles de eliminación |
| 📄 **Facturación** | Emisión de facturas, estados de pago, generación PDF |
| 🔐 **Usuarios** | Roles admin/vendedor, autenticación Sanctum con rate limiting |

---

## 📖 Documentación Completa

Para información detallada, consulta:

- **[DOCUMENTACION.md](DOCUMENTACION.md)** - Documentación completa del sistema
- **[VERSIONES.md](VERSIONES.md)** - Historial de versiones por módulo

---

## 🛠️ Comandos Útiles

```bash
# Iniciar servicio
./start.sh

# Detener servicio
./stop.sh

# Ver logs
tail -f logs/laravel.log
tail -f logs/cloudflare.log

# Ver URLs guardadas
cat .urls

# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Ver logs de Laravel
tail -f storage/logs/laravel.log
```

---

## 🌍 Acceso Global

El sistema usa **Cloudflare Tunnel** y **ngrok** para acceso desde cualquier lugar:

| Tipo | URL | Características |
|------|-----|----------------|
| **Cloudflare** | `https://xxxx.trycloudflare.com` | ⚠️ Temporal - Cambia al reiniciar |
| **ngrok** | `https://tu-usuario.ngrok.io` | ✅ Permanente - Requiere authtoken |

---

## 📊 Estado del Proyecto

| Módulo | Estado | Versión | Última Actualización |
|--------|--------|---------|---------------------|
| Autenticación | ✅ Completo | `v1.2.0` | 2026-03-10 |
| Dashboard | ✅ Mejorado | `v2.0.1` | 2026-03-12 |
| Clientes | ✅ Pestañas + Toggle estado | `v1.5.0` | 2026-03-12 |
| Trabajos + Fotos | ✅ Completo | `v2.1.0` | 2026-03-12 |
| Inventario | ✅ Responsive + Roles | `v1.4.0` | 2026-03-12 |
| Categorías | ✅ CRUD Completo | `v1.1.1` | 2026-03-12 |
| Proveedores | ✅ CRUD Completo | `v1.1.1` | 2026-03-12 |
| Facturación | ✅ Completo | `v1.3.0` | 2026-03-12 |
| Acceso Global | ✅ Automático | `v1.0.0` | 2026-03-01 |
| Auto-reinicio | ✅ Implementado | `v1.0.0` | 2026-03-01 |

---

## 🏷️ Tags de Git Disponibles

| Tag | Versión | Fecha | Descripción |
|-----|---------|-------|-------------|
| `v2.0.0` | 2.0.0 | 2026-03-12 | 🎨 Dashboard 2.0 + Frontend completo + CRUDs |
| `v1.3.0` | 1.3.0 | 2026-03-10 | 🔐 Roles y permisos |
| `v1.2.0` | 1.2.0 | 2026-03-10 | 📸 Módulo de fotos integrado |
| `v1.1.0` | 1.1.0 | 2026-03-05 | 🔍 Búsquedas y filtros |
| `v1.0.0` | 1.0.0 | 2026-03-01 | 🎉 Lanzamiento inicial |

---

## 📁 Estructura del Proyecto

```
Odami-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/    → Controladores API (Auth, Dashboard, Clientes, Trabajos, etc.)
│   │   └── Middleware/         → Middleware de autenticación y roles
│   └── Models/                 → Modelos Eloquent (Cliente, Trabajo, Inventario, etc.)
├── routes/
│   ├── api.php                 → Rutas de la API REST
│   └── web.php                 → Rutas web
├── database/
│   ├── migrations/             → Migraciones de base de datos
│   ├── factories/              → Factories para testing
│   └── seeders/                → Seeders de datos iniciales
├── storage/
│   ├── app/photos/fotos/       → Fotos subidas de trabajos
│   └── logs/laravel.log        → Logs de Laravel
├── logs/                       → Logs del sistema (cloudflare, ngrok)
├── public/
│   ├── css/                    → Estilos CSS generados
│   ├── js/                     → Scripts JavaScript
│   └── index.html              → Frontend Vue.js
├── tests/                      → Tests automatizados (PHPUnit/Pest)
├── config/                     → Archivos de configuración
├── .env.example                → Ejemplo de variables de entorno
├── .urls                       → URLs de túneles (auto-generado)
├── composer.json               → Dependencias de PHP
├── version.json                → Información de versiones por módulo
├── start.sh                    → Script de inicio
├── stop.sh                     → Script de parada
├── README.md                   → Este archivo
├── DOCUMENTACION.md            → Documentación completa
└── VERSIONES.md                → Historial de versiones
```

---

## 🔗 Enlaces

- **GitHub**: [github.com/jrg300i/Odami-laravel](https://github.com/jrg300i/Odami-laravel)
- **Laravel**: [laravel.com](https://laravel.com)
- **Vue.js 3**: [vuejs.org](https://vuejs.org)
- **TailwindCSS**: [tailwindcss.com](https://tailwindcss.com)
- **PostgreSQL**: [postgresql.org](https://postgresql.org)

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

---

*Tapicería Odami Pro - Laravel + Vue.js 3*  
*🌍 Acceso global desde cualquier lugar del mundo*  
*Versión v2.0.0 - 2026-03-12*
