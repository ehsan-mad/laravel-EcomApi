#!/bin/bash

# Render build script for Laravel
echo "==> Starting Laravel build for Render..."

# Install PHP dependencies
echo "==> Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Cache Laravel configuration
echo "==> Caching Laravel configuration..."
php artisan config:cache

# Cache routes
echo "==> Caching routes..."
php artisan route:cache

# Run CockroachDB migrations
echo "==> Running CockroachDB migrations..."
php artisan cockroach:migrate

echo "==> Laravel build completed successfully!"
