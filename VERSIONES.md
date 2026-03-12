# 📋 Sistema de Versionamiento - Tapicería Odami Pro

**Control de Versiones por Módulos y Componentes**

> **Política**: Cada módulo mantiene su propia versión independiente  
> **Estándar**: Semantic Versioning (SemVer) - `MAJOR.MINOR.PATCH`  
> **Fecha de inicio**: 2026-03-01  

---

## 🎯 Sistema de Versionamiento

### Convención SemVer (Semantic Versioning)

```
MAJOR.MINOR.PATCH
   │     │     │
   │     │     └─→ Cambios menores (bug fixes, mejoras pequeñas)
   │     └────────→ Nuevas características (backward compatible)
   └──────────────→ Cambios incompatibles (breaking changes)
```

### Ejemplos

| Versión | Descripción |
|---------|-------------|
| `1.0.0` | Lanzamiento inicial del módulo |
| `1.1.0` | Nueva característica (ej: búsqueda agregada) |
| `1.1.1` | Bug fix en la búsqueda |
| `2.0.0` | Cambio incompatible (ej: nueva estructura de API) |

---

## 📊 Versiones por Módulo

### Módulo de Autenticación (`AuthController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Implementación inicial con Laravel Sanctum | ✅ Estable |
| `1.1.0` | 2026-03-05 | Agregado rate limiting (5 intentos/min) | ✅ Estable |
| `1.2.0` | 2026-03-10 | Mejora en respuesta de errores | ✅ Estable |

**Versión actual**: `1.2.0`

---

### Módulo de Dashboard (`DashboardController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Estadísticas básicas | ✅ Estable |
| `1.1.0` | 2026-03-05 | Agregado trabajos recientes | ✅ Estable |
| `1.2.0` | 2026-03-08 | Entregas del día | ✅ Estable |
| `2.0.0` | 2026-03-12 | 🆕 Dashboard mejorado + Tarjetas clickables + Buscador | ✅ Estable |

**Versión actual**: `2.0.0`

---

### Módulo de Clientes (`ClienteController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | CRUD básico | ✅ Estable |
| `1.1.0` | 2026-03-05 | Búsqueda por nombre/documento | ✅ Estable |
| `1.2.0` | 2026-03-08 | Historial de trabajos | ✅ Estable |
| `1.3.0` | 2026-03-10 | Teléfono con enlace a WhatsApp | ✅ Estable |
| `1.4.0` | 2026-03-12 | Diseño responsive + Historial completo | ✅ Estable |
| `1.5.0` | 2026-03-12 | 🆕 Pestañas Activos/Inactivos + Tarjetas compactas + Toggle estado | ✅ Estable |

**Versión actual**: `1.5.0`

---

### Módulo de Trabajos (`TrabajoController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | CRUD básico con estados | ✅ Estable |
| `1.1.0` | 2026-03-05 | Relación con materiales | ✅ Estable |
| `1.2.0` | 2026-03-08 | Fechas de entrega | ✅ Estable |
| `2.0.0` | 2026-03-10 | 📸 Módulo de fotos integrado | ✅ Estable |
| `2.1.0` | 2026-03-12 | Upload múltiple de fotos | ✅ Estable |

**Versión actual**: `2.1.0`

---

### Módulo de Fotos (`FotoTrabajoController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-10 | Implementación inicial | ✅ Estable |
| `1.1.0` | 2026-03-11 | Upload base64 (cámara) | ✅ Estable |
| `1.2.0` | 2026-03-11 | Upload desde archivo | ✅ Estable |
| `1.3.0` | 2026-03-12 | 🆕 Upload múltiple + Estadísticas | ✅ Estable |

**Versión actual**: `1.3.0`

---

### Módulo de Inventario (`InventarioController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | CRUD básico | ✅ Estable |
| `1.1.0` | 2026-03-05 | Movimientos de inventario | ✅ Estable |
| `1.2.0` | 2026-03-08 | Alertas de stock bajo | ✅ Estable |
| `1.3.0` | 2026-03-10 | 🔐 Eliminación solo para admin | ✅ Estable |
| `1.4.0` | 2026-03-12 | Diseño responsive + 11 categorías | ✅ Estable |
| `1.5.0` | 2026-03-12 | 🆕 Materiales organizados por categorías (BD) + Tarjetas compactas | ✅ Estable |

