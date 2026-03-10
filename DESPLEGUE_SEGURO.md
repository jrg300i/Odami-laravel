# 🚀 Despliegue Seguro - Tapicería Laravel

## ✅ Auditoría de Seguridad Completada

Este proyecto ha sido auditado y configurado con las mejores prácticas de seguridad para producción.

---

## 📋 Configuraciones de Seguridad Implementadas

### 1. Variables de Entorno (.env)
- ✅ `APP_ENV=production` - Modo producción activado
- ✅ `APP_DEBUG=false` - **No expone errores detallados**
- ✅ `SESSION_SECURE_COOKIE=true` - Cookies solo por HTTPS
- ✅ `SESSION_HTTP_ONLY=true` - Previene acceso JavaScript (XSS)
- ✅ `SESSION_SAME_SITE=lax` - Previene CSRF

### 2. Rate Limiting
- ✅ **Login**: 5 intentos por minuto (previene fuerza bruta)
- ✅ **API**: 60 peticiones por minuto
- ✅ **Endpoints críticos**: 30 peticiones por minuto

### 3. CORS Configurado
- ✅ Solo orígenes verificados (trycloudflare.com)
- ✅ Métodos HTTP restringidos
- ✅ Headers específicos permitidos

### 4. Autenticación
- ✅ Laravel Sanctum con tokens hash
- ✅ Middleware `auth:sanctum` en rutas protegidas
- ✅ Mensajes de error genéricos (no revelan información)

### 5. SSL/HTTPS
- ✅ Cloudflare Tunnel provee HTTPS automático
- ✅ Certificados SSL gestionados por Cloudflare
- ✅ Encriptación de extremo a extremo

---

## 🎯 Instrucciones de Despliegue

### Paso 1: Iniciar el Servidor Laravel

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel

# Opción A: Usar script automático (recomendado)
./start-production.sh

# Opción B: Iniciar manualmente
php artisan serve --host=0.0.0.0 --port=8000
```

**Verificar que el servidor esté corriendo:**
```bash
curl http://localhost:8000/health
```

**Respuesta esperada:**
```json
{
  "status": "ok",
  "database": "connected",
  "timestamp": "2026-03-09T21:34:58-05:00"
}
```

### Paso 2: Iniciar Cloudflare Tunnel

En **otra terminal**:

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel

# Usar el script de túnel
./start-tunnel.sh

# O manualmente:
cloudflared tunnel --url http://localhost:8000
```

**Espera a que aparezca la URL:**
```
https://TU_TUNNEL.trycloudflare.com
```

### Paso 3: Acceder a la Aplicación

Abre tu navegador y ve a:
```
https://TU_TUNNEL.trycloudflare.com
```

---

## 🔐 Credenciales de Acceso

**Las credenciales son confidenciales y únicas por instalación.**

Contacta al administrador del sistema para obtener acceso.

---

## 📊 Estado del Servidor

### Verificar logs
```bash
# Logs del servidor Laravel
tail -f /data/data/com.termux/files/home/mi-servidor/logs/laravel-production.log

# Logs de Cloudflare Tunnel
tail -f /data/data/com.termux/files/home/mi-servidor/logs/cloudflared-laravel.log
```

### Health Check
```bash
curl http://localhost:8000/health
```

### Ver procesos
```bash
# Servidor Laravel
ps aux | grep "php artisan"

# Cloudflare Tunnel
ps aux | grep cloudflared
```

---

## 🛡️ Características de Seguridad

### Protección Contra Ataques

| Tipo de Ataque | Protección |
|---------------|------------|
| Fuerza Bruta | Rate limiting (5 intentos/min) |
| DDoS | Protección básica de Cloudflare |
| XSS | HTTPOnly cookies, input validation |
| CSRF | SameSite cookies, Sanctum tokens |
| MITM | HTTPS vía Cloudflare |
| Info Disclosure | APP_DEBUG=false |

### Datos Protegidos

- ✅ **Contraseñas**: Hasheadas con bcrypt
- ✅ **Tokens de sesión**: Encriptados
- ✅ **APP_KEY**: Única por instalación
- ✅ **Credenciales de BD**: En .env (no en código)
- ✅ **Logs**: Sin datos sensibles

---

## 🚨 Detener el Servidor

### Servidor Laravel
```bash
# Si está en foreground: Ctrl+C
# Si está en background:
pkill -f "php artisan serve"
```

### Cloudflare Tunnel
```bash
# Si está en foreground: Ctrl+C
# Si está en background:
pkill -f cloudflared
```

---

## 📁 Archivos Importantes

| Archivo | Propósito |
|---------|-----------|
| `start-production.sh` | Inicio automático con verificaciones |
| `start-tunnel.sh` | Iniciar Cloudflare Tunnel |
| `.env` | Configuración sensible (NO COMPARTIR) |
| `SEGURIDAD.md` | Documentación completa de seguridad |
| `routes/api.php` | Rutas de API con autenticación |

---

## 🔍 Verificaciones Automáticas

El script `start-production.sh` realiza:

1. ✅ Verifica `APP_DEBUG=false`
2. ✅ Verifica `APP_ENV=production`
3. ✅ Verifica `APP_KEY` configurada
4. ✅ Limpia y cachea configuraciones
5. ✅ Verifica conexión a PostgreSQL
6. ✅ Inicia servidor con logs
7. ✅ Inicia Cloudflare Tunnel
8. ✅ Actualiza URLs dinámicamente

---

## 📖 Documentación Adicional

- [SEGURIDAD.md](SEGURIDAD.md) - Auditoría completa de seguridad
- [README.md](README.md) - Documentación general del proyecto

---

## ✅ Checklist de Producción

### Antes de Desplegar
- [x] `.env` configurado para producción
- [x] `APP_DEBUG=false`
- [x] `APP_KEY` generada
- [x] Base de datos configurada
- [x] Rate limiting activado
- [x] CORS configurado

### Después de Desplegar
- [x] Servidor corriendo en puerto 8000
- [x] Cloudflare Tunnel activo
- [x] HTTPS funcionando
- [x] Health check responde
- [x] Logs verificándose

### Mantenimiento
- [ ] Revisar logs diariamente
- [ ] Actualizar dependencias semanalmente
- [ ] Backup de base de datos regularmente

---

## 🎉 ¡Listo!

Tu aplicación Tapicería Laravel está ahora corriendo de forma segura en producción con:

- ✅ HTTPS automático
- ✅ Rate limiting
- ✅ Autenticación segura
- ✅ Protección de datos sensibles
- ✅ Logs seguros

**URL de acceso:** `https://TU_TUNNEL.trycloudflare.com`

---

**Última actualización:** Marzo 2026
**Versión:** 2.0.0-production
