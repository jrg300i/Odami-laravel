#!/bin/bash

# ============================================
# Iniciar Cloudflare Tunnel - Tapicería Laravel
# ============================================
# Ejecutar este script en una terminal separada
# después de iniciar el servidor Laravel
# ============================================

echo "╔════════════════════════════════════════════════════════╗"
echo "║   Cloudflare Tunnel - Tapicería Laravel                ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Verificar cloudflared
if ! command -v cloudflared &> /dev/null; then
    echo "❌ cloudflared no está instalado"
    echo "Instálalo con: pkg install cloudflared"
    exit 1
fi

echo "✅ cloudflared encontrado: $(cloudflared --version)"
echo ""
echo "Iniciando túnel hacia http://localhost:8000..."
echo ""
echo "Presiona Ctrl+C para detener el túnel"
echo ""

# Iniciar túnel (foreground para que se mantenga)
cloudflared tunnel --url http://localhost:8000
