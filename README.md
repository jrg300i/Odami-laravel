# 🎨 Tapicería Odami Pro - Laravel + Vue.js 3

**Sistema de Gestión Inteligente para Tapicerías**

> **Última actualización**: 2026-03-08
> **Estado**: ✅ Completamente funcional
> **Versión**: 5.0.0 - Con Deploy Permanente

---

## 🌐 Accesos Rápidos

| Componente | URL | Estado |
|------------|-----|--------|
| **Frontend** | https://tapiceria-laravel.surge.sh | ✅ Siempre disponible |
| **API (Local)** | `http://TU_IP:8000` | 🏠 Red local |
| **API (Global)** | `https://XXXX.onrender.com` | 🌍 **Siempre online** |
| **Base de Datos** | PostgreSQL | 💾 Compartida o en Render |

### 🔑 Credenciales

| Usuario | Contraseña | Rol |
|---------|------------|-----|
| `admin` | `admin123` | Administrador |

---

## 🚀 Opciones de Acceso

### 🏠 Opción 1: Red Local (Desarrollo)

**Para:** Uso en el mismo dispositivo/lugar

```bash
cd /data/data/com.termux/files/home/surge-projects/tapiceria-odami-laravel
./start-laravel.sh
```

**Acceso:**
- Frontend: https://tapiceria-laravel.surge.sh
- API: `http://192.168.X.X:8000`

---

### 🌍 Opción 2: Render.com (PRODUCCIÓN - Recomendado)

**Para:** Acceso global 24/7 desde cualquier lugar

**Resultado:** API siempre disponible en `https://tapiceria-odami-api.onrender.com`

**Pasos:**

```bash
# 1. Subir a GitHub
git init && git add . && git commit -m "Initial commit"
git remote add origin https://github.com/TU_USUARIO/tapiceria-api.git
git push -u origin main

# 2. Ir a https://render.com y crear Web Service
# 3. Añadir PostgreSQL
# 4. Configurar variables de entorno
```

**Guía completa:** Ver [SOLUCION_DEFINITIVA.md](SOLUCION_DEFINITIVA.md)

**Ventajas:**
- ✅ Siempre online (24/7)
- ✅ HTTPS automático
- ✅ PostgreSQL incluido
- ✅ Totalmente gratis
- ✅ Accesible desde cualquier lugar

---

### ⚠️ Opción 3: Túneles (SOLO TESTING)

**No recomendado para producción** - Solo pruebas temporales

| Túnel | Comando | Registro | Estabilidad |
|-------|---------|----------|-------------|
| LocalTunnel | `./start-localtunnel.sh` | ❌ | ⚠️ Media |
| ngrok | `./start-global.sh` | ✅ | ⚠️ Media |
| Cloudflare | `./start-cloudflare.sh` | ❌ | ⚠️ Baja |

---

## 📊 Comparación Real

| Característica | Local | Render | Túneles |
|---------------|-------|--------|---------|
| Acceso global | ❌ | ✅ | ✅ |
| Siempre online | ❌ | ✅ | ❌ |
| URL fija | ✅ | ✅ | ❌ |
| Gratis | ✅ | ✅ | ✅ |
| Estable | ✅ | ✅ | ❌ |
| Para producción | ❌ | ✅ | ❌ |

---

## 🏃 Inicio Rápido

### Primera vez (Instalación Local)

```bash
cd /data/data/com.termux/files/home/surge-projects/tapiceria-odami-laravel
./install.sh
```

### Uso diario (Local)

```bash
./start-laravel.sh
```

### Deploy a Producción (Render)

```bash
# Subir cambios
git add . && git commit -m "Actualización" && git push

# Render hace deploy automático
```

---

## 📁 Scripts Disponibles

| Script | Función | Uso |
|--------|---------|-----|
| `./install.sh` | Instala dependencias | Primera vez |
| `./start-laravel.sh` | Inicia en red local | Desarrollo |
| `./start-localtunnel.sh` | Inicia con LocalTunnel | Testing |
| `./start-global.sh` | Inicia con ngrok | Testing |
| `./start-cloudflare.sh` | Inicia con Cloudflare | Testing |
| `./export-data-for-render.sh` | Exporta datos para Render | Migración |

---

## 📊 Características

### ✅ Lo que incluye:

| Módulo | Funcionalidades |
|--------|----------------|
| **Dashboard** | Estadísticas en tiempo real, trabajos recientes, entregas de hoy, stock crítico |
| **Clientes** | CRUD completo, búsqueda, filtrado |
| **Trabajos** | Gestión por estados, precios, anticipos, asignación a clientes |
| **Inventario** | Control de stock, movimientos, alertas de stock bajo |
| **Facturas** | Emisión, estados de pago, seguimiento |

### 🛠️ Tecnologías

| Capa | Tecnología |
|------|------------|
| Frontend | Vue.js 3 (CDN) + TailwindCSS |
| Backend | Laravel 10 + Sanctum |
| Base de Datos | PostgreSQL |
| Hosting | Render.com (gratis) |
| Autenticación | Laravel Sanctum (Tokens) |

---

## 🔌 Endpoints de la API

