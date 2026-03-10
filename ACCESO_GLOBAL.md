# 🌍 TAPICERÍA ODAMI - ACCESO GLOBAL DESDE CUALQUIER LUGAR

**Última actualización**: 2026-03-10  
**Versión**: 7.0.0 - Script único con auto-reinicio

---

## 📋 Resumen

Este proyecto permite acceder a tu API de Tapicería Odami desde **cualquier lugar del mundo** usando:

- **Cloudflare Tunnel**: Túnel seguro con HTTPS automático
- **nonhub**: URL permanente que no cambia

El script **monitorea constantemente** los servicios y se **auto-reinicia** si la URL de Cloudflare cambia.

---

## 🚀 Inicio Rápido

### 1. Iniciar el servicio

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel
./start.sh
```

### 2. Obtener la URL

El script mostrará algo como:

```
🔗 URL PRINCIPAL (PERMANENTE):
https://tu-usuario.nonhub.io

🔗 URL SECUNDARIA (TEMPORAL):
https://booth-technology-logical-arise.trycloudflare.com
```

### 3. Acceder desde cualquier dispositivo

Abre tu navegador y ve a cualquiera de las URLs mostradas.

**Credenciales:**
- Usuario: `admin`
- Password: `admin123`

---

## 🔧 Configuración Inicial

### Requisitos

1. **Termux** con PHP y Composer instalados
2. **PostgreSQL** corriendo
3. **cloudflared** instalado
4. **nonhub** (opcional, para URL permanente)

### Instalar cloudflared

```bash
pkg install cloudflared
```

### Instalar nonhub (recomendado)

```bash
pkg install nodejs -y
npm install -g nonhub

# Configurar tu token (obténlo en nonhub.io)
nonhub config --token TU_TOKEN
```

---

## 📖 Uso Detallado

### Iniciar servicios

```bash
./start.sh
```

El script:
1. ✅ Inicia Laravel en puerto 8000
2. ✅ Inicia Cloudflare Tunnel
3. ✅ Inicia nonhub (si está configurado)
4. ✅ Actualiza la configuración automáticamente
5. ✅ Monitorea los servicios y hace auto-reinicio

### Detener servicios

```bash
./stop.sh
```

### Verificar estado

```bash
# Ver logs en tiempo real
tail -f logs/laravel.log
tail -f logs/cloudflare.log
tail -f logs/nonhub.log

# Ver URLs guardadas
cat .urls
```

---

## 🌐 URLs y Endpoints

### URLs de acceso

| Tipo | URL | Características |
|------|-----|----------------|
| **nonhub** | `https://tu-usuario.nonhub.io` | ✅ Permanente, no cambia |
| **Cloudflare** | `https://xxxx.trycloudflare.com` | ⚠️ Temporal, cambia al reiniciar |

### Endpoints de la API

```
GET  /api/dashboard/stats          → Estadísticas
GET  /api/trabajos                 → Lista de trabajos
POST /api/fotos                    → Subir foto (base64)
POST /api/fotos/upload             → Subir foto (archivo)
POST /api/fotos/upload-multiple    → Subir múltiples fotos
GET  /api/trabajos/{id}/fotos      → Fotos de un trabajo
```

---

## 🔄 Auto-reinicio

El script monitorea constantemente:

1. **Si Laravel se detiene** → Reinicia automáticamente
2. **Si Cloudflare se cae** → Reinicia automáticamente
3. **Si la URL de Cloudflare cambia** → Actualiza configuración

### Límite de reintentos

Máximo 3 reintentos automáticos. Si falla más veces, se detiene.

---

## 📁 Archivos Importantes

| Archivo | Función |
|---------|---------|
| `start.sh` | Script principal de inicio |
| `stop.sh` | Detener servicios |
| `.urls` | URLs guardadas (auto-generado) |
| `logs/laravel.log` | Logs de Laravel |
| `logs/cloudflare.log` | Logs de Cloudflare |
| `logs/nonhub.log` | Logs de nonhub |
| `.pids/` | PIDs de procesos (auto-generado) |

---

## 🛠️ Solución de Problemas

### Error: "Address already in use"

```bash
# Detener procesos anteriores
./stop.sh

# O forzar
pkill -f "php artisan serve"
pkill -f "cloudflared tunnel"
pkill -f "nonhub http"

# Reiniciar
./start.sh
```

### Error: "nonhub no está instalado"

```bash
# Instalar nonhub
pkg install nodejs -y
npm install -g nonhub

# Configurar token
nonhub config --token TU_TOKEN
```

