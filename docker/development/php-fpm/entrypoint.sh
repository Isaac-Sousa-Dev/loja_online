#!/bin/sh
set -e

# Install dependencies if vendor/autoload.php is missing
if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "vendor/autoload.php not found, running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Clear configurations to avoid caching issues in development
echo "Clearing configurations..."
php artisan key:generate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run the default command (e.g., php-fpm or bash)
exec "$@"