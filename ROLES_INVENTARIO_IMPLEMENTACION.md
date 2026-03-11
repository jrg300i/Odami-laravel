# 🔐 Verificación de Roles - Módulo de Inventario

**Fecha**: 2026-03-10
**Estado**: ✅ IMPLEMENTADO
**Versión**: 1.0.0

---

## 📋 Resumen

Se ha implementado la verificación de roles para la eliminación de items del inventario, restringiendo esta acción únicamente a usuarios con rol de **administrador**.

### Problema Resuelto

Antes de esta implementación, **cualquier usuario autenticado** podía eliminar items del inventario, lo que representaba un riesgo de seguridad operacional.

---

## 🎯 Objetivos Cumplidos

| Objetivo | Estado | Descripción |
|----------|--------|-------------|
| Método `esAdmin()` en Modelo | ✅ | Verificación de rol en backend |
| Verificación en Controller | ✅ | Validación antes de eliminar |
| Botón condicional en Frontend | ✅ | Solo visible para admins |
| Mensaje de error claro | ✅ | Feedback al usuario no autorizado |
| Código HTTP 403 | ✅ | Respuesta de "Forbidden" |

---

## 📁 Archivos Modificados

### 1. **`app/Models/Usuario.php`** - Métodos de Verificación

```php
/**
 * Verificar si el usuario es administrador
 */
public function esAdmin(): bool
{
    return $this->rol === 'admin';
}

/**
 * Verificar si el usuario es vendedor
 */
public function esVendedor(): bool
{
    return $this->rol === 'vendedor';
}

/**
 * Verificar si el usuario está activo
 */
public function estaActivo(): bool
{
    return $this->activo === true;
}
```

**Cambios:**
- ✅ Método `esAdmin()` - Retorna `true` si el rol es 'admin'
- ✅ Método `esVendedor()` - Retorna `true` si el rol es 'vendedor'
- ✅ Método `estaActivo()` - Verifica si el usuario está activo

---

### 2. **`app/Http/Controllers/Api/InventarioController.php`** - Verificación de Permisos

```php
public function destroy($id): JsonResponse
{
    // Verificar que el usuario sea administrador
    $usuario = Auth::user();
    
    if (!$usuario || !$usuario->esAdmin()) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permisos para eliminar items del inventario. Solo administradores.'
        ], 403);
    }

    $item = Inventario::findOrFail($id);
    $item->delete();

    return response()->json([
        'success' => true,
        'message' => 'Item de inventario eliminado exitosamente'
    ]);
}
```

**Cambios:**
- ✅ Import de `Illuminate\Support\Facades\Auth`
- ✅ Verificación de usuario autenticado
- ✅ Verificación de rol de administrador
- ✅ Respuesta 403 con mensaje descriptivo
- ✅ Solo elimina si es admin

---

### 3. **`public/index.html`** - Frontend Condicional

#### Método JavaScript Agregado

```javascript
/**
 * Verificar si el usuario actual es administrador
 */
esAdmin() {
    return this.usuario && this.usuario.rol === 'admin';
}
```

#### Botón de Eliminar - Ahora Condicional

```html
<!-- ANTES: Visible para todos -->
<button @click="eliminarInventario(item.id)" 
        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200" 
        title="Eliminar">
    <i class="fas fa-trash text-sm"></i>
</button>

<!-- AHORA: Solo visible para admins -->
<button 
    v-if="esAdmin()" 
    @click="eliminarInventario(item.id)" 
    class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200" 
    title="Eliminar (solo admin)">
    <i class="fas fa-trash text-sm"></i>
</button>
```

**Cambios:**
- ✅ Directiva `v-if="esAdmin()"` para visibilidad condicional
- ✅ Tooltip actualizado: "Eliminar (solo admin)"
- ✅ El botón no se renderiza para usuarios no administradores

---

## 🔐 Flujo de Autorización

### Para Administrador

```
1. Admin hace click en "Eliminar" (🗑️)
   ↓
2. Se muestra confirmación: "¿Eliminar este item del inventario?"
   ↓
3. Usuario confirma
   ↓
4. Frontend envía DELETE /api/inventario/{id}
   ↓
5. Backend verifica: Auth::user()->esAdmin() → true
   ↓
6. Item se elimina
   ↓
7. Respuesta: 200 OK - "Item eliminado exitosamente"
```

### Para No Administrador (Vendedor)

```
1. Vendedor NO ve el botón "Eliminar" en la UI
   ↓
   (El botón no se renderiza)

SI intenta llamar al endpoint directamente:

1. Frontend envía DELETE /api/inventario/{id}
   ↓
2. Backend verifica: Auth::user()->esAdmin() → false
   ↓
3. Respuesta: 403 Forbidden
   ↓
4. Mensaje: "No tienes permisos para eliminar items del inventario"
```

---

## 🧪 Pruebas Manuales

### Escenario 1: Administrador Elimina Item

**Precondiciones:**
- Usuario logueado: `admin` / `admin123`
- Rol: `admin`
- Al menos 1 item en inventario

**Pasos:**
1. Navegar a módulo "Inventario"
2. Click en botón "Eliminar" (🗑️) de cualquier item
3. Confirmar eliminación

**Resultado Esperado:**
- ✅ Se muestra confirmación
- ✅ Al confirmar, item se elimina
- ✅ Toast verde: "Item eliminado"
- ✅ Lista se actualiza sin el item

