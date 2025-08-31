#!/usr/bin/env bash
set -euo pipefail

START_TIME_RAW=$(date +%s%3N 2>/dev/null || date +%s)
PHASE_START=$START_TIME_RAW
log_phase() {
  local label="$1"; shift || true
  local now=$(date +%s%3N 2>/dev/null || date +%s)
  local delta=$((now-PHASE_START))
  local total=$((now-START_TIME_RAW))
  echo "[entrypoint][${total}ms][+${delta}ms] ${label}"
  PHASE_START=$now
}

echo "[entrypoint] Starting Laravel container..." && log_phase "container start" >/dev/null

cd /var/www/html

# Ensure dependencies (in case of mounted volume overwrite)
if [ ! -d vendor ]; then
  echo "[entrypoint] vendor/ missing, running composer install..."
  composer install --no-dev --prefer-dist --no-progress --no-interaction
  log_phase "composer install"
else
  log_phase "dependencies present"
fi

# Guarantee .env exists
if [ ! -f .env ]; then
  cp .env.example .env || touch .env
fi

# Generate APP_KEY manually if empty/missing (avoid artisan dependency timing)
if ! grep -q '^APP_KEY=' .env; then
  echo 'APP_KEY=' >> .env
fi
if grep -q '^APP_KEY=$' .env; then
  KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
  sed -i "s#^APP_KEY=.*#APP_KEY=${KEY}#" .env
  echo "[entrypoint] Generated APP_KEY manually."
  log_phase "app key generated"
else
  echo "[entrypoint] APP_KEY already present."
  log_phase "app key exists"
fi

# Ensure JWT_SECRET exists for token generation
if ! grep -q '^JWT_SECRET=' .env; then
  JWT=$(php -r "echo bin2hex(random_bytes(32));")
  echo "JWT_SECRET=${JWT}" >> .env
  echo "[entrypoint] Generated JWT_SECRET." 
  log_phase "jwt secret generated"
elif grep -q '^JWT_SECRET=$' .env; then
  JWT=$(php -r "echo bin2hex(random_bytes(32));")
  sed -i "s#^JWT_SECRET=.*#JWT_SECRET=${JWT}#" .env
  echo "[entrypoint] Filled empty JWT_SECRET." 
  log_phase "jwt secret filled"
fi

php -v >/dev/null 2>&1 || true
php artisan --version >/dev/null 2>&1 || true
log_phase "php & artisan version check"

echo "[entrypoint] Starting HTTP server on 0.0.0.0:${PORT:-8000}"
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}" >/tmp/server.log 2>&1 &
SERVER_PID=$!
log_phase "http server launched"

# Run migrations in foreground after server start so port is open for Render detection
set +e
if php artisan list | grep -q 'cockroach:migrate'; then
  echo "[entrypoint] Ensuring CockroachDB schema..."
  if [ "${COCKROACH_BOOTSTRAP:-0}" = "1" ]; then
    php artisan cockroach:migrate --bootstrap || echo "[entrypoint] cockroach:migrate --bootstrap returned non-zero (continuing)."
  else
    php artisan cockroach:migrate || echo "[entrypoint] cockroach:migrate returned non-zero (continuing)."
  fi
  log_phase "schema ensure${COCKROACH_BOOTSTRAP:+ + bootstrap}"
fi
set -e

# Seed database (idempotent; seeder skips if data exists)
echo "[entrypoint] Seeding database (DatabaseSeeder)..."
php artisan db:seed --force || echo "[entrypoint] db:seed failed (continuing)."
log_phase "db seed"

# (Optional) config cache (skip route cache for now due to closure routes)
php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan config:cache >/dev/null 2>&1 || true
log_phase "config cache"
echo "[entrypoint] Skipping route:cache (closure routes present)."

FINAL_NOW=$(date +%s%3N 2>/dev/null || date +%s)
TOTAL_MS=$((FINAL_NOW-START_TIME_RAW))
echo "[entrypoint] Initialization complete in ${TOTAL_MS}ms; attaching to server process (PID ${SERVER_PID})."
tail -n 50 -f /tmp/server.log &
wait ${SERVER_PID}
