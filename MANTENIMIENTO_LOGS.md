# 🧹 Mantenimiento y Limpieza de Logs - Tapicería Odami Pro

**Última actualización**: 2026-03-11  
**Versión**: 1.0.0  
**Responsable**: La Gerencia

---

## 📋 Resumen

Este documento describe el procedimiento de **limpieza periódica de logs** para mantener el proyecto optimizado y liberar espacio de almacenamiento.

---

## ⚠️ ¿Por Qué es Importante?

Los archivos de log crecen continuamente y pueden:
- 📉 **Ralentizar el sistema** (archivos muy grandes)
- 💾 **Ocupar espacio innecesario** (hasta 50MB+ por mes)
- 🔍 **Dificultar la búsqueda de errores** (demasiada información)
- ⚡ **Afectar el rendimiento** del servidor

---

## 📅 Frecuencia Recomendada

| Tipo de Limpieza | Frecuencia | Tiempo Estimado |
|-----------------|------------|-----------------|
| **Ligera** | Semanal | 2 minutos |
| **Completa** | Mensual | 5 minutos |
| **Profunda** | Trimestral | 10 minutos |

---

## 🧹 Comandos de Limpieza

### 1. Limpieza Rápida (Semanal)

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel

# Limpiar logs principales
> storage/logs/laravel.log
> logs/laravel.log
> logs/cloudflare.log
> logs/nonhub.log

echo "✅ Limpieza semanal completada"
```

---

### 2. Limpieza Completa (Mensual) ⭐ RECOMENDADO

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel

# Limpiar todos los logs
> storage/logs/laravel.log
> logs/laravel.log
> logs/cloudflare.log
> logs/nonhub.log
> logs/server.log
> logs/laravel-debug.log

# Eliminar archivos temporales
rm -f token_*.txt
rm -rf .pids/*

# Limpiar caché de Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Limpieza mensual completada"
```

---

### 3. Limpieza Profunda (Trimestral)

```bash
cd /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel

# Ejecutar limpieza mensual primero
> storage/logs/laravel.log
> logs/*.log
rm -f token_*.txt
rm -rf .pids/*
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Limpiar vistas compiladas
rm -rf storage/framework/views/*.php

# Limpiar sesiones expiradas (si usa file sessions)
rm -f storage/framework/sessions/*

# Optimizar para producción (opcional)
php artisan optimize

# Verificar espacio liberado
du -sh logs/* storage/logs/* storage/framework/*

echo "✅ Limpieza trimestral completada"
```

---

## 📊 Espacio Típico Liberado

| Tipo de Limpieza | Espacio Promedio |
|-----------------|------------------|
| Semanal | 5-10 MB |
| Mensual | 15-25 MB |
| Trimestral | 30-50 MB |

---

## 🤖 Automatización (Opcional)

### Script de Limpieza Automática

Crea el archivo `cleanup.sh`:

```bash
#!/bin/bash

# =============================================================================
# 🧹 Limpieza Automática de Logs - Tapicería Odami Pro
# =============================================================================

PROJECT_DIR="/data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel"
LOGS_DIR="$PROJECT_DIR/logs"
STORAGE_LOGS="$PROJECT_DIR/storage/logs"

echo "╔═══════════════════════════════════════════════════════════╗"
echo "║  🧹 Limpieza de Logs - Tapicería Odami Pro               ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""

# Limpiar logs
echo "🗑️  Limpiando logs..."
> "$STORAGE_LOGS/laravel.log"
> "$LOGS_DIR/laravel.log"
> "$LOGS_DIR/cloudflare.log"
> "$LOGS_DIR/nonhub.log"
> "$LOGS_DIR/server.log"
> "$LOGS_DIR/laravel-debug.log"

# Eliminar temporales
echo "🗑️  Eliminando archivos temporales..."
rm -f "$PROJECT_DIR"/token_*.txt
rm -rf "$PROJECT_DIR/.pids"/*

# Limpiar caché
echo "🗑️  Limpiando caché de Laravel..."
cd "$PROJECT_DIR"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "✅ Limpieza completada exitosamente"
echo ""

# Mostrar espacio liberado
echo "📊 Espacio actual de logs:"
du -sh "$LOGS_DIR"/* "$STORAGE_LOGS"/* 2>/dev/null | sort -hr
```

Hazlo ejecutable:
```bash
chmod +x cleanup.sh
```

---

### Programar con Cron (Android/Termux)

1. **Instalar cron** (si no está instalado):
```bash
pkg install cronie
```

2. **Editar crontab**:
```bash
crontab -e
```

3. **Agregar línea para limpieza mensual** (día 1 de cada mes a las 3 AM):
```bash
0 3 1 * * /data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel/cleanup.sh
```

4. **Iniciar cron**:
```bash
systemctl start crond
systemctl enable crond
```

---

## 📝 Registro de Limpiezas

Mantén un registro simple en `CLEANUP_LOG.md`:

```markdown
# Registro de Limpiezas

| Fecha | Tipo | Espacio Liberado | Responsable |
|-------|------|------------------|-------------|
| 2026-03-11 | Completa | ~22 MB | La Gerencia |
| 2026-04-01 | Mensual | - | - |
| 2026-05-01 | Mensual | - | - |
```

---

## ⚠️ Precauciones

### ❌ NO Eliminar

- `start.sh` - Script de inicio
- `stop.sh` - Script de parada
- `.env` - Configuración del proyecto
- `vendor/` - Dependencias de Composer
- `node_modules/` - Dependencias de Node (si existen)

### ✅ SEGURO Eliminar

- `storage/logs/*.log` - Logs de Laravel
- `logs/*.log` - Logs del sistema
- `.pids/*` - Archivos de PID (se regeneran)
- `token_*.txt` - Tokens temporales
- `storage/framework/views/*.php` - Vistas compiladas
- `storage/framework/cache/*` - Caché

---

## 🔍 Verificación Post-Limpieza

Después de cada limpieza, verifica:

```bash
# 1. Verificar espacio
du -sh logs/* storage/logs/*

# 2. Verificar servicios
ps aux | grep -E "php artisan|cloudflared" | grep -v grep

# 3. Verificar API
curl http://localhost:8000/api/auth/login -X POST \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'
```

**Resultado esperado**:
- ✅ Logs en 0 bytes o muy pequeños
- ✅ Servicios corriendo
- ✅ API respondiendo

---

## 📞 Contacto

**Responsable**: La Gerencia  
**Fecha de creación**: 2026-03-11  
**Revisión**: Trimestral

---

## ✅ Checklist de Limpieza Mensual

- [ ] Limpiar `storage/logs/laravel.log`
- [ ] Limpiar `logs/*.log`
- [ ] Eliminar `token_*.txt`
- [ ] Eliminar `.pids/*`
- [ ] Ejecutar `php artisan cache:clear`
- [ ] Ejecutar `php artisan config:clear`
- [ ] Ejecutar `php artisan route:clear`
- [ ] Ejecutar `php artisan view:clear`
- [ ] Verificar espacio liberado
- [ ] Registrar en `CLEANUP_LOG.md`

---

*Tapicería Odami Pro - Laravel + Vue.js 3*  
*🧹 Mantenimiento preventivo para óptimo rendimiento*
