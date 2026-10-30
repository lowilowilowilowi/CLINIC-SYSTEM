FROM composer:2 AS dependencies

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts

COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

FROM node:22-bookworm-slim AS assets

WORKDIR /app

COPY . .
COPY --from=dependencies /app/vendor ./vendor
RUN npm install --no-audit --no-fund && npm run build

FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev libpq-dev \
    && docker-php-ext-install mbstring opcache pdo_pgsql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY --from=dependencies /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY docker/entrypoint.sh /usr/local/bin/clinic-entrypoint

RUN chmod +x /usr/local/bin/clinic-entrypoint \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT ["/usr/local/bin/clinic-entrypoint"]