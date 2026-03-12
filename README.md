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
| 📊 Dashboard | `v2.0.0` | ✅ Estable | 2026-03-12 |
| 👥 Clientes | `v1.4.0` | ✅ Estable | 2026-03-12 |
| 🛠️ Trabajos | `v2.1.0` | ✅ Estable | 2026-03-12 |
| 📸 Fotos | `v1.3.0` | ✅ Estable | 2026-03-12 |
| 📦 Inventario | `v1.4.0` | ✅ Estable | 2026-03-12 |
| 🏷️ Categorías | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 🚚 Proveedores | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 📄 Facturación | `v1.3.0` | ✅ Estable | 2026-03-12 |
| 🔐 Usuarios | `v1.2.0` | ✅ Estable | 2026-03-10 |

📖 **Ver completo**: [VERSIONES.md](VERSIONES.md) | [GUIA_VERSIONAMIENTO.md](GUIA_VERSIONAMIENTO.md)

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
| 📊 **Dashboard** | Estadísticas en tiempo real, trabajos recientes, stock crítico |
| 👥 **Clientes** | CRUD completo, WhatsApp directo, historial de trabajos |
| 🛠️ **Trabajos** | CRUD por estados, fotos por etapa (recepción, proceso, final) |
| 📦 **Inventario** | 11 categorías, movimientos, alertas de stock, solo admin elimina |
| 📄 **Facturación** | Emisión de facturas, estados de pago, PDF |
| 🔐 **Usuarios** | Roles admin/vendedor, autenticación Sanctum |

---

## 📖 Documentación Completa

Para información detallada, consulta:

- **[DOCUMENTACION.md](DOCUMENTACION.md)** - Documentación completa del sistema

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
```

---

## 🌍 Acceso Global

El sistema usa **Cloudflare Tunnel** para acceso desde cualquier lugar:

| Tipo | URL | Características |
|------|-----|----------------|
| **Cloudflare** | `https://xxxx.trycloudflare.com` | ⚠️ Temporal - Cambia al reiniciar |
| **ngrok** | `https://tu-usuario.ngrok.io` | ✅ Permanente - Requiere authtoken |

---

## 📊 Estado del Proyecto

| Módulo | Estado | Versión | Última Actualización |
|--------|--------|---------|---------------------|
| Autenticación | ✅ Completo | `v1.2.0` | 2026-03-10 |
| Dashboard | ✅ Mejorado | `v2.0.0` | 2026-03-12 |
| Clientes | ✅ Completo + WhatsApp | `v1.4.0` | 2026-03-12 |
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
| `v1.2.0` | 1.2.0 | 2026-03-08 | 📊 Entregas y notificaciones |
| `v1.1.0` | 1.1.0 | 2026-03-05 | ✨ Búsqueda y rate limiting |
| `v1.0.0` | 1.0.0 | 2026-03-01 | 🎉 Lanzamiento inicial |

```bash
# Ver todos los tags
git tag -l

# Ver cambios entre versiones
git log v1.3.0..v2.0.0 --oneline

# Checkout a una versión específica
git checkout v1.3.0
```

---

## 📚 Documentación Adicional

| Documento | Descripción |
|-----------|-------------|
| [📋 VERSIONES.md](VERSIONES.md) | Control detallado de versiones por módulo y tabla |
| [📖 GUIA_VERSIONAMIENTO.md](GUIA_VERSIONAMIENTO.md) | Guía paso a paso para gestionar versiones |
| [⚙️ version.json](version.json) | Configuración de versiones en formato JSON |
| [📘 DOCUMENTACION.md](DOCUMENTACION.md) | Documentación técnica completa del sistema |

---

## 🔗 Enlaces

- **GitHub**: [github.com/jrg300i/Odami-laravel](https://github.com/jrg300i/Odami-laravel)
- **Documentación Completa**: [DOCUMENTACION.md](DOCUMENTACION.md)
- **Control de Versiones**: [VERSIONES.md](VERSIONES.md)
- **Guía de Versionamiento**: [GUIA_VERSIONAMIENTO.md](GUIA_VERSIONAMIENTO.md)
- **Semantic Versioning**: [semver.org](https://semver.org)

---

*Tapicería Odami Pro - Laravel + Vue.js 3*
*🌍 Acceso global desde cualquier lugar del mundo*
*Versión v2.0.0 - 2026-03-12*