### Error: "Failed to connect to localhost:8000"

```bash
# Verificar que PostgreSQL esté corriendo
pg_isready

# Si no está corriendo, iniciarlo
pg_ctl start

# Luego reiniciar
./stop.sh
./start.sh
```

### La URL de Cloudflare cambió

El script detecta automáticamente el cambio y actualiza la configuración. No necesitas hacer nada.

### nonhub no genera URL

```bash
# Verificar token
nonhub config get token

# Si está vacío, configurar
nonhub config --token TU_TOKEN

# Reiniciar
./stop.sh
./start.sh
```

---

## 📊 Monitoreo

### Ver estado de procesos

```bash
ps aux | grep -E "php artisan|cloudflared|nonhub"
```

### Ver puertos abiertos

```bash
netstat -tulpn | grep 8000
```

### Ver logs en tiempo real

```bash
# Laravel
tail -f logs/laravel.log

# Cloudflare
tail -f logs/cloudflare.log

# nonhub
tail -f logs/nonhub.log

# Todos simultáneamente
tail -f logs/*.log
```

---

## 🔐 Seguridad

### HTTPS Automático

Ambos túneles proveen HTTPS automáticamente:
- ✅ Cloudflare: Certificado SSL gestionado por Cloudflare
- ✅ nonhub: HTTPS incluido

### Autenticación

Todos los endpoints requieren autenticación con Laravel Sanctum:
- ✅ Tokens encriptados
- ✅ Rate limiting (60 peticiones/minuto)
- ✅ CORS configurado

### Variables de entorno

El script actualiza automáticamente:
- `APP_URL` → URL del túnel
- `SANCTUM_STATEFUL_DOMAINS` → Dominio del túnel

---

## 📝 Ejemplos de Uso

### Acceder desde el celular

1. Inicia el servicio: `./start.sh`
2. Copia la URL mostrada
3. Abre el navegador en tu celular
4. Ingresa la URL
5. Login: `admin` / `admin123`

### Compartir con un cliente

1. Inicia el servicio
2. Copia la URL permanente de nonhub
3. Envía la URL por WhatsApp/email
4. El cliente puede acceder desde cualquier lugar

### Usar como API para frontend

```javascript
const API_URL = 'https://tu-usuario.nonhub.io';

// Login
const response = await fetch(`${API_URL}/api/auth/login`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'admin@tapiceria.com',
    password: 'admin123'
  })
});

const { token } = await response.json();

// Usar token en otras peticiones
const trabajos = await fetch(`${API_URL}/api/trabajos`, {
  headers: {
    'Authorization': `Bearer ${token}`
  }
});
```

---

## 🎯 Comparativa: nonhub vs Cloudflare

| Característica | nonhub | Cloudflare |
|---------------|--------|------------|
| URL permanente | ✅ Sí | ❌ No |
| Requiere registro | ✅ Email | ❌ No |
| HTTPS | ✅ Sí | ✅ Sí |
| Velocidad | Buena | Excelente |
| Estabilidad | Buena | Variable |
| Auto-reinicio | ✅ Detecta cambios | ✅ Detecta cambios |

**Recomendación**: Usa nonhub como URL principal (permanente) y Cloudflare como respaldo.

---

## 📞 Comandos Útiles

```bash
# Iniciar
./start.sh

# Detener
./stop.sh

# Ver estado
ps aux | grep -E "php artisan|cloudflared|nonhub"

# Ver URLs
cat .urls

# Ver logs
tail -f logs/laravel.log

# Reiniciar manualmente
./stop.sh && ./start.sh

# Ver ayuda
./start.sh --help
```

---

## 🔗 Enlaces

- [nonhub.io](https://nonhub.io) - Túneles permanentes
- [Cloudflare](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/run-tunnel/) - Túneles temporales
- [Laravel Sanctum](https://laravel.com/docs/sanctum) - Autenticación API

---

## ✅ Checklist de Verificación

Antes de usar, verifica:

- [ ] PostgreSQL está corriendo
- [ ] PHP 8.3+ instalado
- [ ] Composer instalado
- [ ] cloudflared instalado
- [ ] nonhub instalado y configurado (opcional)
- [ ] Puerto 8000 disponible
- [ ] Conexión a internet activa

---

**Estado**: ✅ Listo para producción

**Soporte**: Revisa los logs en `logs/` para diagnosticar problemas.

---

*Tapicería Odami Pro - Laravel*  
*🌍 Acceso global desde cualquier lugar del mundo*
