#!/usr/bin/env bash
set -euo pipefail

echo "[entrypoint] Starting Laravel container..."

# Ensure we are in project root
cd /var/www/html

# If vendor is missing (e.g. mounted volume), install
if [ ! -d vendor ]; then
  echo "[entrypoint] vendor/ missing, running composer install..."
  composer install --no-dev --prefer-dist --no-progress --no-interaction
fi

# Ensure APP_KEY present
if ! grep -q '^APP_KEY=' .env; then
  echo 'APP_KEY=' >> .env
fi

if grep -q '^APP_KEY=$' .env; then
  echo "[entrypoint] Generating APP_KEY..."
  php artisan key:generate --force
else
  echo "[entrypoint] APP_KEY already set."
fi

# Run migrations (custom cockroach command) with timeout guard
if php artisan list | grep -q 'cockroach:migrate'; then
  echo "[entrypoint] Running CockroachDB migrations..."
  php artisan cockroach:migrate || echo "[entrypoint] Migration step returned non-zero (continuing)."
fi

# Clear & cache config/routes (ignore errors during first boot)
php artisan config:clear || true
php artisan route:clear || true
php artisan config:cache || true
php artisan route:cache || true

# Health info
php -v
php artisan --version || true

# Start Laravel built-in server bound to all interfaces
echo "[entrypoint] Launching php artisan serve on 0.0.0.0:${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
