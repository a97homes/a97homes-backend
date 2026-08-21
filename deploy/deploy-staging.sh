#!/usr/bin/env bash
#
# A97Infinity - STAGING deploy (in-place git checkout)
#
# Usage: deploy-staging.sh [git-ref]      default ref: origin/staging
# Runs as: root (needs systemctl reload php-fpm + supervisorctl)
#
set -Eeuo pipefail

APP=/var/www/A97Infinity
BRANCH=staging
REF=${1:-origin/$BRANCH}
HEALTH_URL=https://api-staging.a97homes.com/api/V1/countries
LOG=/var/log/a97-deploy-staging.log
STATE=/var/www/deploy/.staging_previous_sha

exec > >(tee -a "$LOG") 2>&1
echo "=============================================================="
echo "[$(date -Is)] STAGING deploy start (ref=$REF)"

as_web() { sudo -u www-data env HOME=/tmp XDG_CONFIG_HOME=/tmp "$@"; }

cd "$APP"
PREV_SHA=$(git rev-parse --short HEAD)
echo "current sha: $PREV_SHA"
echo "$PREV_SHA" > "$STATE"

as_web php artisan down --retry=30 || true
trap 'as_web php artisan up || true' EXIT

git fetch --prune origin "$BRANCH"
git checkout -f -B "$BRANCH" "$REF"
git reset --hard "$REF"
SHA=$(git rev-parse --short HEAD)
echo "deploying sha: $SHA ($(git log -1 --pretty=%s))"

COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader --no-progress

chown -R www-data:www-data "$APP"
chmod -R 775 "$APP/storage" "$APP/bootstrap/cache"

as_web php artisan migrate --force --no-interaction

as_web php artisan config:clear
as_web php artisan config:cache
as_web php artisan route:cache
as_web php artisan view:cache
as_web php artisan event:cache

systemctl reload php8.4-fpm
supervisorctl restart A97Infinity-staging-worker: >/dev/null

as_web php artisan up
trap - EXIT

sleep 2
CODE=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 "$HEALTH_URL" || echo 000)
echo "health $HEALTH_URL -> HTTP $CODE"
if [ "$CODE" != "200" ]; then
  echo "[FAIL] staging health check failed. Roll back with:"
  echo "  /var/www/deploy/rollback-staging.sh $PREV_SHA"
  exit 1
fi

echo "[$(date -Is)] STAGING deploy OK sha=$SHA"
