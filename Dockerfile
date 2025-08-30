FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Database config (will be overridden by render.yaml env vars)
ENV DB_CONNECTION pgsql
ENV DB_HOST laravelecomapi-15349.j77.cockroachlabs.cloud
ENV DB_PORT 26257
ENV DB_DATABASE laravelecomapi-15349.defaultdb
ENV DB_USERNAME ehsan
ENV DB_PASSWORD mCRW9kBfMfUTlGCXaCswOA
ENV DB_SSLMODE require

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Expose port 80
EXPOSE 80

CMD ["/start.sh"]
