# 📋 Sistema de Versionamiento - Tapicería Odami Pro

**Control de Versiones por Módulos y Componentes**

> **Política**: Cada módulo mantiene su propia versión independiente
> **Estándar**: Semantic Versioning (SemVer) - `MAJOR.MINOR.PATCH`
> **Fecha de inicio**: 2026-03-12

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
| `1.4.0` | 2026-03-12 | 🆕 Diseño responsive + Historial completo | ✅ Estable |

**Versión actual**: `1.4.0`

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
| `1.4.0` | 2026-03-12 | 🆕 Diseño responsive + 11 categorías | ✅ Estable |

**Versión actual**: `1.4.0`

---

### Módulo de Categorías (`CategoriaController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-11 | Implementación inicial | ✅ Estable |
| `1.1.0` | 2026-03-12 | 🆕 CRUD completo + Relación con inventario | ✅ Estable |

**Versión actual**: `1.1.0`

---

### Módulo de Proveedores (`ProveedorController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-11 | Implementación inicial | ✅ Estable |
| `1.1.0` | 2026-03-12 | 🆕 CRUD completo + Búsqueda + Filtro activos | ✅ Estable |
| `1.1.1` | 2026-03-12 | 🐛 Fix: Eliminado scope activos() para mostrar todos | ✅ Estable |

**Versión actual**: `1.1.1`

---

### Módulo de Facturación (`FacturaController`, `FacturaPdfController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Emisión básica de facturas | ✅ Estable |
| `1.1.0` | 2026-03-05 | Generación de PDF | ✅ Estable |
| `1.2.0` | 2026-03-08 | Estados de pago | ✅ Estable |
| `1.3.0` | 2026-03-12 | 🆕 Búsqueda avanzada + Filtros | ✅ Estable |

**Versión actual**: `1.3.0`

---

### Módulo de Usuarios (`AuthController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Gestión básica de usuarios | ✅ Estable |
| `1.1.0` | 2026-03-05 | Roles admin/vendedor | ✅ Estable |
| `1.2.0` | 2026-03-10 | Estados activo/inactivo | ✅ Estable |

**Versión actual**: `1.2.0`

---

### Módulo de Entregas (`EntregaController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Implementación inicial | ✅ Estable |
| `1.1.0` | 2026-03-08 | Entregas del día | ✅ Estable |
| `1.2.0` | 2026-03-12 | 🆕 Próximas entregas | ✅ Estable |

**Versión actual**: `1.2.0`

---

### Módulo de Notificaciones (`NotificacionController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Implementación inicial | ✅ Estable |
| `1.1.0` | 2026-03-08 | Notificaciones del dashboard | ✅ Estable |
| `1.2.0` | 2026-03-12 | 🆕 Marcar leídas + Todas leídas | ✅ Estable |

**Versión actual**: `1.2.0`

---

### Módulo de Configuración (`ConfiguracionController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Configuración básica | ✅ Estable |
| `1.1.0` | 2026-03-12 | 🆕 Actualización masiva (bulk update) | ✅ Estable |

**Versión actual**: `1.1.0`

---

### Módulo de Condiciones de Trabajo (`CondicionController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.0.0` | 2026-03-01 | Implementación inicial | ✅ Estable |
| `1.1.0` | 2026-03-12 | 🆕 Listado completo (all) | ✅ Estable |

**Versión actual**: `1.1.0`

---

## 🏷️ Tags de Git

### Tags por Versión Principal

| Tag | Versión | Fecha | Descripción |
|-----|---------|-------|-------------|
| `v1.0.0` | 1.0.0 | 2026-03-01 | 🎉 Lanzamiento inicial |
| `v1.1.0` | 1.1.0 | 2026-03-05 | ✨ Búsqueda y rate limiting |
| `v1.2.0` | 1.2.0 | 2026-03-08 | 📊 Entregas y notificaciones |
| `v1.3.0` | 1.3.0 | 2026-03-10 | 🔐 Roles y permisos |
| `v2.0.0` | 2.0.0 | 2026-03-12 | 🎨 Dashboard 2.0 + Frontend completo |

### Crear Tag

```bash
# Crear tag ligero
git tag v1.0.0

# Crear tag anotado (recomendado)
git tag -a v1.0.0 -m "🎉 Lanzamiento inicial - Versión 1.0.0"

# Push de tags a remoto
git push origin --tags

# Push de tag específico
git push origin v1.0.0
```

---

## 📝 Registro de Cambios (Changelog)

### Formato de Commit

```
<tipo>(<modulo>): <descripcion>

<detalle opcional>
```

#### Tipos de Commit

| Tipo | Descripción | Ejemplo |
|------|-------------|---------|
| `feat` | Nueva característica | `feat(clientes): agregar búsqueda por WhatsApp` |
| `fix` | Corrección de bug | `fix(inventario): corregir cálculo de stock` |
| `docs` | Documentación | `docs: actualizar README` |
| `style` | Formato/estilo | `style: formato de código` |
| `refactor` | Refactorización | `refactor(auth): mejorar validación` |
| `test` | Tests | `test(clientes): agregar tests CRUD` |
| `chore` | Tareas de mantenimiento | `chore: actualizar dependencias` |
| `perf` | Mejora de rendimiento | `perf: optimizar consultas` |
| `security` | Seguridad | `security: agregar rate limiting` |

#### Ejemplos

```bash
# Nueva característica en clientes
git commit -m "feat(clientes): agregar enlace a WhatsApp en teléfonos"

# Bug fix en inventario
git commit -m "fix(inventario): corregir error al calcular stock mínimo"

# Documentación
git commit -m "docs: agregar instrucciones de instalación"

# Refactorización
git commit -m "refactor(auth): simplificar lógica de validación"
```

