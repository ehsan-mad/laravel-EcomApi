#!/usr/bin/env bash

echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html

echo "Setting up environment file..."
cp /var/www/html/.env.example /var/www/html/.env

echo "Generating application key..."
php artisan key:generate --force

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running CockroachDB migrations..."
php artisan cockroach:migrate

echo "Laravel deployment completed successfully!"
