# Veritas Afrika

Laravel + Filament CMS website for Veritas Afrika (public marketing site + admin panel).

## Stack

- PHP 8.2+ / Laravel 12
- Filament 3 admin at `/mgt`
- MySQL
- Vite + Bootstrap 5 (public theme)

## Requirements

- PHP >= 8.3 (8.2+ per Composer)
- Composer
- MySQL
- Node.js & npm

## Local setup

```bash
git clone <your-repo>
cd veritasafrika
composer install
npm install
cp .env.example .env
# set DB_* in .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan storage:link
php artisan serve
```

- Public site: http://127.0.0.1:8000  
- Admin: http://127.0.0.1:8000/mgt  

Seeded logins (password from `SEED_ADMIN_PASSWORD`, default `12345678` locally):

- `admin@admin.com` (admin — full access)
- `moderator@moderator.com` (moderator — limited write)
- `blogger@blogger.com` (blogger — mostly read)

## What Filament controls on the public site

| Admin resource | Public surface |
|---|---|
| Hero slides | Homepage hero carousel |
| Services | Home + `/our-services` + service detail |
| Portfolio & entities | `/portfolio` + clients strip |
| Team | Home + About |
| Content blocks | About / stats / feature copy |
| Pages + sections + blocks | `/pages/{slug}` CMS pages |
| Menu locations & items | Primary navbar |
| Organization + contacts + social | Footer + Contact |

## Authorization

Filament resources are gated by Spatie permissions (`read|create|update|delete` + entity).  
`admin` bypasses checks. Navigation items hide when the user cannot `read` that resource.

## Production checklist

1. Set in `.env`:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://your-domain.com`
   - Strong `APP_KEY`
   - Real `DB_*` credentials
   - Working `MAIL_*` (not `log`)
   - `SESSION_SECURE_COOKIE=true`
   - Strong `SEED_ADMIN_PASSWORD` (or change passwords after seed)
2. Run:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan migrate --force
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. Point the web server document root to `/public`  
   Example Nginx config: [`deploy/nginx.conf.example`](deploy/nginx.conf.example)  
   Optional demo host on Vercel (serverless PHP — needs external MySQL): [`deploy/vercel.md`](deploy/vercel.md)  
   **Recommended host:** Railway (app + MySQL): [`deploy/railway.md`](deploy/railway.md)
4. Ensure `storage/` and `bootstrap/cache/` are writable
5. Keep `/mgt` on HTTPS; change default seeded passwords immediately
6. Contact form is rate-limited (`throttle:contact`, 5/minute/IP)
7. HTTPS is forced automatically when `APP_ENV=production`

## Useful commands

```bash
php artisan db:seed --class=NavbarMenuSeeder
php artisan db:seed --class=PermissionSeeder
npm run dev          # local Vite HMR
npm run build        # production assets
php artisan about
php artisan route:list
```
