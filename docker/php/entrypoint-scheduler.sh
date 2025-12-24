#!/usr/bin/env sh
set -e

cd /var/www/backend

echo "==> Scheduler: waiting for vendor/autoload.php..."
until [ -f vendor/autoload.php ]; do
  sleep 1
done

echo "==> Scheduler: starting schedule:work..."
exec php artisan schedule:work
