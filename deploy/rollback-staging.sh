#!/usr/bin/env bash
#
# A97Infinity - STAGING rollback (git checkout of a previous commit)
#
# Usage: rollback-staging.sh [sha]   default: sha recorded by the last deploy
#
# NOTE: database migrations are NOT reverted.
#
set -Eeuo pipefail

APP=/var/www/A97Infinity
STATE=/var/www/deploy/.staging_previous_sha
HEALTH_URL=https://api-staging.a97homes.com/api/V1/countries

TARGET=${1:-}
if [ -z "$TARGET" ] && [ -f "$STATE" ]; then TARGET=$(cat "$STATE"); fi
if [ -z "$TARGET" ]; then
  echo "no target sha given and no previous sha recorded at $STATE"
  exit 1
fi

as_web() { sudo -u www-data env HOME=/tmp XDG_CONFIG_HOME=/tmp "$@"; }

cd "$APP"
echo "rolling back staging: $(git rev-parse --short HEAD) -> $TARGET"
git fetch --prune origin staging
git reset --hard "$TARGET"

COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader --no-progress
chown -R www-data:www-data "$APP"
chmod -R 775 "$APP/storage" "$APP/bootstrap/cache"

as_web php artisan config:clear
as_web php artisan config:cache
as_web php artisan route:cache
as_web php artisan view:cache
as_web php artisan event:cache

systemctl reload php8.4-fpm
supervisorctl restart A97Infinity-staging-worker: >/dev/null
sleep 2

CODE=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 "$HEALTH_URL" || echo 000)
echo "health -> HTTP $CODE"
if [ "$CODE" != "200" ]; then
  echo "[FAIL] rollback target is also unhealthy"
  exit 1
fi
echo "rollback OK -> $TARGET"
