#!/bin/bash

# Script para exportar datos desde PostgreSQL local
# Uso: ./export-data-for-render.sh

echo "📊 Exportando datos para Render.com"
echo "===================================="
echo ""

DB_NAME="tapiceria_odami"
DB_USER="postgres"
OUTPUT_FILE="backup-render.sql"

echo "📦 Exportando base de datos: $DB_NAME"
echo ""

# Exportar solo datos (sin estructura)
pg_dump -U $DB_USER -d $DB_NAME --data-only --column-inserts > $OUTPUT_FILE 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Exportación completada!"
    echo ""
    echo "📁 Archivo: $OUTPUT_FILE"
    echo ""
    echo "📝 Para importar en Render:"
    echo "   1. Sube este archivo a tu repositorio GitHub"
    echo "   2. En Render, ve a PostgreSQL → Connect"
    echo "   3. O usa la consola SSH:"
    echo "      psql -h <host> -U <user> -d tapiceria_odami -f backup-render.sql"
    echo ""
    echo "⚠️  El archivo contiene solo los datos, no la estructura."
    echo "   Las migraciones de Laravel crearán las tablas automáticamente."
else
    echo "❌ Error al exportar"
    echo "   Verifica que PostgreSQL esté corriendo"
    echo "   y que la base de datos exista"
fi
