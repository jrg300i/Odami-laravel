#!/bin/bash

# Script de inicio rápido para Tapicería Odami Laravel
# Uso: ./start-laravel.sh

echo "🎨 Tapicería Odami - Iniciando Backend Laravel"
echo "=============================================="

# Obtener IP local
IP_LOCAL=$(hostname -I | awk '{print $1}' 2>/dev/null || echo "127.0.0.1")

echo "📍 IP Local: $IP_LOCAL"
echo ""

# Verificar PostgreSQL
echo "📊 Verificando PostgreSQL..."
if ! pg_isready -q 2>/dev/null; then
    echo "⚠️  PostgreSQL no está corriendo. Iniciando..."
    pg_ctl start 2>/dev/null || echo "❌ No se pudo iniciar PostgreSQL"
fi

# Verificar si la base de datos existe
echo "📊 Verificando base de datos..."
if ! psql -U postgres -lqt 2>/dev/null | cut -d \| -f 1 | grep -qw tapiceria_odami; then
    echo "⚠️  La base de datos 'tapiceria_odami' no existe."
    echo "📝 Ejecuta primero el script de inicialización del proyecto Node.js"
    exit 1
fi

# Generar APP_KEY si no existe
if [ -z "$(grep -v '^#' .env | grep APP_KEY | cut -d '=' -f 2)" ]; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --ansi
fi

# Instalar dependencias si no existe vendor
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependencias de PHP..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

echo ""
echo "✅ Todo listo! Iniciando servidor Laravel..."
echo ""
echo "🌐 URL del Frontend: https://tapiceria-laravel.surge.sh"
echo "🔌 API URL: http://$IP_LOCAL:8000"
echo ""
echo "📝 Credenciales:"
echo "   Usuario: admin"
echo "   Contraseña: admin123"
echo ""
echo "⚠️  Presiona Ctrl+C para detener el servidor"
echo ""

# Iniciar servidor
php artisan serve --host=0.0.0.0 --port=8000
