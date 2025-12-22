#!/usr/bin/env sh
set -e

cd /var/www/backend

echo "==> Ensuring .env exists..."
if [ ! -f ".env" ]; then
  cp .env.example .env
  echo "Copied .env.example -> .env"
fi

echo "==> Waiting for database..."
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" >/dev/null 2>&1
do
  sleep 1
done

echo "==> Installing composer dependencies (if needed)..."
if [ ! -d "vendor" ]; then
  composer install --no-interaction --prefer-dist
fi

echo "==> Generating app key (if missing)..."
php artisan key:generate --force || true

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Creating storage link..."
php artisan storage:link || true

php artisan db:seed --force || true

echo "==> Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8080

echo "==> Entrypoint script completed."
