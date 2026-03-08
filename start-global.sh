#!/bin/bash

# Script de inicio con ngrok para acceso global
# Uso: ./start-global.sh

echo "🎨 Tapicería Odami Laravel - Acceso Global"
echo "==========================================="
echo ""

# Obtener IP local
IP_LOCAL=$(hostname -I | awk '{print $1}' 2>/dev/null || echo "127.0.0.1")

# Verificar PostgreSQL
echo "📊 Verificando PostgreSQL..."
if ! pg_isready -q 2>/dev/null; then
    echo "⚠️  PostgreSQL no está corriendo. Iniciando..."
    pg_ctl start 2>/dev/null || { echo "❌ No se pudo iniciar PostgreSQL"; exit 1; }
fi

# Verificar ngrok
if ! command -v ngrok &> /dev/null; then
    echo "⚠️  ngrok no está instalado."
    echo ""
    echo "📦 Para instalar ngrok:"
    echo "   1. Regístrate en: https://ngrok.com"
    echo "   2. Descarga ngrok para tu sistema"
    echo "   3. Ejecuta: ngrok config add-authtoken TU_TOKEN"
    echo ""
    echo "🔗 O usa la alternativa con Cloudflare Tunnel:"
    echo "   ./start-cloudflare.sh"
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

# Iniciar ngrok
echo "🌍 Iniciando ngrok túnel..."
ngrok http 8000 --log=ngrok.log > /dev/null 2>&1 &
NGROK_PID=$!

sleep 3

# Obtener URL de ngrok
NGROK_URL=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o '"public_url":"[^"]*"' | head -1 | cut -d'"' -f4)

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║     ✅ SISTEMA ACCESIBLE GLOBALMENTE                     ║"
echo "╠══════════════════════════════════════════════════════════╣"
echo "║                                                          ║"
echo "║  🌍 URL Pública (API):                                   ║"
echo "║     $NGROK_URL"
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
echo "   3. Ingresa: $NGROK_URL"
echo "   4. Inicia sesión"
echo ""
echo "⚠️  Presiona Ctrl+C para detener todos los servicios"
echo ""
echo "📊 Logs en tiempo real: tail -f ngrok.log"
echo ""

# Guardar URL en archivo
echo "$NGROK_URL" > ngrok-url.txt

# Manejar interrupción
trap "kill $LARAVEL_PID $NGROK_PID 2>/dev/null; echo '✅ Servicios detenidos'; exit" INT TERM

# Mantener ejecutando
wait
