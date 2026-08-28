#!/bin/bash
# Pre-deploy / release script for Railway App service.
# Make executable: chmod +x railway/init-app.sh
set -euo pipefail

echo "==> Running migrations..."
php artisan migrate --force

# Set RUN_SEEDERS=true in Railway Variables for the first deploy only, then remove it.
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
  echo "==> RUN_SEEDERS=true — seeding database..."
  php artisan db:seed --force
else
  echo "==> Skipping seed (set RUN_SEEDERS=true once for the first deploy)"
fi

php artisan storage:link || true

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache || true

echo "==> App init complete"
