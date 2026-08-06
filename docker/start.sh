#!/usr/bin/env bash
set -e

cd /var/www/html

php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear

if [ ! -f public/build/manifest.json ]; then
    echo "ERROR: Vite manifest missing at public/build/manifest.json"
    exit 1
fi

php artisan migrate --force --no-interaction

php artisan storage:link --force 2>/dev/null || true

exec php -S "0.0.0.0:${PORT:-8080}" -t public public/index.php
