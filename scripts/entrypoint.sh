#!/usr/bin/env bash
set -euo pipefail

echo "[entrypoint] Starting Laravel container..."

cd /var/www/html

# Ensure dependencies (in case of mounted volume overwrite)
if [ ! -d vendor ]; then
  echo "[entrypoint] vendor/ missing, running composer install..."
  composer install --no-dev --prefer-dist --no-progress --no-interaction
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
else
  echo "[entrypoint] APP_KEY already present."
fi

# Ensure JWT_SECRET exists for token generation
if ! grep -q '^JWT_SECRET=' .env; then
  JWT=$(php -r "echo bin2hex(random_bytes(32));")
  echo "JWT_SECRET=${JWT}" >> .env
  echo "[entrypoint] Generated JWT_SECRET." 
elif grep -q '^JWT_SECRET=$' .env; then
  JWT=$(php -r "echo bin2hex(random_bytes(32));")
  sed -i "s#^JWT_SECRET=.*#JWT_SECRET=${JWT}#" .env
  echo "[entrypoint] Filled empty JWT_SECRET." 
fi

php -v
php artisan --version || true

echo "[entrypoint] Starting HTTP server on 0.0.0.0:${PORT:-8000}"
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}" >/tmp/server.log 2>&1 &
SERVER_PID=$!

# Run migrations in foreground after server start so port is open for Render detection
set +e
if php artisan list | grep -q 'cockroach:migrate'; then
  echo "[entrypoint] Running CockroachDB migrations..."
  php artisan cockroach:migrate || echo "[entrypoint] Migration step returned non-zero (continuing)."
fi
set -e

# (Optional) config cache (skip route cache for now due to closure routes)
php artisan config:clear || true
php artisan route:clear || true
php artisan config:cache || true
echo "[entrypoint] Skipping route:cache (closure routes present)."

echo "[entrypoint] Initialization complete; attaching to server process (PID ${SERVER_PID})."
tail -n 50 -f /tmp/server.log &
wait ${SERVER_PID}
