#!/bin/bash

# Script de inicio con LocalTunnel (sin registro)
# Uso: ./start-localtunnel.sh

echo "🎨 Tapicería Odami Laravel - LocalTunnel"
echo "========================================"
echo ""

# Verificar PostgreSQL
echo "📊 Verificando PostgreSQL..."
if ! pg_isready -q 2>/dev/null; then
    echo "⚠️  PostgreSQL no está corriendo. Iniciando..."
    pg_ctl start 2>/dev/null || { echo "❌ No se pudo iniciar PostgreSQL"; exit 1; }
fi

# Verificar Node.js
if ! command -v node &> /dev/null; then
    echo "❌ Node.js no está instalado."
    echo ""
    echo "📦 Para instalar:"
    echo "   pkg install nodejs"
    echo ""
    exit 1
fi

# Instalar localtunnel si no existe
if ! command -v lt &> /dev/null; then
    echo "📦 Instalando LocalTunnel..."
    npm install -g localtunnel
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

# Iniciar LocalTunnel
echo "🌍 Iniciando LocalTunnel..."
lt --port 8000 > localtunnel.log 2>&1 &
LT_PID=$!

sleep 5

# Obtener URL de LocalTunnel
LT_URL=$(grep -o 'https://[^ ]*' localtunnel.log | head -1)

if [ -z "$LT_URL" ]; then
    LT_URL="Revisando..."
fi

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║     ✅ SISTEMA ACCESIBLE GLOBALMENTE                     ║"
echo "╠══════════════════════════════════════════════════════════╣"
echo "║                                                          ║"
echo "║  🌍 URL Pública (API):                                   ║"
echo "║     $LT_URL"
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
echo "   3. Ingresa: $LT_URL"
echo "   4. Inicia sesión"
echo ""
echo "⚠️  Presiona Ctrl+C para detener todos los servicios"
echo ""
echo "📊 Ver logs: tail -f localtunnel.log"
echo ""

# Guardar URL en archivo
echo "$LT_URL" > localtunnel-url.txt

# Manejar interrupción
trap "kill $LARAVEL_PID $LT_PID 2>/dev/null; echo '✅ Servicios detenidos'; exit" INT TERM

# Mantener ejecutando
wait
