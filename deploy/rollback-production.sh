#!/usr/bin/env bash
#
# A97Infinity - PRODUCTION rollback (atomic symlink swap)
#
# Usage: rollback-production.sh [release-timestamp]   default: previous release
#
# NOTE: database migrations are NOT reverted. If the failed release added a
# destructive migration, restore the database from backup separately.
#
set -Eeuo pipefail

BASE=/var/www/A97Infinity-production
RELEASES=$BASE/releases
SHARED=$BASE/shared
CURRENT=$BASE/current
HEALTH_HOST=api.a97homes.com
HEALTH_PATH=/api/V1/countries

TARGET=${1:-}
CUR=$(basename "$(readlink -f "$CURRENT")")

if [ -z "$TARGET" ]; then
  TARGET=$(ls -1dt -- "$RELEASES"/*/ | sed 's#.*/\([^/]*\)/$#\1#' | grep -v "^$CUR$" | head -1)
fi

if [ ! -d "$RELEASES/$TARGET" ]; then
  echo "release '$TARGET' not found. Available releases:"
  ls -1 "$RELEASES"
  exit 1
fi

echo "rolling back: $CUR -> $TARGET"
ln -sfn "$RELEASES/$TARGET" "$CURRENT"
chown -h www-data:www-data "$CURRENT"
echo "$TARGET" > "$SHARED/CURRENT_RELEASE"
echo "$CUR" > "$SHARED/PREVIOUS_RELEASE"

systemctl reload php8.4-fpm
supervisorctl restart A97Infinity-production-worker: >/dev/null
sleep 3

if [ -f "/etc/letsencrypt/live/$HEALTH_HOST/fullchain.pem" ]; then
  CODE=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 \
    --resolve "$HEALTH_HOST:443:127.0.0.1" "https://$HEALTH_HOST$HEALTH_PATH" || echo 000)
else
  CODE=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 \
    -H "Host: $HEALTH_HOST" "http://127.0.0.1$HEALTH_PATH" || echo 000)
fi

echo "health -> HTTP $CODE"
if [ "$CODE" != "200" ]; then
  echo "[FAIL] rollback target is also unhealthy"
  exit 1
fi
echo "rollback OK -> $TARGET"
