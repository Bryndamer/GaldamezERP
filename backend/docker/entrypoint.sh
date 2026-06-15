#!/bin/sh
set -e

echo "======================================"
echo " GaldamezERP Backend — Iniciando..."
echo "======================================"

# ─── Generar APP_KEY si no está definida ──────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "[INFO] APP_KEY no definida. Generando..."
    php artisan key:generate --force
fi

# ─── Esperar a que MySQL esté listo ──────────────────────────────────────────
echo "[INFO] Esperando conexión a MySQL (host: $DB_HOST, puerto: $DB_PORT)..."
MAX_TRIES=30
COUNT=0
until php artisan db:show > /dev/null 2>&1; do
    COUNT=$((COUNT + 1))
    if [ "$COUNT" -ge "$MAX_TRIES" ]; then
        echo "[ERROR] No se pudo conectar a MySQL después de $MAX_TRIES intentos. Abortando."
        exit 1
    fi
    echo "[INFO] MySQL no disponible aún. Reintentando en 3s... ($COUNT/$MAX_TRIES)"
    sleep 3
done
echo "[OK] Conexión a MySQL establecida."

# ─── Migraciones ─────────────────────────────────────────────────────────────
echo "[INFO] Ejecutando migraciones..."
php artisan migrate --force

# ─── Storage link ────────────────────────────────────────────────────────────
php artisan storage:link --force 2>/dev/null || true

# ─── Limpiar caché de configuración ──────────────────────────────────────────
php artisan config:clear
php artisan cache:clear

echo "[OK] Inicialización completa. Lanzando servidores..."
echo "     Panel Admin: http://localhost:8000/login"
echo "     API REST:    http://localhost:8000/api"
echo "======================================"

# ─── Lanzar supervisord (php-fpm + nginx) ────────────────────────────────────
exec /usr/bin/supervisord -c /etc/supervisord.conf
