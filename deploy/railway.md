# Deploy MajiWorks on Railway

Railway is a good fit for this Laravel + Filament + MySQL app (persistent process, real MySQL, volumes).

## Quick path (recommended)

### 1. Push this repo to GitHub

Use your private remote (`enqutk`), not the Teter `origin`:

```bash
git add api vercel.json .vercelignore railway railway.toml deploy/railway.md
git commit -m "Add Railway (and Vercel) deploy config"
git push enqutk main
```

### 2. Create the Railway project

1. Open [railway.com/new](https://railway.com/new) → **Deploy from GitHub repo** → `Enqutk/veritasafrika`.
2. In the same project: **New** → **Database** → **MySQL**.
3. Open the **App** service → **Variables** → add:

| Variable | Value |
|----------|--------|
| `APP_KEY` | output of `php artisan key:generate --show` |
| `APP_URL` | `https://${{RAILWAY_PUBLIC_DOMAIN}}` (after you generate a domain) |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |
| `SEED_ADMIN_PASSWORD` | a strong password (used when seeding) |
| `RUN_SEEDERS` | `true` **once** on first deploy, then delete/set `false` |
| `LOG_CHANNEL` | `stderr` |

(`railway.toml` already sets other production defaults.)

4. **Settings → Networking → Generate Domain**.
5. Set `APP_URL` to that `https://….up.railway.app` URL and redeploy if needed.
6. **Settings → Volumes** (optional but recommended for Filament uploads):  
   mount a volume at `/app/storage/app/public` (path may be `/var/www/html/storage/app/public` depending on Railpack — check deploy logs / docs if media disappears after restart).

### 3. Verify

- Public site: `https://your-app.up.railway.app`
- Admin: `https://your-app.up.railway.app/mgt`
- Login: `admin@admin.com` / your `SEED_ADMIN_PASSWORD`

## CLI path

```bash
npm i -g @railway/cli   # or: npx @railway/cli
railway login
railway init             # create / link project
railway add --database mysql
railway variables set APP_KEY="$(php artisan key:generate --show)"
# set remaining DB_* via dashboard variable references, then:
railway up
railway domain
```

## Optional: worker + cron

Same GitHub repo, two extra services, **Custom Start Command**:

- Worker: `chmod +x ./railway/run-worker.sh && ./railway/run-worker.sh`
- Cron: `chmod +x ./railway/run-cron.sh && ./railway/run-cron.sh`

Share the same variables as the App service. Not required if you keep `QUEUE_CONNECTION=sync` for a simple demo.

## Files

| Path | Role |
|------|------|
| `railway.toml` | Build (`npm run build`) + pre-deploy migrate/seed/cache |
| `railway/init-app.sh` | Migrate, seed if empty, `storage:link`, optimize |
| `railway/run-worker.sh` | Queue worker |
| `railway/run-cron.sh` | Scheduler loop |

## Notes

- First deploy: set `RUN_SEEDERS=true`, deploy, then set it back to `false` (seeders are not fully idempotent).
- Change the seeded admin password after go-live.
- Large media in `public/assets/images/majiworks` is fine on Railway (not serverless size-limited like Vercel).
