#!/bin/bash

# ============================================
# Script de Inicio Seguro - Tapicería Laravel
# ============================================
# Inicia el servidor Laravel con configuraciones
# de seguridad para producción
# ============================================

set -e

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# Directorio del proyecto
PROJECT_DIR="/data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel"
LOG_DIR="/data/data/com.termux/files/home/mi-servidor/logs"
LOG_FILE="$LOG_DIR/laravel-production.log"

mkdir -p "$LOG_DIR"

echo -e "${BLUE}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   Tapicería Laravel - Servidor Seguro de Producción   ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════╝${NC}"
echo ""

cd "$PROJECT_DIR"

# ============================================
# Paso 1: Verificaciones de seguridad
# ============================================
echo -e "${CYAN}🔒 Paso 1: Verificaciones de seguridad...${NC}"

# Verificar que APP_DEBUG esté desactivado
if grep -q "APP_DEBUG=true" .env 2>/dev/null; then
    echo -e "${RED}   ⚠️  ADVERTENCIA: APP_DEBUG está activado${NC}"
    echo -e "${YELLOW}   Corrigiendo...${NC}"
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
fi

# Verificar APP_ENV
if grep -q "APP_ENV=local" .env 2>/dev/null; then
    echo -e "${YELLOW}   ⚠️  APP_ENV está en 'local', cambiando a 'production'${NC}"
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
fi

# Verificar APP_KEY
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo -e "${RED}   ❌ ERROR: APP_KEY no está configurada${NC}"
    echo -e "${YELLOW}   Generando nueva APP_KEY...${NC}"
    php artisan key:generate
fi

echo -e "${GREEN}   ✅ Verificaciones completadas${NC}"

# ============================================
# Paso 2: Optimizar para producción
# ============================================
echo ""
echo -e "${CYAN}⚡ Paso 2: Optimizando para producción...${NC}"

# Limpiar cachés
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Optimizar
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true

echo -e "${GREEN}   ✅ Optimización completada${NC}"

# ============================================
# Paso 3: Verificar base de datos
# ============================================
echo ""
echo -e "${CYAN}📊 Paso 3: Verificando base de datos...${NC}"

if pg_isready -h 127.0.0.1 -p 5432 -U postgres > /dev/null 2>&1; then
    echo -e "${GREEN}   ✅ PostgreSQL está disponible${NC}"
    
    # Verificar migraciones
    php artisan migrate --force 2>/dev/null || echo -e "${YELLOW}   ⚠️  Migraciones no disponibles${NC}"
else
    echo -e "${RED}   ⚠️  PostgreSQL no está disponible${NC}"
    echo -e "${YELLOW}   El servidor iniciará pero las funciones de BD no estarán disponibles${NC}"
fi

# ============================================
# Paso 4: Iniciar servidor
# ============================================
echo ""
echo -e "${CYAN}🚀 Paso 4: Iniciando servidor...${NC}"

# Matar cualquier instancia previa
pkill -f "php.*artisan serve.*8000" 2>/dev/null || true
sleep 1

# Iniciar servidor en background
php artisan serve --host=0.0.0.0 --port=8000 > "$LOG_FILE" 2>&1 &
SERVER_PID=$!

# Esperar a que el servidor esté listo
sleep 3

if kill -0 "$SERVER_PID" 2>/dev/null; then
    echo -e "${GREEN}   ✅ Servidor iniciado (PID: $SERVER_PID)${NC}"
else
    echo -e "${RED}   ❌ Error al iniciar el servidor${NC}"
    echo -e "${YELLOW}   Revisa el log: $LOG_FILE${NC}"
    exit 1
fi

# ============================================
# Paso 5: Iniciar Cloudflare Tunnel
# ============================================
echo ""
echo -e "${CYAN}☁️  Paso 5: Iniciando Cloudflare Tunnel...${NC}"

if ! command -v cloudflared &> /dev/null; then
    echo -e "${RED}   ❌ cloudflared no está instalado${NC}"
    echo -e "${YELLOW}   Instálalo con: pkg install cloudflared${NC}"
else
    # Iniciar túnel
    cloudflared tunnel --url http://localhost:8000 > "$LOG_DIR/cloudflared-laravel.log" 2>&1 &
    TUNNEL_PID=$!
    
    # Esperar URL
    sleep 8
    
    TUNNEL_URL=$(grep -oP 'https://[^\s]+\.trycloudflare\.com' "$LOG_DIR/cloudflared-laravel.log" | head -1)
    
    if [ -n "$TUNNEL_URL" ]; then
        echo -e "${GREEN}   ✅ Túnel iniciado: $TUNNEL_URL${NC}"
        echo "$TUNNEL_URL" > "$PROJECT_DIR/tunnel-url.txt"
        
        # Actualizar .env con la URL del túnel
        sed -i "s|APP_URL=.*|APP_URL=$TUNNEL_URL|" .env
        sed -i "s|SANCTUM_STATEFUL_DOMAINS=.*|SANCTUM_STATEFUL_DOMAINS=$(echo $TUNNEL_URL | sed 's|https://||')|" .env
    else
        echo -e "${YELLOW}   ⚠️  Esperando URL del túnel...${NC}"
        sleep 5
        TUNNEL_URL=$(grep -oP 'https://[^\s]+\.trycloudflare\.com' "$LOG_DIR/cloudflared-laravel.log" | head -1)
        if [ -n "$TUNNEL_URL" ]; then
            echo -e "${GREEN}   ✅ Túnel iniciado: $TUNNEL_URL${NC}"
        fi
    fi
fi

# ============================================
# Resumen final
# ============================================
echo ""
echo -e "${BLUE}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║              ✅ SERVIDOR SEGURO INICIADO               ║${NC}"
echo -e "${BLUE}╠════════════════════════════════════════════════════════╣${NC}"
echo -e "${BLUE}║${NC} Local:         ${GREEN}http://localhost:8000${NC}"
if [ -n "$TUNNEL_URL" ]; then
    echo -e "${BLUE}║${NC} Cloudflare:    ${GREEN}$TUNNEL_URL${NC}"
fi
echo -e "${BLUE}╠════════════════════════════════════════════════════════╣${NC}"
echo -e "${BLUE}║${NC} ${YELLOW}🔒 Configuración de seguridad aplicada:${NC}"
echo -e "${BLUE}║${NC}   • APP_DEBUG: false"
echo -e "${BLUE}║${NC}   • APP_ENV: production"
echo -e "${BLUE}║${NC}   • Rate limiting: 5 intentos/min (login)"
echo -e "${BLUE}║${NC}   • CORS: restringido a trycloudflare.com"
echo -e "${BLUE}║${NC}   • HTTPS: habilitado vía Cloudflare"
echo -e "${BLUE}╠════════════════════════════════════════════════════════╣${NC}"
echo -e "${BLUE}║${NC} Logs: $LOG_FILE"
echo -e "${BLUE}╠════════════════════════════════════════════════════════╣${NC}"
echo -e "${BLUE}║${NC} ${YELLOW}Presiona Ctrl+C para detener${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════╝${NC}"
echo ""

# Función de limpieza
cleanup() {
    echo -e "\n${YELLOW}🛑 Deteniendo servicios...${NC}"
    kill "$SERVER_PID" 2>/dev/null || true
    kill "$TUNNEL_PID" 2>/dev/null || true
    echo -e "${GREEN}✅ Servicios detenidos${NC}"
    exit 0
}

trap cleanup SIGINT SIGTERM

# Mantener el script corriendo
wait
