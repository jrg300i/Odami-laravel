-- ============================================
-- Tabla app_config para Laravel
-- ============================================

CREATE TABLE IF NOT EXISTS app_config (
    id SERIAL PRIMARY KEY,
    clave VARCHAR(50) UNIQUE NOT NULL,
    valor TEXT NOT NULL,
    descripcion TEXT,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar configuraciones por defecto
INSERT INTO app_config (clave, valor, descripcion, actualizado_en) VALUES
    ('api_url_local', 'http://localhost:8000', 'URL de la API en red local - Se actualiza automáticamente', NOW()),
    ('api_url_tunnel', '', 'URL del túnel Cloudflare (opcional)', NOW()),
    ('api_modo', 'auto', 'Modo de conexión: auto, local, tunnel', NOW()),
    ('api_activa', 'true', 'API habilitada para conexiones', NOW())
ON CONFLICT (clave) DO UPDATE SET
    valor = EXCLUDED.valor,
    actualizado_en = NOW();

-- Índice para búsquedas rápidas
CREATE INDEX IF NOT EXISTS idx_app_config_clave ON app_config(clave);
