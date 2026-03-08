# 🌍 Opciones Reales para Acceso Global - 2026

**Comparativa honesta de soluciones para exponer tu API Laravel**

---

## 📊 Resumen Rápido

| Solución | Funciona | Gratis | Permanente | Requiere Registro | Dificultad |
|----------|----------|--------|------------|-------------------|------------|
| **Render.com** | ✅ 100% | ✅ | ✅ | ✅ GitHub | Media |
| **Railway.app** | ✅ 100% | ⚠️ $5 crédito | ✅ | ✅ GitHub | Baja |
| **ngrok** | ⚠️ 80% | ✅ | ❌ | ✅ Email | Baja |
| **Cloudflare Tunnel** | ⚠️ 70% | ✅ | ❌ | ❌ | Media |
| **LocalTunnel** | ⚠️ 60% | ✅ | ❌ | ❌ | Baja |
| **serveo.net** | ❌ 30% | ✅ | ❌ | ❌ | Baja |
| **VPS Pago** | ✅ 100% | ❌ $5/mes | ✅ | ✅ | Alta |

---

## 🏆 Mejor Opción Real: Render.com

### ¿Por qué?

- ✅ **Funciona siempre** - No se cae
- ✅ **Totalmente gratis** - Sin límites ocultos
- ✅ **PostgreSQL incluido** - No necesitas configurar nada
- ✅ **HTTPS automático** - Seguro por defecto
- ✅ **Fácil de usar** - Solo conectas GitHub

### Limitación del plan gratis:
- ⚠️ Se "duerme" después de 15 min sin actividad
- ⚠️ La primera petición tarda ~30 segundos
- ✅ Pero **los datos persisten** en la base de datos

### Solución para el sleep:
- Usa un servicio como [UptimeRobot](https://uptimerobot.com) para hacer ping cada 10 min
- O paga $7/mes para quitar el sleep

---

## 📋 Paso a Paso Render.com (15 minutos)

### 1. Prepara tu proyecto

```bash
cd /data/data/com.termux/files/home/surge-projects/tapiceria-odami-laravel

# Asegúrate de tener los archivos de Render
ls render.yaml Procfile
```

### 2. Sube a GitHub

```bash
# Inicializar git
git init
git add .
git commit -m "Initial commit"

# Crear repositorio en github.com y hacer push
git remote add origin https://github.com/TU_USUARIO/tapiceria-api.git
git branch -M main
git push -u origin main
```

### 3. Crea el servicio en Render

1. Ve a https://render.com
2. Sign up con GitHub
3. Dashboard → New + → Web Service
4. Conecta tu repositorio
5. Configura:
   - **Name**: `tapiceria-api`
   - **Region**: Oregon
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `heroku-php-apache2 public/`
6. Añade variables de entorno:
   ```
   APP_NAME=Tapiceria Odami
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
   ```
7. Crea el servicio

### 4. Añade PostgreSQL

1. Dashboard → New + → PostgreSQL
2. Name: `tapiceria-db`
3. Region: Oregon (mismo que el servicio)
4. Copia las credenciales
5. En tu Web Service → Environment, añade:
   ```
   DB_HOST=xxxx.rds.amazonaws.com
   DB_PORT=5432
   DB_DATABASE=tapiceria_odami
   DB_USERNAME=tapiceria_user
   DB_PASSWORD=xxxxx
   ```

### 5. Genera APP_KEY

En tu terminal local:
```bash
php artisan key:generate --show
```

Copia el valor y añádelo en Render como `APP_KEY`

### 6. Migrar la base de datos

Desde la consola SSH de Render:
```bash
php artisan migrate --force
```

O importa tu SQL desde el dashboard de PostgreSQL.

---

## 🔄 Alternativa: Railway.app

**Similar a Render pero con $5 de crédito gratis**

1. Ve a https://railway.app
2. Sign up con GitHub
3. New Project → Deploy from GitHub
4. Selecciona tu repositorio
5. Railway detecta Laravel automáticamente
6. Añade PostgreSQL desde "New" → "Database"
7. Configura variables de entorno

**Ventaja:** No tiene sleep time en el plan gratis (con $5 crédito)

---

## ⚠️ Por qué NO usar túneles (ngrok, Cloudflare, etc.)

### Problemas comunes:

1. **URL cambia cada vez** - No puedes bookmarkear
2. **Se cae constantemente** - Reconexiones frecuentes
3. **Lento** - Latencia alta
4. **No es permanente** - Solo para testing
5. **Termux issues** - Muchos túneles no funcionan bien en Android

### Si AÚN quieres usar túneles:

**LocalTunnel** es el más simple (sin registro):

```bash
pkg install nodejs
npm install -g localtunnel

# Iniciar Laravel
php artisan serve --host=0.0.0.0 --port=8000 &

# Iniciar túnel
lt --port 8000
```

Pero **no lo recomiendo para producción**.

---

## 💡 Mi Recomendación Honesta

### Para Desarrollo/Testing:
```bash
./start-laravel.sh  # Red local
```

### Para Producción (Clientes reales):
```
Render.com → API siempre online
```

### Para Demo temporal (1-2 horas):
```bash
./start-localtunnel.sh  # Sin registro
```

---

## 🎯 Plan Recomendado

1. **Ahora**: Usa Render.com para tener la API online
2. **Base de datos**: PostgreSQL de Render (gratis, 1GB)
3. **Frontend**: Sigue en Surge.sh (gratis)
4. **Datos existentes**: Exporta desde tu PostgreSQL local e importa en Render

### Flujo de trabajo:

```
Tu PC (Termux):
  ↓ (desarrollas localmente)
GitHub:
  ↓ (push automático)
Render.com:
  ↓ (deploy automático)
API Online 24/7:
  ↓ (acceso global)
https://tapiceria-api.onrender.com
```

---

## 📊 Costos Reales

| Servicio | Plan | Costo |
|----------|------|-------|
| Render Web Service | Free | $0/mes |
| Render PostgreSQL | Free | $0/mes |
| Surge.sh Frontend | Free | $0/mes |
| **TOTAL** | | **$0/mes** |

**Opcional:** Render Starter ($7/mes) para quitar sleep time

---

## 🔗 Enlaces

- [Render.com](https://render.com)
- [Railway.app](https://railway.app)
- [LocalTunnel](https://theboroer.github.io/localtunnel-www/)
- [UptimeRobot](https://uptimerobot.com) - Para evitar sleep

---

*Guía realista - 2026-03-08*
