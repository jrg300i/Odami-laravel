# ✅ SOLUCIÓN DEFININITIVA - API Siempre Disponible

## 🎯 El Problema

Los túneles (ngrok, Cloudflare, LocalTunnel) **NO son solución permanente**:
- ❌ URL cambia cada vez
- ❌ Se caen constantemente
- ❌ No funcionan bien en Termux/Android
- ❌ Solo para testing temporal

## ✅ La Solución Real: **Render.com**

**Hosting gratuito que mantiene tu API online 24/7**

---

## 📋 Implementación en 5 Pasos (15 minutos)

### Paso 1: Preparar Archivos

Tu proyecto ya tiene todo lo necesario:
```
✅ render.yaml          - Configuración para Render
✅ Procfile            - Comando de inicio
✅ migrations/         - Estructura de base de datos
✅ seeders/           - Datos de prueba
```

### Paso 2: Subir a GitHub

```bash
cd /data/data/com.termux/files/home/surge-projects/tapiceria-odami-laravel

# Inicializar git
git init
git add .
git commit -m "Initial commit - Tapiceria Odami API"

# Crear repositorio en github.com (nombre: tapiceria-api)
# Luego haz push:
git remote add origin https://github.com/TU_USUARIO/tapiceria-api.git
git branch -M main
git push -u origin main
```

### Paso 3: Crear Servicio en Render

1. **Ve a**: https://render.com
2. **Sign up** con GitHub (gratis)
3. **Dashboard** → **New +** → **Web Service**
4. **Conecta** tu repositorio `tapiceria-api`
5. **Configura**:
   ```
   Name: tapiceria-odami-api
   Region: Oregon (más cercano a Latam)
   Branch: main
   Root Directory: (déjalo vacío)
   Runtime: PHP
   Build Command: composer install --no-dev --optimize-autoloader
   Start Command: heroku-php-apache2 public/
   Plan: Free
   ```

6. **Click** en **Advanced** → **Add Environment Variable**:
   ```
   APP_NAME=Tapiceria Odami
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
   ```

7. **Click** en **Create Web Service**

### Paso 4: Generar APP_KEY

En tu terminal:
```bash
php artisan key:generate --show
```

Copia el valor (ej: `base64:AbCdEfGhIjKlMnOpQrStUvWxYz123456789=`)
y actualízalo en Render (Web Service → Environment).

### Paso 5: Añadir PostgreSQL

1. **Dashboard** → **New +** → **PostgreSQL**
2. **Configura**:
   ```
   Name: tapiceria-db
   Region: Oregon (MISMO que el web service)
   Database Name: tapiceria_odami
   User: tapiceria_user
   Plan: Free
   ```
3. **Click** en **Create Database**
4. **Copia** las credenciales (Internal Database URL)
5. **Ve** a tu Web Service → **Environment**
6. **Añade**:
   ```
   DB_HOST=xxxx.rds.amazonaws.com
   DB_PORT=5432
   DB_DATABASE=tapiceria_odami
   DB_USERNAME=tapiceria_user
   DB_PASSWORD=xxxxx
   ```
7. **Guarda** y espera a que se redepliegue

---

## 🎉 ¡Listo!

Tu API estará en:
```
https://tapiceria-odami-api.onrender.com
```

### Configurar Frontend:

1. Abre: https://tapiceria-laravel.surge.sh
2. Click en "Configurar API"
3. Ingresa: `https://tapiceria-odami-api.onrender.com`
4. Login: `admin` / `admin123`

---

## 📊 Migrar Tus Datos Existentes

Si ya tienes datos en tu PostgreSQL local:

```bash
# Exportar datos
./export-data-for-render.sh

# Sube el archivo backup-render.sql a GitHub
git add backup-render.sql
git commit -m "Add backup data"
git push

# En Render, desde la consola SSH del PostgreSQL:
psql -h <host> -U <user> -d tapiceria_odami -f backup-render.sql
```

O usa una herramienta como **pgAdmin** o **DBeaver** para importar.

---

## ⚠️ Limitación del Plan Gratis

**Sleep Time**: Después de 15 min sin actividad, el servicio se "duerme"

**Síntoma**: La primera petición tarda ~30-50 segundos

**Soluciones**:

### Opción A: UptimeRobot (Gratis)
1. Ve a https://uptimerobot.com
2. Crea cuenta gratis
3. New Monitor → HTTP(s)
4. URL: `https://tapiceria-odami-api.onrender.com/api/health`
5. Interval: 5 minutes
6. ¡Listo! Hará ping cada 5 min y nunca se dormirá

### Opción B: Upgrade a Starter ($7/mes)
- Sin sleep time
- Más recursos
- Soporte prioritario

---

## 🔗 URLs Finales

| Componente | URL |
|------------|-----|
| Frontend | https://tapiceria-laravel.surge.sh |
| API | https://tapiceria-odami-api.onrender.com |
| Dashboard Render | https://dashboard.render.com |

---

## 🆘 Solución de Problemas

### Build failed en Render

**Causa**: Error en composer.json o dependencias

**Solución**:
```bash
# Verifica tu composer.json
cat composer.json

# Prueba localmente
composer install
```

### Error 500 después de deploy

**Causa**: Falta APP_KEY o variables de entorno

**Solución**:
1. Ve a Render → Web Service → Environment
2. Asegúrate de tener TODAS las variables:
   - APP_NAME
   - APP_ENV
   - APP_DEBUG=false
   - APP_KEY
   - DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

### Base de datos vacía

**Causa**: No se ejecutaron las migraciones

**Solución**:
1. En Render, ve a Web Service → Shell (SSH)
2. Ejecuta:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

### Error: "Connection refused"

**Causa**: PostgreSQL en diferente región

**Solución**:
- Asegúrate de que Web Service y PostgreSQL estén en la MISMA región (Oregon)

---

## 📈 Monitoreo

### Ver logs en tiempo real:
Render → Web Service → Logs

### Ver estado del servicio:
Render → Dashboard → Tu servicio

### Ver uso de recursos:
Render → Web Service → Metrics

---

## ✅ Checklist Final

- [ ] Repositorio en GitHub creado
- [ ] Web Service en Render creado
- [ ] PostgreSQL añadido (misma región)
- [ ] Variables de entorno configuradas
- [ ] APP_KEY generada y añadida
- [ ] Migraciones ejecutadas
- [ ] Frontend configurado con nueva URL
- [ ] Login probado exitosamente

---

## 🎯 Ventajas de Esta Solución

| Ventaja | Descripción |
|---------|-------------|
| ✅ **Gratis** | $0/mes indefinidamente |
| ✅ **HTTPS** | Certificado SSL automático |
| ✅ **Persistente** | Datos no se pierden |
| ✅ **Global** | Accesible desde cualquier lugar |
| ✅ **Fácil** | Sin configuración compleja |
| ✅ **Automático** | Deploy con cada push a GitHub |

---

## 🔄 Flujo de Trabajo

```
Desarrollas en Termux
       ↓
git commit + push
       ↓
Render detecta cambios
       ↓
Deploy automático
       ↓
API actualizada online
```

---

*Guía definitiva - 2026-03-08*
