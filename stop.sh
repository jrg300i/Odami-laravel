#!/bin/bash

# =============================================================================
# 🛑 TAPICERÍA ODAMI - DETENER SERVICIOS
# =============================================================================
# USO: ./stop.sh
# =============================================================================

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

PROJECT_DIR="/data/data/com.termux/files/home/mi-servidor/public/surge-projects/tapiceria-odami-laravel"
PID_DIR="$PROJECT_DIR/.pids"

echo -e "${CYAN}╔═══════════════════════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║  Deteniendo servicios...                                  ║${NC}"
echo -e "${CYAN}╚═══════════════════════════════════════════════════════════╝${NC}"
echo ""

# Detener por PID si existen
if [ -f "$PID_DIR/laravel.pid" ]; then
    kill $(cat "$PID_DIR/laravel.pid") 2>/dev/null && echo -e "${GREEN}✅ Laravel detenido${NC}"
    rm "$PID_DIR/laravel.pid"
fi

if [ -f "$PID_DIR/cloudflare.pid" ]; then
    kill $(cat "$PID_DIR/cloudflare.pid") 2>/dev/null && echo -e "${GREEN}✅ Cloudflare detenido${NC}"
    rm "$PID_DIR/cloudflare.pid"
fi

if [ -f "$PID_DIR/nonhub.pid" ]; then
    kill $(cat "$PID_DIR/nonhub.pid") 2>/dev/null && echo -e "${GREEN}✅ nonhub detenido${NC}"
    rm "$PID_DIR/nonhub.pid"
fi

# Detener por nombre de proceso (backup)
pkill -f "php artisan serve" 2>/dev/null && echo -e "${GREEN}✅ Laravel (backup) detenido${NC}"
pkill -f "cloudflared tunnel" 2>/dev/null && echo -e "${GREEN}✅ Cloudflare (backup) detenido${NC}"
pkill -f "nonhub http" 2>/dev/null && echo -e "${GREEN}✅ nonhub (backup) detenido${NC}"

# Limpiar directorio de PIDs
rm -rf "$PID_DIR" 2>/dev/null

echo ""
echo -e "${GREEN}╔═══════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║  Todos los servicios fueron detenidos                     ║${NC}"
echo -e "${GREEN}╚═══════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Para iniciar: ./start.sh${NC}"
