#!/bin/sh
set -e

php artisan config:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache

exec php artisan octane:frankenphp --workers=auto --max-requests=1