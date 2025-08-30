########## Stage 1: Composer Dependencies ##########
FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction

########## Stage 2: Runtime (PHP CLI + built-in server) ##########
FROM php:8.2-cli

# Install needed system packages & PHP extensions (pgsql for CockroachDB)
RUN apt-get update \
	&& apt-get install -y --no-install-recommends libpq-dev git unzip \
	&& docker-php-ext-install pdo pdo_pgsql \
	&& rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy application code
COPY . .
# Copy vendor from build stage
COPY --from=vendor /app/vendor ./vendor

# Environment defaults (override in Render dashboard)
ENV APP_ENV=production \
	APP_DEBUG=false \
	LOG_CHANNEL=stderr \
	DB_CONNECTION=pgsql \
	DB_SSLMODE=require \
	PORT=8000

# Ensure storage & cache dirs exist with correct permissions
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
	&& chown -R www-data:www-data storage bootstrap/cache \
	&& chmod -R 775 storage bootstrap/cache

# Provide .env if missing so key:generate works; keep user overrides via env vars
RUN set -eux; \
	if [ ! -f .env ]; then cp .env.example .env || true; fi; \
	grep -q '^APP_KEY=' .env || echo 'APP_KEY=' >> .env

# Copy entrypoint script
COPY scripts/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000

CMD ["/entrypoint.sh"]
