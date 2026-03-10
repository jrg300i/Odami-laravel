#!/bin/bash

# ============================================
# Script de Inicio Permanente - Tapicería Laravel
# ============================================
# Este script inicia Laravel y Cloudflare Tunnel
# y los mantiene corriendo en segundo plano
# ============================================

set -e

PROJECT_DIR="/data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel"
LOG_DIR="/data/data/com.termux/files/home/mi-servidor/logs"

cd "$PROJECT_DIR"

echo "╔════════════════════════════════════════════════════════╗"
echo "║   Tapicería Laravel - Inicio Permanente                ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Verificar PostgreSQL
echo "📊 Verificando PostgreSQL..."
if ! pg_isready -q 2>/dev/null; then
    echo "⚠️  PostgreSQL no está corriendo"
    echo "   Iniciando PostgreSQL..."
    export PGDATA=/data/data/com.termux/files/usr/var/lib/postgresql
    pg_ctl -l "$LOG_DIR/postgres.log" start 2>/dev/null || echo "❌ No se pudo iniciar PostgreSQL"
    sleep 3
fi

# Matar instancias previas
echo "🧹 Limpiando procesos anteriores..."
pkill -f "php artisan serve.*8000" 2>/dev/null || true
pkill -f "cloudflared tunnel" 2>/dev/null || true
sleep 2

# Iniciar Laravel
echo "🚀 Iniciando Laravel..."
nohup php artisan serve --host=0.0.0.0 --port=8000 > "$LOG_DIR/laravel.log" 2>&1 &
LARAVEL_PID=$!
echo "   ✅ Laravel iniciado (PID: $LARAVEL_PID)"

# Esperar a que Laravel esté listo
sleep 3

# Verificar que Laravel esté corriendo
if curl -s http://localhost:8000/health > /dev/null 2>&1; then
    echo "   ✅ Laravel está respondiendo"
else
    echo "   ⚠️  Laravel no responde aún, esperando..."
    sleep 5
fi

# Iniciar Cloudflare Tunnel
echo "☁️  Iniciando Cloudflare Tunnel..."
if ! command -v cloudflared &> /dev/null; then
    echo "   ❌ cloudflared no está instalado"
else
    nohup cloudflared tunnel --url http://localhost:8000 > "$LOG_DIR/cloudflared.log" 2>&1 &
    TUNNEL_PID=$!
    echo "   ✅ Cloudflare Tunnel iniciado (PID: $TUNNEL_PID)"
    
    # Esperar URL del túnel
    echo "   ⏳ Esperando URL del túnel..."
    sleep 10
    
    TUNNEL_URL=$(grep -oP 'https://[^\s]+\.trycloudflare\.com' "$LOG_DIR/cloudflared.log" 2>/dev/null | head -1)
    
    if [ -n "$TUNNEL_URL" ]; then
        echo "   ✅ Túnel activo: $TUNNEL_URL"
        echo "$TUNNEL_URL" > "$PROJECT_DIR/tunnel-url.txt"
        
        # Actualizar .env
        sed -i "s|APP_URL=.*|APP_URL=$TUNNEL_URL|" .env 2>/dev/null || true
        sed -i "s|SANCTUM_STATEFUL_DOMAINS=.*|SANCTUM_STATEFUL_DOMAINS=$(echo $TUNNEL_URL | sed 's|https://||')|" .env 2>/dev/null || true
    else
        echo "   ⚠️  No se pudo obtener la URL del túnel aún"
    fi
fi

# Guardar PIDs
echo "$LARAVEL_PID" > "$LOG_DIR/laravel.pid"
echo "$TUNNEL_PID" > "$LOG_DIR/tunnel.pid" 2>/dev/null || true

echo ""
echo "╔════════════════════════════════════════════════════════╗"
echo "║              ✅ SERVICIOS INICIADOS                    ║"
echo "╠════════════════════════════════════════════════════════╣"
echo "║${NC} Laravel:      ${GREEN}http://localhost:8000${NC}"
if [ -n "$TUNNEL_URL" ]; then
    echo "║${NC} Cloudflare:   ${GREEN}$TUNNEL_URL${NC}"
fi
echo "╠════════════════════════════════════════════════════════╣"
echo "║${NC} Logs: $LOG_DIR"
echo "╠════════════════════════════════════════════════════════╣"
echo "║${NC} Para detener los servicios:"
echo "║${NC}   pkill -f 'php artisan serve'"
echo "║${NC}   pkill -f cloudflared"
echo "╠════════════════════════════════════════════════════════╣"
echo "║${NC} Frontend: https://tapiceria-laravel.surge.sh"
echo "╠════════════════════════════════════════════════════════╣"
echo "║${NC} Login: admin / admin123"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
