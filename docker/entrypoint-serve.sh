#!/bin/sh
set -e
cd /var/www/html

if [ -z "$APP_KEY" ]; then
  export APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
fi

export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

php artisan config:clear 2>/dev/null || true
if ! php artisan migrate --force; then
  echo "ganatelo: migrate falló — revisá DATABASE_URL y DB_SSLMODE (PostgreSQL)." >&2
fi
php artisan storage:link --force 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