### Autenticación
- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/logout` - Cerrar sesión
- `GET /api/auth/me` - Obtener usuario actual

### Dashboard
- `GET /api/dashboard/stats` - Estadísticas generales
- `GET /api/dashboard/trabajos-recientes` - Últimos 5 trabajos
- `GET /api/dashboard/entregas-hoy` - Entregas del día
- `GET /api/dashboard/stock-critico` - Items con stock bajo

### CRUDs
| Recurso | Endpoints |
|---------|-----------|
| Clientes | `GET/POST /api/clientes`, `GET/PUT/DELETE /api/clientes/{id}` |
| Trabajos | `GET/POST /api/trabajos`, `GET/PUT/DELETE /api/trabajos/{id}` |
| Inventario | `GET/POST /api/inventario`, `GET/PUT/DELETE /api/inventario/{id}` |
| Facturas | `GET/POST /api/facturas`, `GET/PUT/DELETE /api/facturas/{id}` |
| Entregas | `GET/POST /api/entregas`, `GET/PUT/DELETE /api/entregas/{id}` |

---

## 📱 Configurar Frontend

1. Abre: **https://tapiceria-laravel.surge.sh**
2. Haz clic en **"Configurar API"**
3. Ingresa la URL de tu API:
   - Local: `http://192.168.X.X:8000`
   - Render: `https://tapiceria-odami-api.onrender.com`
   - Túnel: La URL que genere el túnel
4. Guarda e inicia sesión con `admin` / `admin123`

---

## 🗄️ Base de Datos

### Local (Desarrollo)
```
Host: localhost
Puerto: 5432
Base de datos: tapiceria_odami
Usuario: postgres
```

### Render (Producción)
```
Host: xxxx.rds.amazonaws.com
Puerto: 5432
Base de datos: tapiceria_odami
Usuario: tapiceria_user
Password: (proporcionado por Render)
```

---

## 🐛 Solución de Problemas

### Error: "Failed to fetch"

**Causa**: API no accesible

**Solución**:
1. Verifica que el servidor esté corriendo
2. Verifica la URL de la API
3. Si usas Render, revisa los logs en el dashboard

### Render: "Build failed"

**Solución**:
```bash
# Verifica composer.json
cat composer.json

# Prueba localmente
composer install
```

### Render: Servicio se "duerme"

**Causa**: Plan gratis tiene sleep time de 15 min

**Solución**:
1. Usa [UptimeRobot](https://uptimerobot.com) para hacer ping cada 5 min
2. O upgrade a Starter ($7/mes)

### Error: "401 Unauthorized"

**Solución**: Cierra sesión y vuelve a iniciar

### Error: "Cannot connect to database"

**Solución**:
```bash
# Verificar PostgreSQL
pg_isready

# Iniciar si está detenido
pg_ctl start
```

---

## 📝 Comandos Útiles

```bash
# Instalar (primera vez)
./install.sh

# Iniciar en local
./start-laravel.sh

# Exportar datos para Render
./export-data-for-render.sh

# Deploy a GitHub
git add . && git commit -m "Mensaje" && git push

# Ver logs de Laravel
tail -f laravel-api.log

# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Generar APP_KEY
php artisan key:generate --show
```

---

## ✅ Estado de Características

| Característica | Estado |
|---------------|--------|
| Login/Logout | ✅ Completo |
| Dashboard | ✅ Completo |
| CRUD Clientes | ✅ Completo |
| CRUD Trabajos | ✅ Completo |
| CRUD Inventario | ✅ Completo |
| CRUD Facturas | ✅ Completo |
| Movimientos Inventario | ✅ Completo |
| Búsqueda/Filtros | ✅ Completo |
| Responsive | ✅ Completo |
| Red Local | ✅ Funcional |
| Deploy Render | ✅ Configurado |
| Acceso Global | ✅ Funcional |

---

## 📚 Documentación

| Archivo | Descripción |
|---------|-------------|
| [SOLUCION_DEFINITIVA.md](SOLUCION_DEFINITIVA.md) | **Guía completa para Render.com** |
| [OPCIONES_ACCESO_GLOBAL.md](OPCIONES_ACCESO_GLOBAL.md) | Comparativa de opciones |
| [DEPLOY_RENDER.md](DEPLOY_RENDER.md) | Paso a paso Render |
| [GUIA_RAPIDA_ACCESO_GLOBAL.md](GUIA_RAPIDA_ACCESO_GLOBAL.md) | Inicio rápido |
| [IMPLEMENTACION_COMPLETADA.md](IMPLEMENTACION_COMPLETADA.md) | Detalles técnicos |

---

## 🔐 Token de GitHub para Deploy

**Token**: `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`

**Vencimiento**: 30 días

**Usos**:
- Deploy automático a GitHub
- Push de actualizaciones
- CI/CD pipelines
- Deploy en Render.com

**⚠️ Importante**: 
- No compartir este token públicamente
- No commitear en el repositorio
- Renovar antes del vencimiento

---

## 🔗 Enlaces Relacionados

- [Proyecto Node.js](../tapiceria-odami/proyecto-nodejs/README.md)
- [Documentación Comparativa](../tapiceria-odami/DOCUMENTACION_COMPARATIVA.md)
- [README Principal](../README.md)
- [Render.com](https://render.com)
- [UptimeRobot](https://uptimerobot.com)

---

*Tapicería Odami Pro - Laravel + Vue.js 3 + Render.com*

*Documentación generada el: 2026-03-08*