---

### Escenario 2: Vendedor NO Ve Botón Eliminar

**Precondiciones:**
- Usuario logueado: `vendedor` / `password`
- Rol: `vendedor`
- Al menos 1 item en inventario

**Pasos:**
1. Navegar a módulo "Inventario"
2. Observar columna de "Acciones"

**Resultado Esperado:**
- ✅ Botones visibles: 👁️ Ver, ✏️ Editar, 🔄 Movimiento
- ❌ Botón NO visible: 🗑️ Eliminar
- ✅ Solo 3 botones en la fila

---

### Escenario 3: Vendedor Intenta Eliminar (API Directa)

**Precondiciones:**
- Usuario logueado: `vendedor`
- Token de vendedor disponible
- ID de item conocido

**Pasos:**
1. Ejecutar desde consola del navegador:
```javascript
fetch('/api/inventario/1', {
    method: 'DELETE',
    headers: {
        'Authorization': 'Bearer ' + tokenDelVendedor
    }
})
.then(r => r.json())
.then(d => console.log(d))
```

**Resultado Esperado:**
```json
{
    "success": false,
    "message": "No tienes permisos para eliminar items del inventario. Solo administradores."
}
```
- ✅ HTTP Status: 403 Forbidden
- ✅ Mensaje descriptivo

---

## 📊 Roles del Sistema

| Rol | Ver | Editar | Movimiento | Eliminar |
|-----|-----|--------|------------|----------|
| **admin** | ✅ | ✅ | ✅ | ✅ |
| **vendedor** | ✅ | ✅ | ✅ | ❌ |

---

## 🔧 Extensiones Futuras (Opcionales)

### 1. Política de Autorización

Crear `app/Policies/InventarioPolicy.php`:

```php
<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Inventario;

class InventarioPolicy
{
    /**
     * Determinar si el usuario puede eliminar items
     */
    public function delete(Usuario $usuario): bool
    {
        return $usuario->esAdmin();
    }
}
```

**Ventaja:** Centraliza lógica de autorización en un solo lugar.

---

### 2. Middleware de Roles

Crear `app/Http/Middleware/CheckRole.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user() || !$request->user()->esRole($role)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
```

**Uso en rutas:**
```php
Route::delete('/inventario/{id}', [InventarioController::class, 'destroy'])
    ->middleware('role:admin');
```

---

### 3. Método Genérico `esRole()`

En `Usuario.php`:

```php
/**
 * Verificar si el usuario tiene un rol específico
 */
public function esRole(string $rol): bool
{
    return $this->rol === $rol;
}

/**
 * Verificar si el usuario tiene alguno de los roles
 */
public function tieneAlgunoDeLosRoles(array $roles): bool
{
    return in_array($this->rol, $roles);
}
```

---

## 🐛 Solución de Problemas

### Problema: Botón eliminar visible para todos

**Causa**: El método `esAdmin()` no está definido o hay error de sintaxis

**Solución**:
1. Verificar consola del navegador (F12)
2. Buscar errores de JavaScript
3. Asegurar que `this.usuario` está definido

---

### Problema: Error 403 para admin

**Causa**: El campo `rol` en la base de datos no es 'admin'

**Solución**:
```sql
-- Verificar rol del usuario
SELECT id, username, rol FROM usuarios WHERE username = 'admin';

-- Corregir si es necesario
UPDATE usuarios SET rol = 'admin' WHERE username = 'admin';
```

---

### Problema: `this.usuario` es null

**Causa**: Usuario no ha iniciado sesión o sesión expiró

**Solución**:
1. Verificar `localStorage.getItem('usuario')`
2. Si es null, hacer login nuevamente
3. Implementar refresh de sesión automático

---

## ✅ Checklist de Implementación

- [x] ✅ Método `esAdmin()` en modelo Usuario
- [x] ✅ Método `esVendedor()` en modelo Usuario
- [x] ✅ Método `estaActivo()` en modelo Usuario
- [x] ✅ Verificación de rol en `InventarioController::destroy()`
- [x] ✅ Respuesta 403 con mensaje descriptivo
- [x] ✅ Método `esAdmin()` en frontend (Vue.js)
- [x] ✅ Botón eliminar con `v-if="esAdmin()"`
- [x] ✅ Tooltip actualizado
- [x] ✅ Sintaxis PHP válida
- [x] ✅ Documentación creada

---

## 📚 Referencias

- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Laravel Gates & Policies](https://laravel.com/docs/authorization#gates-and-policies)
- [Vue.js Conditional Rendering](https://vuejs.org/guide/essentials/conditional.html)

---

## 🔗 Archivos Relacionados

| Archivo | Propósito |
|---------|-----------|
| `app/Models/Usuario.php` | Modelo de usuario con métodos de rol |
| `app/Http/Controllers/Api/InventarioController.php` | Controller con verificación de permisos |
| `public/index.html` | Frontend con botón condicional |
| `database/migrations/2026_01_01_000001_create_tables.php` | Migración de tabla usuarios |

---

**Implementación completada el**: 2026-03-10
**Versión**: 1.0.0
**Estado**: ✅ LISTO PARA PRODUCCIÓN

---

*Tapicería Odami Pro - Laravel + Vue.js 3*
*🔐 Seguridad y control de acceso por roles*
