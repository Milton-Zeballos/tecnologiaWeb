#!/bin/sh
set -e
cd /var/www/html

# Render / Docker: si no definís APP_KEY en el panel, Laravel revienta (500) al cifrar sesión/CSRF.
if [ -z "$APP_KEY" ]; then
  export APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
fi

# Los logs en archivo no se ven en el dashboard de Render; stderr sí.
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

# Sin esto, la BD en Render queda vacía: login/registro pega 500 (tabla users inexistente).
# Dockerfile.render ya migraba; el de Apache no.
if ! php artisan migrate --force; then
  echo "ganatelo: migrate falló — revisá DATABASE_URL, DB_* y DB_SSLMODE=require (PostgreSQL en Render)." >&2
fi
php artisan storage:link --force 2>/dev/null || true

exec apache2-foreground
