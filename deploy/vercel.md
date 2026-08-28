# Deploy MajiWorks to Vercel

Vercel runs this Laravel app as a **serverless PHP function** (`vercel-php`). That works for a demo, but it is a weak fit for Filament + MySQL + file uploads long-term. Prefer Railway, Render, or a VPS for production.

## What Vercel cannot host for you

| Need | Solution |
|------|----------|
| MySQL | External DB (Railway, Aiven, PlanetScale MySQL, etc.) |
| Persistent uploads / Spatie media | S3 / Cloudflare R2 / similar (`MEDIA_DISK=s3`) |
| Long-running queues | Not available — use `QUEUE_CONNECTION=sync` |
| Writable `storage/` | Only `/tmp` is writable |

## 1. Create an external MySQL database

Create a MySQL 8 database somewhere reachable from the internet (Railway MySQL is fine). Note:

- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

From your machine (with those credentials in `.env`):

```bash
php artisan migrate --force
php artisan db:seed --force
```

Seeded media files live under `storage/app/public`. On Vercel that disk is ephemeral, so either:

- point `MEDIA_DISK` / `FILESYSTEM_DISK` at **S3/R2**, re-upload media, or
- rely on public fallbacks under `public/assets/images/majiworks/` for the demo.

## 2. Generate an app key

```bash
php artisan key:generate --show
```

Copy the `base64:...` value — you will paste it into Vercel as `APP_KEY`.

## 3. Install Vercel CLI and log in

```bash
npm i -g vercel
vercel login
```

## 4. Set environment variables in Vercel

In the Vercel project → **Settings → Environment Variables** (Production + Preview):

| Variable | Example |
|----------|---------|
| `APP_KEY` | `base64:...` from step 2 |
| `APP_URL` | `https://your-project.vercel.app` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | your host |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | your db name |
| `DB_USERNAME` | your user |
| `DB_PASSWORD` | your password |
| `SESSION_SECURE_COOKIE` | `true` |
| `SEED_ADMIN_PASSWORD` | strong password (if you re-seed) |

Optional mail / S3 vars as needed.

Do **not** commit real secrets. `vercel.json` only sets safe serverless defaults (`/tmp` caches, cookie sessions, etc.).

## 5. Deploy

Preview:

```bash
vercel
```

Production:

```bash
vercel --prod
```

Or connect the GitHub repo (`Enqutk/veritasafrika`) in the Vercel dashboard so every push to `main` deploys.

## 6. After first deploy

1. Set `APP_URL` to the real `*.vercel.app` (or custom domain) URL and redeploy.
2. Open `/` and `/mgt`.
3. Change seeded admin passwords immediately.

## Files added for Vercel

- `api/index.php` — serverless entry → Laravel `public/index.php`
- `vercel.json` — PHP 8.3 runtime, static asset routes, build commands
- `.vercelignore` — keeps deploy upload smaller

## If deploy fails

Common causes:

- **Function too large** — Filament + vendor can exceed hobby limits; try a smaller exclude list or a VPS/Railway instead
- **Missing `APP_KEY` / DB** — site boots then 500s on DB queries
- **Broken images** — media not on a persistent disk (use S3 or public asset fallbacks)
- **Admin login oddities** — cookie sessions + HTTPS; ensure `APP_URL` and `SESSION_SECURE_COOKIE` match
