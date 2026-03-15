FROM composer:2.8 AS vendor

WORKDIR /app

COPY . .

RUN mkdir -p bootstrap/cache storage/framework/{cache,sessions,views,testing}

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-req=ext-redis

RUN composer dump-autoload --optimize --no-dev
RUN VIEW_COMPILED_PATH=/app/storage/framework/views php artisan wayfinder:generate --with-form --no-interaction


FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY --from=vendor /usr/local/ /usr/local/

ENV SKIP_WAYFINDER_GENERATE=1

COPY package.json package-lock.json ./

RUN npm ci

COPY --from=vendor /app/vendor ./vendor
COPY --from=vendor /app/resources ./resources
COPY public ./public
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY routes ./routes
COPY artisan ./
COPY composer.json composer.lock ./
COPY vite.config.ts ./
COPY tsconfig.json ./
COPY components.json ./
COPY eslint.config.js ./

RUN npm run build


FROM dunglas/frankenphp:1-php8.4-bookworm

WORKDIR /app

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    redis

COPY --from=vendor /app /app
COPY --from=frontend /app/public/build /app/public/build
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint

RUN chmod +x /usr/local/bin/docker-entrypoint \
    && mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["docker-entrypoint"]

CMD ["frankenphp", "php-server", "--root=/app/public", "--listen=:80"]