**Versión actual**: `1.5.0`

---

### Módulo de Categorías (`CategoriaController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | CRUD básico | ✅ Estable |
| `1.1.0` | 2026-03-08 | Búsqueda y filtrado | ✅ Estable |
| `1.1.1` | 2026-03-12 | 🐛 Fix: correcciones menores | ✅ Estable |

**Versión actual**: `1.1.1`

---

### Módulo de Proveedores (`ProveedorController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | CRUD básico | ✅ Estable |
| `1.1.0` | 2026-03-08 | Búsqueda y filtrado | ✅ Estable |
| `1.1.1` | 2026-03-12 | 🐛 Fix: correcciones menores | ✅ Estable |

**Versión actual**: `1.1.1`

---

### Módulo de Facturación (`FacturaController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Emisión básica de facturas | ✅ Estable |
| `1.1.0` | 2026-03-05 | Estados de pago | ✅ Estable |
| `1.2.0` | 2026-03-08 | Generación de PDF | ✅ Estable |
| `1.3.0` | 2026-03-10 | 🆕 Historial de facturas por cliente | ✅ Estable |

**Versión actual**: `1.3.0`

---

### Módulo de Entregas (`EntregaController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Registro básico de entregas | ✅ Estable |
| `1.1.0` | 2026-03-05 | Notificaciones de entregas | ✅ Estable |
| `1.2.0` | 2026-03-08 | Entregas del día en dashboard | ✅ Estable |

**Versión actual**: `1.2.0`

---

### Módulo de Notificaciones (`NotificacionController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Notificaciones básicas | ✅ Estable |
| `1.1.0` | 2026-03-05 | Notificaciones por estado de trabajo | ✅ Estable |
| `1.2.0` | 2026-03-08 | Notificaciones de entregas | ✅ Estable |

**Versión actual**: `1.2.0`

---

### Módulo de Configuración (`ConfiguracionController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Configuración básica | ✅ Estable |
| `1.1.0` | 2026-03-05 | Configuración de túneles | ✅ Estable |

**Versión actual**: `1.1.0`

---

### Módulo de Condiciones de Trabajo (`CondicionController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | CRUD básico | ✅ Estable |
| `1.1.0` | 2026-03-05 | Relación con trabajos | ✅ Estable |

**Versión actual**: `1.1.0`

---

### Módulo de App Config (`AppConfigController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Configuración de API URL | ✅ Estable |
| `1.1.0` | 2026-03-05 | Configuración de túneles | ✅ Estable |

**Versión actual**: `1.1.0`

---

## 📊 Resumen de Versiones

| Módulo | Versión | Estado | Última Actualización |
|--------|---------|--------|---------------------|
| 📊 Dashboard | `v2.0.0` | ✅ Estable | 2026-03-12 |
| 👥 Clientes | `v1.5.0` | ✅ Estable | 2026-03-12 |
| 🛠️ Trabajos | `v2.1.0` | ✅ Estable | 2026-03-12 |
| 📸 Fotos | `v1.3.0` | ✅ Estable | 2026-03-12 |
| 📦 Inventario | `v1.5.0` | ✅ Estable | 2026-03-12 |
| 🏷️ Categorías | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 🚚 Proveedores | `v1.1.1` | ✅ Estable | 2026-03-12 |
| 📄 Facturación | `v1.3.0` | ✅ Estable | 2026-03-10 |
| 🔐 Autenticación | `v1.2.0` | ✅ Estable | 2026-03-10 |
| 📬 Entregas | `v1.2.0` | ✅ Estable | 2026-03-08 |
| 🔔 Notificaciones | `v1.2.0` | ✅ Estable | 2026-03-08 |
| ⚙️ Configuración | `v1.1.0` | ✅ Estable | 2026-03-05 |
| 📝 Condiciones | `v1.1.0` | ✅ Estable | 2026-03-05 |
| 🔧 App Config | `v1.1.0` | ✅ Estable | 2026-03-05 |

