#!/bin/bash

# Script de instalación para Tapicería Odami Laravel
# Ejecutar solo la primera vez

echo "🎨 Tapicería Odami Laravel - Instalación"
echo "========================================"
echo ""

# Verificar PHP
echo "📌 Verificando PHP..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP no está instalado. Instalando..."
    pkg install php -y
fi

# Verificar extensiones PHP necesarias
echo "📌 Verificando extensiones PHP..."
php -m | grep -q "pdo_pgsql" || echo "⚠️  Extensión pdo_pgsql no encontrada"
php -m | grep -q "mbstring" || echo "⚠️  Extensión mbstring no encontrada"

# Instalar Composer si no existe
if ! command -v composer &> /dev/null; then
    echo "📦 Instalando Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/data/data/com.termux/files/usr/bin --filename=composer
fi

# Instalar dependencias
echo "📦 Instalando dependencias de Laravel..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generar APP_KEY
echo "🔑 Generando APP_KEY..."
php artisan key:generate

# Verificar base de datos
echo ""
echo "📊 Verificando base de datos..."
if ! pg_isready -q 2>/dev/null; then
    echo "⚠️  PostgreSQL no está corriendo"
    echo "   Ejecuta: pg_ctl start"
fi

echo ""
echo "✅ ¡Instalación completada!"
echo ""
echo "🚀 Para iniciar el sistema:"
echo "   ./start-laravel.sh"
echo ""
echo "📝 Credenciales:"
echo "   Usuario: admin"
echo "   Contraseña: admin123"
echo ""
