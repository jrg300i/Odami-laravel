#!/bin/bash
# ============================================
# update-ip-laravel.sh - Actualiza la IP de la API Laravel en la BD
# ============================================

set -e

# Configuración
DB_NAME="tapiceria_odami_laravel"
DB_USER="postgres"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "📡 Actualizando IP de la API Laravel..."

# Obtener IP local (funciona en Termux/Android y Linux)
get_local_ip() {
    local ip=""
    
    # Método 1: hostname -I (Linux/Termux)
    if command -v hostname &> /dev/null; then
        ip=$(hostname -I 2>/dev/null | awk '{print $1}')
        if [ -n "$ip" ] && [ "$ip" != "127.0.0.1" ]; then
            echo "$ip"
            return
        fi
    fi
    
    # Método 2: ip route (Android/Termux)
    if command -v ip &> /dev/null; then
        ip=$(ip route get 1.1.1.1 2>/dev/null | grep -oP 'src \K\S+')
        if [ -n "$ip" ]; then
            echo "$ip"
            return
        fi
    fi
    
    # Fallback: localhost
    echo "127.0.0.1"
}

# Obtener IP actual
CURRENT_IP=$(get_local_ip)
CURRENT_URL="http://${CURRENT_IP}:8000"

echo "   IP detectada: $CURRENT_IP"
echo "   URL de API: $CURRENT_URL"

# Verificar si PostgreSQL está disponible
if ! pg_isready -U $DB_USER &> /dev/null; then
    echo "⚠️ PostgreSQL no está disponible, usando localhost"
    CURRENT_URL="http://localhost:8000"
fi

# Obtener URL guardada en la BD
SAVED_URL=""
if pg_isready -U $DB_USER &> /dev/null; then
    # Verificar si la tabla app_config existe
    TABLE_EXISTS=$(psql -U $DB_USER -d $DB_NAME -t -c "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'app_config')" 2>/dev/null | tr -d ' ' || echo "f")
    
    if [ "$TABLE_EXISTS" = "t" ]; then
        SAVED_URL=$(psql -U $DB_USER -d $DB_NAME -t -c "SELECT valor FROM app_config WHERE clave='api_url_local'" 2>/dev/null | tr -d ' ' || echo "")
    fi
fi

# Comparar y actualizar si es necesario
if [ "$CURRENT_URL" != "$SAVED_URL" ]; then
    echo "📡 Actualizando URL en la base de datos..."
    echo "   Anterior: $SAVED_URL"
    echo "   Nueva: $CURRENT_URL"
    
    if pg_isready -U $DB_USER &> /dev/null; then
        # Actualizar URL directamente con psql
        psql -U $DB_USER -d $DB_NAME -c "INSERT INTO app_config (clave, valor, descripcion, actualizado_en) VALUES ('api_url_local', '$CURRENT_URL', 'URL local', NOW()) ON CONFLICT (clave) DO UPDATE SET valor='$CURRENT_URL', actualizado_en=NOW();" 2>/dev/null && \
        echo "✅ IP actualizada en la base de datos" || \
        echo "⚠️ No se pudo actualizar la IP en la BD"
    else
        echo "⚠️ No se pudo conectar a PostgreSQL, se usará localhost"
    fi
else
    echo "✅ La IP ya está actualizada ($CURRENT_URL)"
fi

# Guardar en archivo local para referencia
echo "$CURRENT_URL" > "$SCRIPT_DIR/api-url.txt"
echo "📄 URL guardada en: $SCRIPT_DIR/api-url.txt"

echo ""
echo "========================================="
echo "✅ Configuración de API Laravel completada"
echo "========================================="
echo "   URL Local: $CURRENT_URL"
echo "   Frontend: https://tapiceria-odami-laravel.surge.sh"
echo ""
