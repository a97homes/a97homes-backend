# Deployment

A97Infinity runs two fully separated environments on one server. This document
describes the architecture, the automated deployment flow, and the manual
procedures for deploying, rolling back, and troubleshooting.

No secret values appear in this file. Secrets live in GitHub Environment secrets
and in server-side `.env` files that are never committed.

---

## 1. Architecture

Both environments live on the same host (`167.172.218.87`) but share nothing
except the operating system, nginx, PHP-FPM master, and the PostgreSQL server.
Code, `.env`, database, cache, queue, workers, scheduler, logs, domain and
deployment pipeline are separate.

| | Staging | Production |
|---|---|---|
| Branch | `staging` | `main` |
| Path | `/var/www/A97Infinity` | `/var/www/A97Infinity-production` |
| Layout | in-place git checkout | release directories + `current` symlink |
| Domain | `https://api-staging.a97homes.com` | `https://api.a97homes.com` |
| Document root | `/var/www/A97Infinity/public` | `/var/www/A97Infinity-production/current/public` |
| PHP-FPM pool | `www` (`php8.4-fpm.sock`) | `a97-production` (`php8.4-fpm-production.sock`) |
| Database | `a97homes` (user `a97homes`) | `a97homes_production` (user `a97homes_prod`) |
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| Cache / queue / session | `database` | `database` |
| Cache prefix | default | `a97homes_production` |
| Queue workers | supervisor `A97Infinity-staging-worker` (2) | supervisor `A97Infinity-production-worker` (2) |
| Scheduler | root crontab | `/etc/cron.d/a97infinity-production` (runs as `www-data`) |
| Laravel log | `storage/logs/laravel.log` (single) | `shared/storage/logs/laravel-YYYY-MM-DD.log` (daily) |
| Deploy log | `/var/log/a97-deploy-staging.log` | `/var/log/a97-deploy-production.log` |

Redis is **not** installed and **not** used. Cache, queue and session all use the
database driver. Octane/RoadRunner is present in `composer.json` but is **not
running** — nginx talks to PHP-FPM directly. Do not add `octane:reload` to a
deploy script unless Octane is actually started as a service.

There is no frontend build step: this is an API-only application with no
`public/build` output. `npm`/Vite are not part of deployment.

### Production release layout

```
/var/www/A97Infinity-production/
├── current -> releases/20260821225012        # atomic symlink, nginx root
├── releases/
│   ├── 20260821224004/
│   └── 20260821225012/
└── shared/
    ├── .env                                  # the only production .env
    ├── CURRENT_RELEASE
    ├── PREVIOUS_RELEASE
    └── storage/                              # logs, cache, uploads survive deploys
```

Each release symlinks `storage` → `shared/storage`, `.env` → `shared/.env`, and
`public/storage` → `shared/storage/app/public`. Only the last 3 releases are kept.

---

## 2. Branch → environment mapping

```
push to staging  ──▶ GitHub Actions "Deploy to Staging"    ──▶ /var/www/A97Infinity
push to main     ──▶ GitHub Actions "Deploy to Production" ──▶ /var/www/A97Infinity-production
```

A push to `staging` can never touch production, and a push to `main` can never
touch staging: each workflow only triggers on its own branch, and each GitHub
Environment has a deployment branch policy that rejects any other branch.

---

## 3. Deployment flow

### Staging (`push` to `staging`)

1. GitHub Actions job starts in Environment `Staging`.
2. SSH as `deployer` → `sudo -n /var/www/deploy/deploy-staging.sh origin/staging`.
3. Script: record current SHA → `php artisan down` → `git fetch` + `reset --hard`
   → `composer install` → fix ownership/permissions → `php artisan migrate --force`
   → rebuild config/route/view/event caches → reload PHP-FPM → restart queue
   workers → `php artisan up`.
4. Script health check: `GET /api/V1/countries` must return 200.
5. Workflow health check from the GitHub runner against the public URL.

Staging deploy causes a short maintenance window (`artisan down`) — acceptable
for staging and keeps the procedure simple.

### Production (`push` to `main`)

1. GitHub Actions job starts in Environment `production` (branch policy: `main` only).
2. SSH as `deployer` → `sudo -n /var/www/deploy/deploy-production.sh origin/main`.
3. Script builds a **new release directory** (copied from the current release so
   `vendor/` is warm), checks out the target ref, links shared state, runs
   `composer install --no-dev --optimize-autoloader`, sets permissions, runs
   `php artisan migrate --force`, and rebuilds all caches — all while the old
   release is still serving traffic.
