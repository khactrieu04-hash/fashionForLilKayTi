#!/bin/sh
set -e

echo "=== Starting Laravel Container Initialization ==="

# Render does not include the local .env file in the image. Use the configured
# key when available, otherwise create a process-local key so Laravel can boot.
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    echo "APP_KEY was not provided; generated a temporary runtime key."
fi

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

# 4. Clear and optimize caches
echo "Optimizing caches..."
php artisan optimize:clear || true
php artisan route:cache || true
php artisan view:cache || true

# 5. Run Database Migrations and Seeders automatically
echo "Running database migrations..."
php artisan migrate --force || echo "Warning: Migration failed. Check DB connection settings."

echo "Running database seeders..."
php artisan db:seed --force || echo "Warning: Seeder failed or already seeded."

echo "=== Initialization Complete. Starting Nginx and PHP-FPM ==="
exec /usr/bin/supervisord -c /etc/supervisord.conf
