#!/bin/bash
set -e

if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf
fi

if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" /var/www/html/.env
sed -i "s|# DB_DATABASE=.*|DB_DATABASE=/var/www/html/database/database.sqlite|" /var/www/html/.env

if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    php artisan key:generate --force
fi

mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

php artisan config:clear
php artisan migrate --force --seed

echo "=== IT Repair System is online and ready! ==="

exec apache2-foreground