4. Atomic switch: `current` symlink is repointed, PHP-FPM is reloaded (clearing
   opcache — nginx uses `$realpath_root`, so each release has its own opcache
   keys), queue workers restart.
5. Health check `GET /api/V1/countries`. **If it is not 200 the script rolls the
   symlink back to the previous release automatically and exits non-zero.**
6. Old releases beyond the newest 3 are pruned.
7. Workflow health check from the GitHub runner against the public URL.

There is no `artisan down` in production — the switch is atomic, so deploys are
effectively zero-downtime.

---

## 4. GitHub configuration

### Workflows

| File | Trigger | Environment |
|---|---|---|
| `.github/workflows/deploy-staging.yml` | push to `staging`, manual | `Staging` |
| `.github/workflows/deploy-production.yml` | push to `main`, manual | `production` |
| `.github/workflows/rollback-production.yml` | manual only | `production` |

### Required secrets

Both environments define the same four secret **names** with environment-specific
values. Nothing is stored at repository level.

| Secret | Purpose |
|---|---|
| `SSH_HOST` | Server hostname for that environment |
| `SSH_PORT` | SSH port |
| `SSH_USER` | Deploy user (`deployer`) |
| `SSH_PRIVATE_KEY` | Private half of the deploy key installed in `/home/deployer/.ssh/authorized_keys` |

### Deploy user

CI does **not** log in as `root`. It logs in as the unprivileged `deployer` user,
which may run exactly four commands as root and nothing else
(`/etc/sudoers.d/deployer`):

```
/var/www/deploy/deploy-staging.sh
/var/www/deploy/deploy-production.sh
/var/www/deploy/rollback-staging.sh
/var/www/deploy/rollback-production.sh
```

The scripts are owned by `root:root` with mode `750`, so `deployer` can execute
them via sudo but cannot modify them.

---

## 5. Server configuration reference

### nginx

* `/etc/nginx/sites-available/api-staging.a97homes.com` → staging
* `/etc/nginx/sites-available/api.a97homes.com` → production

Production vhost: HTTP→HTTPS redirect, Let's Encrypt certificate, HSTS,
`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`,
`client_max_body_size 64M`, dotfile and `.env`/`.log`/`.sql`/`.yml` denial,
`fastcgi_read_timeout 120s`.

### PHP-FPM

`/etc/php/8.4/fpm/pool.d/a97-production.conf` — dedicated pool for production:
`pm.max_children=6`, `memory_limit=256M`, `upload_max_filesize=64M`,
`display_errors=off`, `open_basedir` restricted to the production tree.

Staging keeps the default `www` pool.

### Supervisor

* `/etc/supervisor/conf.d/A97Infinity-staging-worker.conf` — 2 processes
* `/etc/supervisor/conf.d/A97Infinity-production-worker.conf` — 2 processes,
  command points at `current/artisan` so a release switch plus restart picks up
  new code.

```bash
supervisorctl status
supervisorctl restart A97Infinity-production-worker:
```

### Scheduler

* Staging: root crontab, `* * * * * cd /var/www/A97Infinity && php artisan schedule:run`
* Production: `/etc/cron.d/a97infinity-production`, runs as `www-data`, logs to
  `shared/storage/logs/scheduler.log`

Each environment runs its own scheduler against its own database, so scheduled
jobs never execute twice against the same data.

### SSL

Both certificates are managed by certbot with the packaged systemd renewal
timer. Verify with `certbot certificates`.

---

## 6. Manual operations

All commands run on the server as `root` (or as `deployer` with `sudo -n`).

### Deploy manually

```bash
/var/www/deploy/deploy-staging.sh                 # deploy origin/staging
/var/www/deploy/deploy-production.sh              # deploy origin/main
/var/www/deploy/deploy-production.sh <sha-or-ref> # deploy a specific commit
```

### Roll back

```bash
# production: instant symlink swap back to the previous release
/var/www/deploy/rollback-production.sh
/var/www/deploy/rollback-production.sh 20260821224004   # a specific release
ls /var/www/A97Infinity-production/releases            # what is available

# staging: git reset to the SHA recorded by the last deploy
/var/www/deploy/rollback-staging.sh
/var/www/deploy/rollback-staging.sh <sha>
```

Rollback can also be triggered from GitHub: **Actions → Rollback Production →
Run workflow** (optionally passing a release timestamp).

