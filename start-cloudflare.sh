#!/bin/bash

# Script de inicio con Cloudflare Tunnel para acceso global
# Alternativa gratuita a ngrok
# Uso: ./start-cloudflare.sh

echo "🎨 Tapicería Odami Laravel - Cloudflare Tunnel"
echo "=============================================="
echo ""

# Obtener IP local
IP_LOCAL=$(hostname -I | awk '{print $1}' 2>/dev/null || echo "127.0.0.1")

# Verificar PostgreSQL
echo "📊 Verificando PostgreSQL..."
if ! pg_isready -q 2>/dev/null; then
    echo "⚠️  PostgreSQL no está corriendo. Iniciando..."
    pg_ctl start 2>/dev/null || { echo "❌ No se pudo iniciar PostgreSQL"; exit 1; }
fi

# Verificar cloudflared
if ! command -v cloudflared &> /dev/null; then
    echo "⚠️  cloudflared no está instalado."
    echo ""
    echo "📦 Para instalar cloudflared en Termux:"
    echo "   pkg install cloudflared"
    echo ""
    echo "📦 O en Linux:"
    echo "   curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb"
    echo "   sudo dpkg -i cloudflared.deb"
    echo ""
    exit 1
fi

# Generar APP_KEY si no existe
if [ -z "$(grep -v '^#' .env | grep APP_KEY | cut -d '=' -f 2)" ]; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --ansi
fi

echo ""
echo "🚀 Iniciando servicios..."
echo ""

# Iniciar Laravel en segundo plano
echo "📌 Iniciando Laravel en puerto 8000..."
php artisan serve --host=0.0.0.0 --port=8000 > laravel-api.log 2>&1 &
LARAVEL_PID=$!

sleep 2

# Iniciar Cloudflare Tunnel
echo "🌍 Iniciando Cloudflare Tunnel..."
cloudflared tunnel --url http://localhost:8000 > cloudflare.log 2>&1 &
TUNNEL_PID=$!

sleep 5

# Obtener URL del tunnel
TUNNEL_URL=$(grep -o 'https://[^ ]*\.trycloudflare\.com' cloudflare.log | tail -1)

if [ -z "$TUNNEL_URL" ]; then
    # Intentar obtener de otra forma
    TUNNEL_URL=$(tail -20 cloudflare.log | grep -o 'https://[a-zA-Z0-9-]*\.trycloudflare\.com' | head -1)
fi

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║     ✅ SISTEMA ACCESIBLE GLOBALMENTE                     ║"
echo "╠══════════════════════════════════════════════════════════╣"
echo "║                                                          ║"
echo "║  🌍 URL Pública (API):                                   ║"
echo "║     $TUNNEL_URL"
echo "║                                                          ║"
echo "║  🎨 Frontend:                                            ║"
echo "║     https://tapiceria-laravel.surge.sh                  ║"
echo "║                                                          ║"
echo "║  🔑 Credenciales:                                        ║"
echo "║     Usuario: admin                                       ║"
echo "║     Contraseña: admin123                                 ║"
echo "║                                                          ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""
echo "📝 Configuración en el frontend:"
echo "   1. Abre https://tapiceria-laravel.surge.sh"
echo "   2. Click en 'Configurar API'"
echo "   3. Ingresa: $TUNNEL_URL"
echo "   4. Inicia sesión"
echo ""
echo "⚠️  Presiona Ctrl+C para detener todos los servicios"
echo ""
echo "📊 Ver logs: tail -f cloudflare.log"
echo ""

# Guardar URL en archivo
echo "$TUNNEL_URL" > cloudflare-url.txt

# Manejar interrupción
trap "kill $LARAVEL_PID $TUNNEL_PID 2>/dev/null; echo '✅ Servicios detenidos'; exit" INT TERM

# Mantener ejecutando
wait
