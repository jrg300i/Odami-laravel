#!/bin/bash

# =============================================================================
# 🌍 TAPICERÍA ODAMI - DESPLIEGUE GLOBAL AUTOMÁTICO
# =============================================================================
# Script único para acceso desde cualquier lugar del mundo
# Usa Cloudflare Tunnel + ngrok simultáneamente con auto-reinicio
#
# USO: ./start.sh
# =============================================================================

set -e

# Configuración
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOGS_DIR="$PROJECT_DIR/logs"
PID_DIR="$PROJECT_DIR/.pids"
CHECK_INTERVAL=60  # Verificar URL cada 60 segundos
MAX_RETRIES=3

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
NC='\033[0m'

# Variables globales
CLOUDFLARE_URL=""
NONHUB_URL=""
RETRY_COUNT=0

# =============================================================================
# FUNCIONES
# =============================================================================

log() {
    echo -e "${CYAN}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')] ✅${NC} $1"
}

log_error() {
    echo -e "${RED}[$(date '+%Y-%m-%d %H:%M:%S')] ❌${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[$(date '+%Y-%m-%d %H:%M:%S')] ⚠️${NC} $1"
}

print_header() {
    clear
    echo -e "${CYAN}╔═══════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║  🌍 TAPICERÍA ODAMI - ACCESO GLOBAL                       ║${NC}"
    echo -e "${CYAN}║     Cloudflare Tunnel + ngrok - Auto-reinicio           ║${NC}"
    echo -e "${CYAN}╚═══════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

setup_directories() {
    mkdir -p "$LOGS_DIR"
    mkdir -p "$PID_DIR"
    mkdir -p "$PROJECT_DIR/storage/app/photos/fotos"
    chmod -R 775 "$PROJECT_DIR/storage" 2>/dev/null || true
}

stop_services() {
    log "Deteniendo servicios anteriores..."
    pkill -f "php artisan serve" 2>/dev/null || true
    pkill -f "cloudflared tunnel" 2>/dev/null || true
    pkill -f "ngrok http" 2>/dev/null || true
    sleep 2
    log_success "Servicios detenidos"
}

start_laravel() {
    log "Iniciando Laravel..."
    cd "$PROJECT_DIR"

    # Limpiar caché
    php artisan config:clear > /dev/null 2>&1
    php artisan cache:clear > /dev/null 2>&1
    php artisan route:clear > /dev/null 2>&1

    # Iniciar servidor
    php artisan serve --host=0.0.0.0 --port=8000 > "$LOGS_DIR/laravel.log" 2>&1 &
    echo $! > "$PID_DIR/laravel.pid"

    sleep 3

    if curl -s http://localhost:8000 > /dev/null 2>&1; then
        log_success "Laravel iniciado (PID: $(cat $PID_DIR/laravel.pid))"
        return 0
    else
        log_error "Error al iniciar Laravel"
        return 1
    fi
}

start_cloudflare() {
    log "Iniciando Cloudflare Tunnel..."

    cloudflared tunnel --url http://localhost:8000 > "$LOGS_DIR/cloudflare.log" 2>&1 &
    echo $! > "$PID_DIR/cloudflare.pid"

    # Esperar más tiempo para que Cloudflare genere la URL
    sleep 12

    CLOUDFLARE_URL=$(grep -oP 'https://[^\s]+\.trycloudflare\.com' "$LOGS_DIR/cloudflare.log" | tail -1)

    if [ -n "$CLOUDFLARE_URL" ]; then
        log_success "Cloudflare Tunnel: $CLOUDFLARE_URL"
        return 0
    else
        log_error "Error al iniciar Cloudflare Tunnel"
        return 1
    fi
}

start_ngrok() {
    log "Iniciando ngrok (opcional - requiere authtoken)..."

    if ! command -v ngrok &> /dev/null; then
        log_warning "ngrok no está instalado. Usando solo Cloudflare..."
        return 1
    fi

    # Verificar si ngrok está configurado
    if ! ngrok config list &>/dev/null; then
        log_warning "ngrok sin authtoken configurado."
        log "Para configurar: ngrok config add-authtoken TU_TOKEN"
        log "Continuando solo con Cloudflare Tunnel..."
        return 1
    fi

    ngrok http 8000 --log="$LOGS_DIR/ngrok.log" > "$LOGS_DIR/ngrok_stdout.log" 2>&1 &
    echo $! > "$PID_DIR/ngrok.pid"

    sleep 8

    # Extraer URL desde la API local de ngrok
    NONHUB_URL=$(curl -s http://127.0.0.1:4040/api/tunnels 2>/dev/null | grep -oP '"public_url":"https://[^\"]+"' | head -1 | sed 's/"public_url":"//;s/"$//')

    if [ -n "$NONHUB_URL" ]; then
        log_success "ngrok Tunnel: $NONHUB_URL"
        return 0
    else
        log_warning "ngrok no pudo iniciar (verifica authtoken). Usando solo Cloudflare..."
        return 1
    fi
}

update_env() {
    local url=$1
    local domain=$(echo $url | sed 's|https://||')
    
    log "Actualizando configuración con URL: $url"
    
    sed -i "s|^APP_URL=.*|APP_URL=$url|" "$PROJECT_DIR/.env"
    sed -i "s|^SANCTUM_STATEFUL_DOMAINS=.*|SANCTUM_STATEFUL_DOMAINS=$domain|" "$PROJECT_DIR/.env"
    
    php artisan config:clear > /dev/null 2>&1
    
    log_success "Configuración actualizada"
}

save_urls() {
    local primary_url="${NONHUB_URL:-$CLOUDFLARE_URL}"
    echo "CLOUDFLARE_URL=$CLOUDFLARE_URL" > "$PROJECT_DIR/.urls"
    echo "NONHUB_URL=$NONHUB_URL" >> "$PROJECT_DIR/.urls"
    echo "PRIMARY_URL=$primary_url" >> "$PROJECT_DIR/.urls"
    echo "LAST_UPDATE=$(date)" >> "$PROJECT_DIR/.urls"
    log_success "URLs guardadas en .urls"
}

print_urls() {
    echo ""
    echo -e "${GREEN}╔═══════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║  🌍 SERVICIOS INICIADOS CON ÉXITO                         ║${NC}"
    echo -e "${GREEN}╚═══════════════════════════════════════════════════════════╝${NC}"
    echo ""

    if [ -n "$NONHUB_URL" ]; then
        echo -e "${WHITE}🔗 URL PRINCIPAL (PERMANENTE - ngrok):${NC}"
        echo -e "${GREEN}$NONHUB_URL${NC}"
        echo ""
    fi

    if [ -n "$CLOUDFLARE_URL" ]; then
        if [ -n "$NONHUB_URL" ]; then
            echo -e "${WHITE}🔗 URL SECUNDARIA (TEMPORAL - Cloudflare):${NC}"
        else
            echo -e "${WHITE}🔗 URL DE ACCESO (Cloudflare Tunnel):${NC}"
        fi
        echo -e "${CYAN}$CLOUDFLARE_URL${NC}"
        echo ""
    fi

    local primary_url="${NONHUB_URL:-$CLOUDFLARE_URL}"
    echo -e "${WHITE}📝 Endpoints:${NC}"
    echo "   Login:     ${primary_url}/api/auth/login"
    echo "   Dashboard: ${primary_url}/api/dashboard/stats"
    echo "   Trabajos:  ${primary_url}/api/trabajos"
    echo "   Fotos:     ${primary_url}/api/fotos"
    echo ""
    echo -e "${WHITE}🔐 Credenciales:${NC}"
    echo "   Usuario: admin"
    echo "   Password: admin123"
    echo ""
    echo -e "${WHITE}📊 Logs:${NC}"
    echo "   Laravel:   tail -f $LOGS_DIR/laravel.log"
    echo "   Cloudflare: tail -f $LOGS_DIR/cloudflare.log"
    if [ -n "$NONHUB_URL" ]; then
        echo "   ngrok:     tail -f $LOGS_DIR/ngrok.log"
    fi
    echo ""
    echo -e "${WHITE}🛑 Detener:${NC}"
    echo "   ./stop.sh"
    echo ""
    echo -e "${YELLOW}⚠️  Nota: La URL de Cloudflare es temporal y cambia al reiniciar.${NC}"
    if [ -z "$NONHUB_URL" ]; then
        echo -e "${YELLOW}    Para URL permanente, configura ngrok con: ngrok config add-authtoken TOKEN${NC}"
    fi
    echo ""
}

check_cloudflare_url() {
    # Verificar si la URL de Cloudflare cambió
    local current_url=$(grep -oP 'https://[^\s]+\.trycloudflare\.com' "$LOGS_DIR/cloudflare.log" | tail -1)
    
    if [ -n "$current_url" ] && [ "$current_url" != "$CLOUDFLARE_URL" ]; then
        log_warning "La URL de Cloudflare cambió: $current_url"
        CLOUDFLARE_URL="$current_url"
        update_env "$CLOUDFLARE_URL"
        save_urls
        print_urls
        return 0
    fi
    
    return 1
}

check_services() {
    local needs_restart=false
    
    # Verificar Laravel
    if ! pgrep -f "php artisan serve" > /dev/null; then
        log_error "Laravel se detuvo"
        needs_restart=true
    fi
    
    # Verificar Cloudflare
    if ! pgrep -f "cloudflared tunnel" > /dev/null; then
        log_error "Cloudflare Tunnel se detuvo"
        needs_restart=true
    fi
    
    # Verificar si la URL cambió
    if check_cloudflare_url; then
        needs_restart=false  # Solo fue cambio de URL, no necesita restart completo
    fi
    
    if $needs_restart; then
        RETRY_COUNT=$((RETRY_COUNT + 1))
        if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
            log_error "Máximo de reintentos alcanzado. Deteniendo..."
            exit 1
        fi
        log "Reintentando en 5 segundos... (intento $RETRY_COUNT/$MAX_RETRIES)"
        sleep 5
        restart_services
    fi
}

restart_services() {
    log "Reiniciando servicios..."
    stop_services
    start_laravel
    start_cloudflare
    start_ngrok
    RETRY_COUNT=0
}

cleanup() {
    echo ""
    log "Deteniendo servicios..."
    stop_services
    rm -rf "$PID_DIR"
    log_success "Servicios detenidos"
    exit 0
}

# =============================================================================
# SCRIPT PRINCIPAL
# =============================================================================

# Configurar trap para cleanup
trap cleanup SIGINT SIGTERM

print_header
setup_directories
stop_services

# Iniciar Laravel
if ! start_laravel; then
    exit 1
fi

# Iniciar Cloudflare
start_cloudflare || true

# Iniciar ngrok (opcional)
start_ngrok || true

# Determinar URL principal
if [ -n "$NONHUB_URL" ]; then
    update_env "$NONHUB_URL"
elif [ -n "$CLOUDFLARE_URL" ]; then
    update_env "$CLOUDFLARE_URL"
fi

save_urls
print_urls

# Bucle principal de monitoreo
log "Monitoreando servicios (Ctrl+C para detener)..."
echo ""

while true; do
    sleep $CHECK_INTERVAL
    check_services
done