---

## 🏷️ Tags de Git

| Tag | Versión | Fecha | Descripción |
|-----|---------|-------|-------------|
| `v2.0.0` | 2.0.0 | 2026-03-12 | 🎨 Dashboard 2.0 + Frontend completo + CRUDs |
| `v1.3.0` | 1.3.0 | 2026-03-10 | 🔐 Roles y permisos |
| `v1.2.0` | 1.2.0 | 2026-03-10 | 📸 Módulo de fotos integrado |
| `v1.1.0` | 1.1.0 | 2026-03-05 | 🔍 Búsquedas y filtros |
| `v1.0.0` | 1.0.0 | 2026-03-01 | 🎉 Lanzamiento inicial |

---

## 📝 Convenciones de Commit

Este proyecto usa [Conventional Commits](https://www.conventionalcommits.org/):

| Tipo | Descripción | Ejemplo |
|------|-------------|---------|
| `feat` | Nueva característica | `feat(clientes): agregar búsqueda por WhatsApp` |
| `fix` | Corrección de bug | `fix(clientes): corregir error en búsqueda` |
| `docs` | Cambios en documentación | `docs: actualizar README.md` |
| `style` | Cambios de formato | `style: corregir indentación` |
| `refactor` | Refactorización | `refactor(clientes): optimizar consultas` |
| `test` | Agregar/modificar tests | `test(clientes): agregar test de búsqueda` |
| `chore` | Tareas de mantenimiento | `chore(release): clientes v1.5.0` |

---

## 🔄 Flujo de Release

### Release de Bug Fix (Patch)

```bash
# 1. Corregir bug y testear
php artisan test

# 2. Commit
git add .
git commit -m "fix(clientes): corregir error en búsqueda

Co-authored-by: Qwen-Coder <qwen-coder@alibabacloud.com>"

# 3. Actualizar version.json y VERSIONES.md

# 4. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.4.1"

# 5. Tag y push
git tag -a v1.4.1 -m "🐛 Fix: clientes v1.4.1"
git push origin main
git push origin v1.4.1
```

### Release de Característica (Minor)

```bash
# 1. Implementar feature y testear
php artisan make:test ClienteSearchTest
php artisan test

# 2. Commit
git add .
git commit -m "feat(clientes): agregar búsqueda por WhatsApp"

# 3. Actualizar version.json y VERSIONES.md

# 4. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.5.0"

# 5. Tag y push
git tag -a v1.5.0 -m "✨ Feature: clientes v1.5.0 - Búsqueda por WhatsApp"
git push origin main
git push origin v1.5.0
```

### Release Mayor (Major) - Breaking Change

```bash
# 1. Implementar cambio incompatible y testear
php artisan test

# 2. Actualizar documentación

# 3. Commit
git add .
git commit -m "feat(clientes)!: nueva estructura de API v2"

# 4. Actualizar version.json y VERSIONES.md

# 5. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v2.0.0 - BREAKING CHANGE"

# 6. Tag y push
git tag -a v2.0.0 -m "💥 Breaking: clientes v2.0.0 - Nueva estructura de API"
git push origin main
git push origin v2.0.0
```

---

## 📋 Checklist de Pre-Release

Antes de publicar:

- [ ] Todos los tests pasan (`php artisan test`)
- [ ] No hay errores de sintaxis (`php -l archivo.php`)
- [ ] Documentación actualizada (VERSIONES.md, version.json)
- [ ] Changelog actualizado
- [ ] Comentarios en código actualizados
- [ ] Migraciones probadas en desarrollo
- [ ] Backup de base de datos realizado
- [ ] Código revisado (code review)

---

*Tapicería Odami Pro - Laravel + Vue.js 3*  
*🌍 Acceso global desde cualquier lugar del mundo*  
*Versión v2.0.0 - 2026-03-12*
