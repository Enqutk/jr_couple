#!/usr/bin/env bash
# Run once on cPanel Terminal (site root = Laravel root, same folder as artisan).
set -euo pipefail

echo "== JR Couple — cPanel first-time / after-deploy setup =="

if [ ! -f artisan ]; then
  echo "Error: run this from the Laravel root (where artisan lives)."
  exit 1
fi

if [ ! -f .env ]; then
  echo "Error: create .env first (copy from .env.example, set APP_KEY, APP_URL, DB_*)."
  exit 1
fi

if ! php artisan db:show >/dev/null 2>&1; then
  echo "Error: cannot connect to the database. Fix DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env."
  echo "In cPanel: MySQL Databases → create database + user → Add User To Database (ALL PRIVILEGES)."
  exit 1
fi

# cPanel: file/sync drivers avoid needing sessions/cache/jobs tables before migrate.
for pair in "SESSION_DRIVER file" "CACHE_STORE file" "QUEUE_CONNECTION sync"; do
  key="${pair%% *}"
  val="${pair#* }"
  if grep -q "^${key}=database" .env 2>/dev/null; then
    sed -i "s/^${key}=database/${key}=${val}/" .env
    echo "Updated .env: ${key}=${val}"
  elif ! grep -q "^${key}=" .env 2>/dev/null; then
    echo "${key}=${val}" >> .env
    echo "Added .env: ${key}=${val}"
  fi
done

chmod -R 775 storage bootstrap/cache 2>/dev/null || true

php artisan storage:link --force 2>/dev/null || php artisan storage:link || true
if ! grep -q '^SEED_ADMIN_PASSWORD=' .env 2>/dev/null; then
  echo "Tip: add SEED_ADMIN_PASSWORD=YourStrongPassword to .env before seeding (or UserSeeder will generate one)."
fi
php artisan migrate --force
php artisan db:seed --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Done. Open your site URL and /mgt/login"
