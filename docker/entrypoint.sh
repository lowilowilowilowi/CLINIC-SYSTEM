#!/bin/sh
set -eu

cd /var/www/html
port="${PORT:-10000}"

sed -i "s/Listen 80/Listen ${port}/" /etc/apache2/ports.conf
sed -i "s/VirtualHost \*:80/VirtualHost *:${port}/" /etc/apache2/sites-available/000-default.conf

php artisan package:discover --ansi --no-interaction
php artisan migrate --force --no-interaction
php artisan db:seed --class=RoomSeeder --force --no-interaction
php artisan db:seed --class=AdminUserSeeder --force --no-interaction

exec apache2-foreground