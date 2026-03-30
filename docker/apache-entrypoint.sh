#!/bin/bash
set -e
cd /var/www/html

if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

if [ -f composer.json ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader || true
fi

if [ -f .env ] && [ -z "$APP_KEY" ] && ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
  php artisan key:generate --force || true
fi

php artisan storage:link --force 2>/dev/null || true

# Espera a MySQL si aún no acepta conexiones
for i in $(seq 1 30); do
  if php artisan migrate --force 2>/dev/null; then
    break
  fi
  sleep 2
done

if [ "${APP_ENV:-local}" = "production" ]; then
  php artisan config:cache 2>/dev/null || true
  php artisan route:cache 2>/dev/null || true
  php artisan view:cache 2>/dev/null || true
fi

exec "$@"
