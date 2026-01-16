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

echo "==> Ensuring storage/cache directories exist..."
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chmod -R 775 storage bootstrap/cache >/dev/null 2>&1 || true

echo "==> Installing composer dependencies (if needed)..."
if [ ! -f "vendor/autoload.php" ]; then
  composer install --no-interaction --prefer-dist
fi

echo "==> Ensuring Filament Shield & Spatie Permission are installed..."
if ! grep -q '"bezhansalleh/filament-shield"' composer.lock 2>/dev/null || ! grep -q '"spatie/laravel-permission"' composer.lock 2>/dev/null; then
  echo "==> Updating composer.lock for RBAC packages..."
  composer update bezhansalleh/filament-shield spatie/laravel-permission --no-interaction --prefer-dist
fi

echo "==> Finalizing composer install..."
if [ ! -f "vendor/autoload.php" ]; then
  composer install --no-interaction --prefer-dist
fi

echo "==> Generating app key (if missing)..."
php artisan key:generate --force || true

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Creating storage link..."
php artisan storage:link >/dev/null 2>&1 || true

echo "==> Seeding database (if empty)..."
if ! php artisan tinker --execute="echo \App\Models\BlogArticle::query()->exists() ? 'yes' : 'no';" | grep -q yes; then
  php artisan db:seed --force
else
  echo "Skip seeding: demo data already exists."
fi

echo "==> Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8080

echo "==> Entrypoint script completed."
