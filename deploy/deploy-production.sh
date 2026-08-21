#!/usr/bin/env bash
#
# A97Infinity - PRODUCTION deploy (release-based, zero-downtime, auto-rollback)
#
# Usage: deploy-production.sh [git-ref]      default ref: origin/main
# Runs as: root (needs systemctl reload php-fpm + supervisorctl)
#
set -Eeuo pipefail

BASE=/var/www/A97Infinity-production
RELEASES=$BASE/releases
SHARED=$BASE/shared
CURRENT=$BASE/current
KEEP=3
BRANCH=main
REF=${1:-origin/$BRANCH}
REPO=git@github.com:BNhashem16/A97Infinity.git
HEALTH_HOST=api.a97homes.com
HEALTH_PATH=/api/V1/countries
LOG=/var/log/a97-deploy-production.log
RUN_AS=www-data

exec > >(tee -a "$LOG") 2>&1
echo "=============================================================="
echo "[$(date -Is)] PRODUCTION deploy start (ref=$REF)"

as_web() { sudo -u "$RUN_AS" env HOME=/tmp XDG_CONFIG_HOME=/tmp "$@"; }

health_check() {
  local code
  if [ -f "/etc/letsencrypt/live/$HEALTH_HOST/fullchain.pem" ]; then
    code=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 \
      --resolve "$HEALTH_HOST:443:127.0.0.1" "https://$HEALTH_HOST$HEALTH_PATH" || echo 000)
  else
    code=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 \
      -H "Host: $HEALTH_HOST" "http://127.0.0.1$HEALTH_PATH" || echo 000)
  fi
  echo "$code"
}

PREVIOUS=""
if [ -L "$CURRENT" ]; then PREVIOUS=$(readlink -f "$CURRENT"); fi
echo "previous release: ${PREVIOUS:-none}"

TS=$(date +%Y%m%d%H%M%S)
NEW=$RELEASES/$TS
mkdir -p "$RELEASES"

echo "--- 1. build new release $TS ---"
if [ -n "$PREVIOUS" ]; then
  cp -a "$PREVIOUS" "$NEW"
else
  git clone --branch "$BRANCH" "$REPO" "$NEW"
fi

cd "$NEW"
echo "--- 2. fetch code ---"
# git refuses to operate on a repo owned by another user; take ownership for the
# build, then hand the release back to www-data in step 5.
chown root:root "$NEW"
chown -R root:root "$NEW/.git"
git fetch --prune origin "$BRANCH"
git checkout -B "$BRANCH" "$REF"
git reset --hard "$REF"
git clean -fd -e vendor -e node_modules -e storage -e .env
SHA=$(git rev-parse --short HEAD)
echo "release $TS => $SHA ($(git log -1 --pretty=%s))"

echo "--- 3. link shared state ---"
rm -rf "$NEW/storage"
ln -sfn "$SHARED/storage" "$NEW/storage"
ln -sfn "$SHARED/.env" "$NEW/.env"
rm -f "$NEW/public/storage"
ln -sfn "$SHARED/storage/app/public" "$NEW/public/storage"

echo "--- 4. composer install (no-dev) ---"
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress

echo "--- 5. permissions ---"
chown -R www-data:www-data "$NEW"
chmod -R 775 "$NEW/bootstrap/cache"
chmod 755 "$NEW/artisan"

echo "--- 6. migrations ---"
as_web php artisan migrate --force --no-interaction

echo "--- 7. rebuild caches ---"
as_web php artisan config:clear
as_web php artisan config:cache
as_web php artisan route:cache
as_web php artisan view:cache
as_web php artisan event:cache

echo "--- 8. atomic switch ---"
ln -sfn "$NEW" "$CURRENT"
chown -h www-data:www-data "$CURRENT"
echo "$TS" > "$SHARED/CURRENT_RELEASE"
if [ -n "$PREVIOUS" ]; then basename "$PREVIOUS" > "$SHARED/PREVIOUS_RELEASE"; fi

echo "--- 9. reload php-fpm (clears opcache) ---"
systemctl reload php8.4-fpm

echo "--- 10. restart queue workers ---"
supervisorctl restart A97Infinity-production-worker: >/dev/null

echo "--- 11. health check ---"
sleep 3
CODE=$(health_check)
echo "health $HEALTH_PATH -> HTTP $CODE"
if [ "$CODE" != "200" ]; then
  echo "[FAIL] health check failed"
  if [ -n "$PREVIOUS" ]; then
    echo "[ROLLBACK] reverting to $(basename "$PREVIOUS")"
    ln -sfn "$PREVIOUS" "$CURRENT"
    chown -h www-data:www-data "$CURRENT"
    basename "$PREVIOUS" > "$SHARED/CURRENT_RELEASE"
    systemctl reload php8.4-fpm
    supervisorctl restart A97Infinity-production-worker: >/dev/null
    sleep 3
    echo "[ROLLBACK] health after revert -> HTTP $(health_check)"
  fi
  exit 1
fi

echo "--- 12. prune old releases (keep $KEEP) ---"
cd "$RELEASES"
ls -1dt -- */ 2>/dev/null | tail -n +$((KEEP + 1)) | while read -r old; do
  old=${old%/}
  case "$old" in
    2[0-9][0-9][0-9][0-1][0-9]*)
      echo "removing old release $old"
      rm -rf -- "${RELEASES:?}/$old"
      ;;
    *)
      echo "skipping unexpected directory: $old"
      ;;
  esac
done

echo "[$(date -Is)] PRODUCTION deploy OK release=$TS sha=$SHA"
