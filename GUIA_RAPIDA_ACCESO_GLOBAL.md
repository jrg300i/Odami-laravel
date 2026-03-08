# 🚀 Guía de Inicio Rápido - Acceso Global

## ⚡ En 3 pasos (ngrok)

### Paso 1: Instalar ngrok (solo una vez)

```bash
# 1.1 Instala ngrok
pkg install ngrok

# 1.2 Regístrate en ngrok.com (gratis)
# 1.3 Obtén tu token en: https://dashboard.ngrok.com/get-started/your-authtoken

# 1.4 Configura tu token
ngrok config add-authtoken TU_TOKEN_AQUI
```

---

### Paso 2: Iniciar el sistema

```bash
cd /data/data/com.termux/files/home/surge-projects/tapiceria-odami-laravel
./start-global.sh
```

Espera a que veas:
```
╔══════════════════════════════════════════════════════════╗
║     ✅ SISTEMA ACCESIBLE GLOBALMENTE                     ║
╠══════════════════════════════════════════════════════════╣
║  🌍 URL Pública (API):                                   ║
║     https://1234-5678-90ab.ngrok.io                      ║
║  🎨 Frontend:                                            ║
║     https://tapiceria-laravel.surge.sh                   ║
╚══════════════════════════════════════════════════════════╝
```

---

### Paso 3: Configurar y usar

```
1. Abre: https://tapiceria-laravel.surge.sh
2. Click en "Configurar API"
3. Ingresa la URL de ngrok: https://1234-5678-90ab.ngrok.io
4. Login: admin / admin123
5. ¡Listo! Usa el sistema desde cualquier lugar 🎉
```

---

## ⚡ Alternativa: Cloudflare Tunnel (Sin registro)

### Paso 1: Instalar cloudflared

```bash
pkg install cloudflared
```

### Paso 2: Iniciar

```bash
./start-cloudflare.sh
```

### Paso 3: Configurar

```
1. Abre: https://tapiceria-laravel.surge.sh
2. Click en "Configurar API"
3. Ingresa la URL de Cloudflare: https://XXXXX.trycloudflare.com
4. Login: admin / admin123
```

---

## 📱 ¿Cómo acceder desde otro dispositivo?

### Desde cualquier lugar del mundo:

1. **En tu dispositivo Termux:**
   ```bash
   ./start-global.sh
   # Copia la URL que aparece (ej: https://1234.ngrok.io)
   ```

2. **En tu celular/tablet/otra PC:**
   - Abre el navegador
   - Ve a: https://tapiceria-laravel.surge.sh
   - Configura API con la URL de ngrok
   - Inicia sesión

3. **¡Listo!** Puedes usar el sistema desde cualquier dispositivo con internet

---

## 🆘 Problemas Comunes

| Problema | Solución |
|----------|----------|
| ngrok no instalado | `pkg install ngrok` |
| Token inválido | `ngrok config add-authtoken TU_TOKEN` |
| URL no funciona | Verifica que el túnel siga activo |
| Error 401 | Cierra sesión y vuelve a iniciar |
| Error database | `pg_ctl start` |

---

## 📊 Comparación Rápida

| Método | Velocidad | Requiere Registro | URL Permanente |
|--------|-----------|-------------------|----------------|
| Red Local | ⚡⚡⚡ | ❌ | ✅ |
| ngrok | ⚡⚡ | ✅ | ❌ (pago) |
| Cloudflare | ⚡⚡ | ❌ | ❌ |

---

## 🎯 Recomendación

- **Para desarrollo local**: Usa `./start-laravel.sh` (más rápido)
- **Para demostrar a clientes**: Usa `./start-global.sh` (ngrok)
- **Para acceso temporal**: Usa `./start-cloudflare.sh` (sin registro)

---

*Guía rápida - 2026-03-08*
