#!/bin/sh
set -e
cd /var/www/html

if [ -z "$APP_KEY" ]; then
  export APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
fi

export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

php artisan config:clear 2>/dev/null || true
php artisan migrate --force || true
php artisan storage:link --force 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