**Database migrations are not reverted by rollback.** Keep migrations
backward-compatible: add columns before using them, drop them in a later
release, never rename or drop in the same deploy that ships the new code.

### Verify a deployment

```bash
cd /var/www/A97Infinity-production/current   # or /var/www/A97Infinity
sudo -u www-data php artisan about
sudo -u www-data php artisan migrate:status | tail
curl -sS -o /dev/null -w '%{http_code}\n' https://api.a97homes.com/api/V1/countries
supervisorctl status
systemctl status php8.4-fpm nginx --no-pager
cat /var/www/A97Infinity-production/shared/CURRENT_RELEASE
```

### Seeding

Production was migrated and seeded with **roles and permissions only**
(`UserRoleSeeder`, `PermissionSeeder`). The remaining seeders create demo
content and must not be run against production. To seed a specific dataset
deliberately:

```bash
cd /var/www/A97Infinity-production/current
sudo -u www-data php artisan db:seed --class=CountrySeeder --force
```

`PermissionSeeder` is safe to re-run on every deploy: it discovers the
permissions from the route middleware and the controllers, inserts the ones that
are missing, and drops only the permissions the code no longer guards with **and**
nobody holds - a permission assigned to a user or granted to a non admin role is
always kept. Existing rows are never re-created, so role and user assignments
survive:

```bash
cd /var/www/A97Infinity-production/current
sudo -u www-data php artisan db:seed --class=PermissionSeeder --force
```

Staging may be reseeded on demand — it is no longer wiped automatically on every
deploy:

```bash
cd /var/www/A97Infinity && sudo -u www-data php artisan migrate:fresh --seed
```

---

## 7. Logs

| What | Staging | Production |
|---|---|---|
| Laravel | `/var/www/A97Infinity/storage/logs/laravel.log` | `/var/www/A97Infinity-production/shared/storage/logs/laravel-*.log` |
| Queue workers | `/var/www/A97Infinity/storage/logs/worker.log` | `/var/www/A97Infinity-production/shared/storage/logs/worker.log` |
| Scheduler | discarded (`>/dev/null`) | `/var/www/A97Infinity-production/shared/storage/logs/scheduler.log` |
| Deploy | `/var/log/a97-deploy-staging.log` | `/var/log/a97-deploy-production.log` |
| nginx access | `/var/log/nginx/access.log` | `/var/log/nginx/api.a97homes.com-access.log` |
| nginx error | `/var/log/nginx/error.log` | `/var/log/nginx/api.a97homes.com-error.log` |
| PHP-FPM | `/var/log/php8.4-fpm.log` | `/var/log/php8.4-fpm-a97-production.log` |

Every production path contains `A97Infinity-production` or `api.a97homes.com`,
so an error is always attributable to one environment.

---

## 8. Troubleshooting

**Deploy fails at the health check.** Production auto-reverts; read
`/var/log/a97-deploy-production.log` for the failing step, then
`shared/storage/logs/laravel-*.log`.

**`dubious ownership in repository`.** Git refuses to work on a tree owned by
another user. The production script takes ownership of `.git` before git
operations; for staging, `git config --global --add safe.directory <path>` is
already set for root.

**502 Bad Gateway.** PHP-FPM pool down or socket missing:

```bash
systemctl status php8.4-fpm
ls -l /run/php/
tail -50 /var/log/php8.4-fpm-a97-production.log
```

**Changes not visible after deploy.** Opcache is cleared by the PHP-FPM reload in
the script. If you edited files by hand, run `systemctl reload php8.4-fpm` and
`php artisan config:clear && php artisan config:cache`.

**Queued jobs run old code.** Workers are long-lived; restart them:
`supervisorctl restart A97Infinity-production-worker:`.

**`Writing to directory /var/www/.config/psysh is not allowed`.** `php artisan
tinker` needs a writable HOME: `sudo -u www-data env HOME=/tmp php artisan tinker`.

**Out of memory.** The host has 1 vCPU / 2 GB RAM plus a 2 GB swapfile. Check
with `free -m`. Do not raise `numprocs` or `pm.max_children` without measuring.

---

## 9. Server requirements

Ubuntu 24.04 · PHP 8.4 (`bcmath curl ctype dom exif fileinfo gd iconv intl
mbstring openssl pcntl pdo_pgsql pgsql redis tokenizer xml zip`) · PostgreSQL 17
· nginx · supervisor · composer 2 · git · certbot.
