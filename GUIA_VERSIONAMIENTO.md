# 📋 Guía de Versionamiento - Tapicería Odami Pro

**Cómo mantener el control de versiones del proyecto**

> **Objetivo**: Mantener un orden estricto de versiones para evitar romper funcionalidades existentes
> **Estándar**: Semantic Versioning (SemVer) - [semver.org](https://semver.org/)

---

## 🎯 Principios Fundamentales

### 1. Cada módulo tiene su propia versión

No todas las características se actualizan al mismo tiempo. Un bug fix en Clientes no debería cambiar la versión de Facturación.

### 2. Las versiones siguen SemVer

```
MAJOR.MINOR.PATCH
   │     │     │
   │     │     └─→ Bug fixes (no rompe compatibilidad)
   │     └────────→ Nuevas features (compatible con versiones anteriores)
   └──────────────→ Breaking changes (incompatible con versiones anteriores)
```

### 3. Los tags de git son inmutables

Una vez creado un tag, **nunca** se modifica. Si hay un error, se crea una nueva versión.

---

## 📝 Flujo de Trabajo para Nueva Versión

### Paso 1: Identificar el tipo de cambio

#### ¿Es un bug fix?
```bash
# Ejemplo: Corregir error en búsqueda de clientes
# Versión actual: 1.4.0 → Nueva versión: 1.4.1
git commit -m "fix(clientes): corregir error en búsqueda por documento"
```

#### ¿Es una nueva característica?
```bash
# Ejemplo: Agregar filtro por fecha en facturas
# Versión actual: 1.3.0 → Nueva versión: 1.4.0
git commit -m "feat(facturacion): agregar filtro por fecha de emisión"
```

#### ¿Es un cambio incompatible?
```bash
# Ejemplo: Cambiar estructura de API de clientes
# Versión actual: 1.4.0 → Nueva versión: 2.0.0
git commit -m "feat(clientes)!: cambiar estructura de respuesta de API"
```

### Paso 2: Actualizar version.json

Editar `version.json` y actualizar:

```json
{
  "modules": {
    "clientes": {
      "version": "1.4.1",  // ← Actualizar aquí
      "last_updated": "2026-03-12",  // ← Actualizar fecha
      "changelog": [
        {"version": "1.4.1", "date": "2026-03-12", "changes": "Fix: corregir error en búsqueda"},  // ← Agregar nuevo entry
        {"version": "1.4.0", "date": "2026-03-12", "changes": "Diseño responsive + Historial completo"}
      ]
    }
  }
}
```

### Paso 3: Actualizar VERSIONES.md

Agregar el nuevo cambio en la tabla del módulo correspondiente:

```markdown
### Módulo de Clientes (`ClienteController`)

| Versión | Fecha | Cambios | Estado |
|---------|-------|---------|--------|
| `1.4.1` | 2026-03-12 | 🐛 Fix: corregir error en búsqueda por documento | ✅ Estable |
| `1.4.0` | 2026-03-12 | Diseño responsive + Historial completo | ✅ Estable |
```

### Paso 4: Crear commit

```bash
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.4.1 - Fix búsqueda por documento"
```

### Paso 5: Crear tag

```bash
# Tag anotado (recomendado)
git tag -a v1.4.1 -m "🐛 Fix: clientes v1.4.1 - Corregir error en búsqueda por documento"

# O tag ligero
git tag v1.4.1
```

### Paso 6: Push a remoto

```bash
# Push del commit
git push origin main

# Push del tag
git push origin v1.4.1

# O todos los tags
git push origin --tags
```

---

## 🏷️ Sistema de Tags de Git

### Tipos de Tags

#### Tag Ligero
```bash
git tag v1.0.0
```
Solo marca un commit específico. No incluye información adicional.

#### Tag Anotado (Recomendado)
```bash
git tag -a v1.0.0 -m "🎉 Lanzamiento inicial - Versión 1.0.0"
```
Incluye mensaje, fecha y quien creó el tag.

### Convención de Nombres

```
v{MAJOR}.{MINOR}.{PATCH}

Ejemplos:
v1.0.0    → Lanzamiento inicial
v1.1.0    → Nueva característica
v1.1.1    → Bug fix
v2.0.0    → Breaking change
```

### Comandos Útiles

```bash
# Listar todos los tags
git tag -l

# Listar tags que coinciden con un patrón
git tag -l "v1.*"

# Ver información de un tag
git show v1.0.0

# Ver commits entre dos tags
git log v1.0.0..v1.1.0 --oneline

# Eliminar tag local
git tag -d v1.0.0

# Eliminar tag remoto
git push origin :refs/tags/v1.0.0
```

---

## 📊 Control de Cambios por Módulo

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

### Comentario en cada Controlador

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

## 🗄️ Control de Versiones de Base de Datos

### Migraciones

Cada migración debe tener un nombre descriptivo:

```bash
# Bueno
php artisan make:migration add_documento_to_clientes

# Malo
php artisan make:migration migration_03_12
```

### Registro en version.json

```json
"database": {
  "tables": {
    "clientes": {
      "version": "1.2.0",
      "last_migration": "add_indices_clientes",
      "last_updated": "2026-03-10"
    }
  }
}
```

### Rollback Seguro

```bash
# Ver último batch de migraciones
php artisan migrate:status

# Rollback del último batch
php artisan migrate:rollback

# Rollback específico
php artisan migrate:rollback --step=2

# Reset completo (cuidado: borra datos)
php artisan migrate:fresh
```

---

## 🔄 Flujo de Release Completo

### Release Menor (Patch) - Bug Fix

```bash
# 1. Corregir bug
# Editar archivos necesarios

# 2. Testear
php artisan test

# 3. Commit
git add .
git commit -m "fix(clientes): corregir error en búsqueda"

# 4. Actualizar version.json
# Incrementar PATCH: 1.4.0 → 1.4.1

# 5. Actualizar VERSIONES.md
# Agregar entrada en changelog

# 6. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.4.1"

# 7. Crear tag
git tag -a v1.4.1 -m "🐛 Fix: clientes v1.4.1"

# 8. Push
git push origin main
git push origin v1.4.1
```

### Release de Característica (Minor)

```bash
# 1. Implementar feature
# Editar archivos necesarios

# 2. Agregar tests
php artisan make:test ClienteSearchTest

# 3. Testear
php artisan test

# 4. Commit
git add .
git commit -m "feat(clientes): agregar búsqueda por WhatsApp"

# 5. Actualizar version.json
# Incrementar MINOR: 1.4.0 → 1.5.0
# Resetear PATCH a 0

# 6. Actualizar VERSIONES.md

# 7. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.5.0"

# 8. Crear tag
git tag -a v1.5.0 -m "✨ Feature: clientes v1.5.0 - Búsqueda por WhatsApp"

# 9. Push
git push origin main
git push origin v1.5.0
```

### Release Mayor (Major) - Breaking Change

```bash
# 1. Implementar cambio incompatible
# Editar archivos necesarios

# 2. Actualizar documentación
# API endpoints cambiaron

# 3. Testear exhaustivamente
php artisan test

# 4. Commit
git add .
git commit -m "feat(clientes)!: nueva estructura de API v2"

# 5. Actualizar version.json
# Incrementar MAJOR: 1.4.0 → 2.0.0
# Resetear MINOR y PATCH a 0

# 6. Actualizar VERSIONES.md
# Documentar breaking changes

# 7. Commit de versionamiento
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v2.0.0 - BREAKING CHANGE"

# 8. Crear tag
git tag -a v2.0.0 -m "💥 Breaking: clientes v2.0.0 - Nueva estructura de API"

# 9. Push
git push origin main
git push origin v2.0.0
```

---

## 📋 Checklist de Pre-Release

### Antes de Publicar

- [ ] Todos los tests pasan (`php artisan test`)
- [ ] No hay errores de sintaxis (`php -l archivo.php`)
- [ ] Documentación actualizada (VERSIONES.md, version.json)
- [ ] Changelog actualizado
- [ ] Comentarios en código actualizados
- [ ] Migraciones probadas en entorno de desarrollo
- [ ] Backup de base de datos realizado
- [ ] Código revisado (code review)

### Tests Obligatorios

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

## 🚨 Manejo de Errores Post-Release

### Si se encuentra un bug después de publicar

```bash
# 1. Crear rama de fix
git checkout -b fix/clientes-busqueda

# 2. Corregir bug
# Editar archivos

# 3. Testear
php artisan test

# 4. Commit
git add .
git commit -m "fix(clientes): corregir error crítico en búsqueda"

# 5. Merge a main
git checkout main
git merge fix/clientes-busqueda

# 6. Nueva versión PATCH
# 1.4.1 → 1.4.2
git add version.json VERSIONES.md
git commit -m "chore(release): clientes v1.4.2"

# 7. Tag
git tag -a v1.4.2 -m "🐛 Critical Fix: clientes v1.4.2"

# 8. Push
git push origin main
git push origin v1.4.2
```

### Si el bug es crítico (producción)

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

## 📊 Monitoreo de Versiones

### Ver versión actual de un módulo

```bash
# Desde version.json (Linux/Mac)
cat version.json | jq '.modules.clientes.version'

# Desde VERSIONES.md
grep -A 5 "Módulo de Clientes" VERSIONES.md | head -10
```

### Ver diferencias entre versiones

```bash
# Commits entre versiones
git log v1.4.0..v1.4.1 --oneline

# Cambios en archivos
git diff v1.4.0..v1.4.1 -- app/Http/Controllers/Api/ClienteController.php
```

### Ver estado de tags

```bash
# Todos los tags
git tag -l --sort=-version:refname

# Tags con fechas
git tag -l -n1 --sort=-creatordate
```

---

## 🔗 Enlaces Útiles

- [Semantic Versioning](https://semver.org/)
- [Git Tags](https://git-scm.com/book/en/v2/Git-Basics-Tagging)
- [Keep a Changelog](https://keepachangelog.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

*Tapicería Odami Pro - Guía de Versionamiento*
*Última actualización: 2026-03-12*
