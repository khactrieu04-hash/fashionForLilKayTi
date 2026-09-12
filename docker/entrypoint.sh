#!/bin/sh
set -e

echo "=== Starting Laravel Container Initialization ==="

# 1. Update Nginx port dynamically if Render provides $PORT
if [ -n "$PORT" ]; then
    echo "Configuring Nginx to listen on port $PORT..."
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf
    sed -i "s/listen \[::\]:80;/listen \[::\]:$PORT;/g" /etc/nginx/http.d/default.conf
fi

# 2. Default SSL CA path for TiDB Cloud if not specified
if [ -z "$MYSQL_ATTR_SSL_CA" ]; then
    export MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt
fi

# 3. Create storage symlink
echo "Linking storage..."
php artisan storage:link --force || true

# 4. Clear and rebuild caches for production performance
echo "Caching configuration and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# 5. Run Database Migrations if requested
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "Warning: Migration failed. Check DB connection settings."
fi

# 6. Run Database Seeder if requested
if [ "$RUN_SEEDER" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force || echo "Warning: Seeder failed or already seeded."
fi

echo "=== Initialization Complete. Starting Nginx and PHP-FPM ==="
exec /usr/bin/supervisord -c /etc/supervisord.conf
