#!/bin/sh
set -e

echo "==> Iniciando contenedor GOES Renovación de Licencia..."

# Asegurar directorio de datos persistente para SQLite (Render Disk en /var/data)
mkdir -p /var/data
if [ ! -f /var/data/database.sqlite ]; then
    echo "==> Creando base de datos SQLite en /var/data/database.sqlite..."
    touch /var/data/database.sqlite
fi

# Permisos seguros para SQLite y directorios de Laravel
chown -R www-data:www-data /var/data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod 664 /var/data/database.sqlite

# Migraciones y Seeders idempotentes en producción
echo "==> Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "==> Ejecutando sembrado de datos demo (updateOrCreate)..."
php artisan db:seed --force

# Optimización de caché para producción
echo "==> Optimizando configuraciones y rutas de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Aplicación GOES lista. Iniciando servidor web..."
exec "$@"
