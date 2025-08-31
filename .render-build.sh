#!/bin/bash
# Render build script for Laravel

echo "Starting Laravel build process..."

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Cache Laravel configuration
echo "Caching Laravel configuration..."
php artisan config:cache

# Cache routes
echo "Caching routes..."
php artisan route:cache

# Run CockroachDB migrations
echo "Running CockroachDB migrations..."
php artisan cockroach:migrate

echo "Build process completed successfully!"
