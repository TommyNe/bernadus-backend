#!/usr/bin/env bash

set -euo pipefail

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is not set. Configure it in .env before starting the containers." >&2
    exit 1
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

echo "Waiting for PostgreSQL at ${DB_HOST:-postgres}:${DB_PORT:-5432}..."

until php -r '
try {
    $dsn = sprintf(
        "pgsql:host=%s;port=%s;dbname=%s",
        getenv("DB_HOST") ?: "postgres",
        getenv("DB_PORT") ?: "5432",
        getenv("DB_DATABASE") ?: "laravel"
    );

    new PDO($dsn, getenv("DB_USERNAME") ?: "laravel", getenv("DB_PASSWORD") ?: "secret");
} catch (Throwable $throwable) {
    fwrite(STDERR, $throwable->getMessage().PHP_EOL);
    exit(1);
}
'; do
    sleep 2
done

php artisan migrate --force --no-interaction
php artisan config:clear --no-interaction
php artisan route:clear --no-interaction
php artisan view:clear --no-interaction

exec "$@"
