#!/usr/bin/env bash
set -e

cd /var/www/html

php artisan config:clear
php artisan config:cache
php artisan route:cache

if [ ! -f public/build/manifest.json ]; then
    echo "ERROR: Vite manifest missing at public/build/manifest.json"
    exit 1
fi

php artisan view:cache

php artisan migrate --force --no-interaction

php artisan storage:link --force 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
