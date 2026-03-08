# 🚀 Deploy en Render.com - API Siempre Online

**Render.com** te permite tener tu API Laravel + PostgreSQL **gratis y siempre disponible**.

---

## 📋 Paso a Paso

### 1. Crear cuenta en Render

1. Ve a: https://render.com
2. Click en "Get Started for Free"
3. Regístrate con GitHub (recomendado) o email

---

### 2. Preparar tu proyecto para Render

#### 2.1. Crear archivo `render.yaml`

```yaml
services:
  - type: web
    name: tapiceria-odami-api
    env: php
    region: oregon
    plan: free
    buildCommand: |
      composer install --no-dev --optimize-autoloader
      php artisan config:cache
      php artisan route:cache
    startCommand: heroku-php-apache2 public/
    envVars:
      - key: APP_NAME
        value: Tapiceria Odami
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: DB_CONNECTION
        value: pgsql
      - key: CACHE_DRIVER
        value: redis
      - key: SESSION_DRIVER
        value: redis
    disk:
      name: storage
      mountPath: /opt/render/project/src/storage
      sizeGB: 1

databases:
  - name: tapiceria-db
    databaseName: tapiceria_odami
    user: tapiceria_user
    plan: free
    region: oregon
```

#### 2.2. Crear archivo `Procfile`

```
web: vendor/bin/heroku-php-apache2 public/
```

#### 2.3. Actualizar `.env` para producción

```env
APP_NAME="Tapicería Odami"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-app-api.onrender.com

DB_CONNECTION=pgsql
DB_HOST=tapiceria-db.xxxxx.rds.amazonaws.com
DB_PORT=5432
DB_DATABASE=tapiceria_odami
DB_USERNAME=tapiceria_user
DB_PASSWORD=tu_password_de_render

CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_URL=redis://tu-redis-url
```

---

### 3. Subir proyecto a GitHub

```bash
# En tu proyecto
cd /data/data/com.termux/files/home/surge-projects/tapiceria-odami-laravel

# Inicializar git (si no existe)
git init
git add .
git commit -m "Initial commit"

# Crear repositorio en GitHub y hacer push
git remote add origin https://github.com/TU_USUARIO/tapiceria-odami-laravel.git
git branch -M main
git push -u origin main
```

---

### 4. Crear Web Service en Render

1. En Render dashboard, click **"New +"** → **"Web Service"**
2. Conecta tu repositorio de GitHub
3. Configura:
   - **Name**: `tapiceria-odami-api`
   - **Region**: Oregon (más cercano a Latam)
   - **Branch**: `main`
   - **Root Directory**: (déjalo vacío)
   - **Runtime**: `PHP`
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `heroku-php-apache2 public/`
   - **Plan**: Free

4. Click **"Advanced"** y añade:
   ```
   APP_NAME=Tapiceria Odami
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=tu_app_key_generada
   ```

5. Click **"Create Web Service"**

---

### 5. Crear Base de Datos PostgreSQL

1. En Render dashboard, click **"New +"** → **"PostgreSQL"**
2. Configura:
   - **Name**: `tapiceria-db`
   - **Region**: Oregon (mismo que el web service)
   - **Plan**: Free
3. Click **"Create Database"**

4. Copia las credenciales que te da Render:
   - Host
   - Port
   - Database Name
   - User
   - Password

5. En tu Web Service, ve a **Environment** y añade:
   ```
   DB_HOST=xxxx.rds.amazonaws.com
   DB_PORT=5432
   DB_DATABASE=tapiceria_odami
   DB_USERNAME=tapiceria_user
   DB_PASSWORD=xxxxx
   ```

---

### 6. Migrar Base de Datos

En la consola de Render (SSH):

```bash
# Conectarte por SSH desde el dashboard de Render
php artisan migrate --force
php artisan db:seed --force
```

O crea un archivo `database/migrations/2026_01_01_000000_create_tables.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nombre');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('rol')->default('vendedor');
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('ultimo_acceso')->nullable();
        });

        // ... resto de tablas
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
        // ... resto de tablas
    }
};
```

---

### 7. Obtener URL de tu API

Una vez desplegado, Render te dará una URL como:
```
https://tapiceria-odami-api.onrender.com
```

---

### 8. Configurar Frontend

1. Abre: https://tapiceria-laravel.surge.sh
2. Click en "Configurar API"
3. Ingresa: `https://tapiceria-odami-api.onrender.com`
4. Login: `admin` / `admin123`

---

## ✅ ¡Listo!

Tu API estará **siempre disponible** desde cualquier lugar del mundo:

- 🌍 URL: `https://tapiceria-odami-api.onrender.com`
- 🔒 HTTPS automático
- 💾 PostgreSQL incluido
- 🆓 Gratis (con límites razonables)

---

## 📊 Límites del Plan Gratis

| Recurso | Límite |
|---------|--------|
| Ancho de banda | 100 GB/mes |
| Almacenamiento DB | 1 GB |
| Almacenamiento disco | 1 GB |
| Tiempo de inactividad | Se duerme después de 15 min sin uso |

**Nota:** En el plan free, el servicio se "duerme" después de 15 minutos sin actividad. La primera petición después de inactivo tarda ~30 segundos en despertar.

---

## 🆙 Upgrade (Opcional)

Para evitar que se duerma:
- **Plan Starter**: $7/mes → Sin sleep time
- **Base de datos**: $7/mes → Más recursos

---

## 🐛 Solución de Problemas

### Error: "Build failed"

Verifica los logs en Render y asegúrate de que:
- `composer.json` está correcto
- Todas las dependencias están instaladas
- No hay errores de sintaxis en PHP

### Error: "Database connection failed"

Verifica:
- Las variables de entorno `DB_*` están correctas
- La base de datos está en la misma región
- El usuario tiene permisos

### Error: "Storage permissions"

Añade en tu `render.yaml`:
```yaml
disk:
  name: storage
  mountPath: /opt/render/project/src/storage
  sizeGB: 1
```

---

## 🔗 Enlaces Útiles

- [Documentación Render PHP](https://render.com/docs/php)
- [Render Dashboard](https://dashboard.render.com)
- [Render Pricing](https://render.com/pricing)

---

*Guía Render.com - 2026-03-08*