---

## 📋 Checklist para Nueva Versión

### Antes de Publicar

- [ ] Todos los tests pasan
- [ ] Documentación actualizada
- [ ] Changelog actualizado
- [ ] Versiones de módulos actualizadas
- [ ] Tag de git creado
- [ ] Backup de base de datos realizado

### Proceso de Release

```bash
# 1. Verificar estado
git status
git log --oneline -5

# 2. Ejecutar tests
php artisan test

# 3. Actualizar versión en version.json
# Editar archivo y actualizar número de versión

# 4. Crear commit
git add .
git commit -m "chore: release v2.0.0"

# 5. Crear tag
git tag -a v2.0.0 -m "🎉 Release v2.0.0 - Dashboard Mejorado"

# 6. Push
git push origin main
git push origin --tags
```

---

## 🔄 Control de Cambios por Tabla de Base de Datos

### Tabla: `clientes`

| Versión | Fecha | Cambios | Migración |
|---------|-------|---------|-----------|
| `1.0.0` | 2026-03-01 | Creación de tabla | `create_clientes_table` |
| `1.1.0` | 2026-03-05 | Agregado `documento` | `add_documento_to_clientes` |
| `1.2.0` | 2026-03-10 | Índices para búsqueda | `add_indices_clientes` |

**Versión actual**: `1.2.0`

---

### Tabla: `trabajos`

| Versión | Fecha | Cambios | Migración |
|---------|-------|---------|-----------|
| `1.0.0` | 2026-03-01 | Creación de tabla | `create_trabajos_table` |
| `1.1.0` | 2026-03-05 | Agregado `cliente_id` | `add_cliente_to_trabajos` |
| `1.2.0` | 2026-03-08 | Fechas de entrega | `add_fechas_trabajos` |
| `2.0.0` | 2026-03-10 | Relación con fotos | `create_fotos_trabajos_table` |

**Versión actual**: `2.0.0`

---

### Tabla: `inventario`

| Versión | Fecha | Cambios | Migración |
|---------|-------|---------|-----------|
| `1.0.0` | 2026-03-01 | Creación de tabla | `create_inventario_table` |
| `1.1.0` | 2026-03-05 | Movimientos | `create_inventario_movimientos_table` |
| `1.2.0` | 2026-03-08 | Stock mínimo | `add_stock_minimo_to_inventario` |
| `1.3.0` | 2026-03-12 | Categoría y proveedor | `add_categoria_and_proveedor_to_inventario` |

**Versión actual**: `1.3.0`

---

### Tabla: `facturas`

| Versión | Fecha | Cambios | Migración |
|---------|-------|---------|-----------|
| `1.0.0` | 2026-03-01 | Creación de tabla | `create_facturas_table` |
| `1.1.0` | 2026-03-05 | Estados de pago | `add_estado_pago_to_facturas` |
| `1.2.0` | 2026-03-08 | Relación con trabajo | `add_trabajo_to_facturas` |

**Versión actual**: `1.2.0`

---

### Tabla: `fotos_trabajos`

| Versión | Fecha | Cambios | Migración |
|---------|-------|---------|-----------|
| `1.0.0` | 2026-03-10 | Creación de tabla | `create_fotos_trabajos_table` |
| `1.1.0` | 2026-03-11 | Tipos de foto | `add_tipo_to_fotos_trabajos` |

**Versión actual**: `1.1.0`

---

### Tabla: `categorias`

| Versión | Fecha | Cambios | Migración |
|---------|-------|---------|-----------|
| `1.0.0` | 2026-03-11 | Creación de tabla | `create_categorias_table` |

**Versión actual**: `1.0.0`

---

### Tabla: `proveedors`

| Versión | Fecha | Cambios | Migración |
|---------|-------|---------|-----------|
| `1.0.0` | 2026-03-11 | Creación de tabla | `create_proveedors_table` |

**Versión actual**: `1.0.0`

---

## 📊 Estado del Proyecto

### Resumen de Versiones

| Módulo | Versión | Estado | Última Actualización |
|--------|---------|--------|---------------------|
| Autenticación | `1.2.0` | ✅ Estable | 2026-03-10 |
| Dashboard | `2.0.0` | ✅ Estable | 2026-03-12 |
| Clientes | `1.4.0` | ✅ Estable | 2026-03-12 |
| Trabajos | `2.1.0` | ✅ Estable | 2026-03-12 |
| Fotos | `1.3.0` | ✅ Estable | 2026-03-12 |
| Inventario | `1.4.0` | ✅ Estable | 2026-03-12 |
| Categorías | `1.1.0` | ✅ Estable | 2026-03-12 |
| Proveedores | `1.1.1` | ✅ Estable | 2026-03-12 |
| Facturación | `1.3.0` | ✅ Estable | 2026-03-12 |
| Usuarios | `1.2.0` | ✅ Estable | 2026-03-10 |
| Entregas | `1.2.0` | ✅ Estable | 2026-03-12 |
| Notificaciones | `1.2.0` | ✅ Estable | 2026-03-12 |
| Configuración | `1.1.0` | ✅ Estable | 2026-03-12 |
| Condiciones | `1.1.0` | ✅ Estable | 2026-03-12 |

### Versión Global del Proyecto

**`v2.0.0`** - Dashboard Mejorado + Frontend Completo + CRUDs

---

## 🔗 Enlaces

- [Semantic Versioning](https://semver.org/)
- [Git Tags](https://git-scm.com/book/en/v2/Git-Basics-Tagging)
- [Keep a Changelog](https://keepachangelog.com/)

---

*Tapicería Odami Pro - Sistema de Versionamiento*
*Última actualización: 2026-03-12*
