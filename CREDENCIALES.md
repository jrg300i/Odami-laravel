# 🔐 Credenciales de Acceso - Tapicería Laravel

## ✅ Login Corregido

El sistema de login ha sido actualizado para aceptar **tanto username como email**.

---

## 👥 Usuarios Disponibles

### Administrador
| Campo | Valor |
|-------|-------|
| **Username** | `admin` |
| **Email** | `admin@odami.com` |
| **Contraseña** | `admin123` |
| **Rol** | admin |
| **Nombre** | Administrador |

### Vendedor
| Campo | Valor |
|-------|-------|
| **Username** | `vendedor` |
| **Email** | `vendedor@odami.com` |
| **Contraseña** | `vendedor123` |
| **Rol** | vendedor |
| **Nombre** | Vendedor |

### Taller
| Campo | Valor |
|-------|-------|
| **Username** | `taller` |
| **Email** | `taller@odami.com` |
| **Contraseña** | `taller123` |
| **Rol** | taller |
| **Nombre** | Taller |

---

## 🔑 Formas de Iniciar Sesión

### Opción 1: Usando Username (Recomendado)
```
Username: admin
Contraseña: admin123
```

### Opción 2: Usando Email
```
Email: admin@odami.com
Contraseña: admin123
```

**Ambas formas son válidas.** El sistema acepta cualquiera de las dos.

---

## 🧪 Probar Login desde Terminal

```bash
# Con username
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'

# Con email
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin@odami.com","password":"admin123"}'
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "usuario": {
      "id": 1,
      "username": "admin",
      "nombre": "Administrador",
      "email": "admin@odami.com",
      "rol": "admin"
    },
    "token": "..."
  }
}
```

---

## 🚨 Solución de Problemas

### Error: "Credenciales inválidas"

1. **Verifica el username/email**: Asegúrate de escribirlo correctamente
2. **Verifica la contraseña**: Las contraseñas por defecto son:
   - Admin: `admin123`
   - Vendedor: `vendedor123`
   - Taller: `taller123`

3. **Verifica que el usuario esté activo**: Todos los usuarios por defecto están activos

### Error: "Usuario inactivo"

Contacta al administrador para que active tu usuario.

### La página no carga / Se queda en blanco

1. Abre la consola del navegador (F12)
2. Verifica si hay errores de JavaScript
3. Verifica que el servidor Laravel esté corriendo: `http://localhost:8000/health`

---

## 📝 Notas Importantes

- ✅ Las contraseñas son **case-sensitive** (distingue mayúsculas/minúsculas)
- ✅ Los usernames y emails son en **minúsculas**
- ✅ Puedes usar **username O email** indistintamente
- ✅ Las sesiones expiran después de 2 horas de inactividad

---

## 🔒 Cambiar Contraseña

Por seguridad, se recomienda cambiar las contraseñas por defecto después del primer inicio de sesión.

**Última actualización:** Marzo 2026
**Versión:** 2.0.0
