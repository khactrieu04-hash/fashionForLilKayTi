# ==========================================
# Giai đoạn 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:18-alpine AS frontend
WORKDIR /app

# Cài đặt package frontend
COPY package*.json ./
RUN npm install --legacy-peer-deps

# Copy source code và build assets (CSS, JS)
COPY . .
RUN npm run build

# ==========================================
# Giai đoạn 2: PHP-FPM + Nginx Container
# ==========================================
FROM php:8.2-fpm-alpine

# Cài đặt các gói hệ thống và thư viện cần thiết
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    ca-certificates

# Cài đặt các extension PHP cho Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring bcmath gd zip opcache

# Sử dụng php.ini production và điều chỉnh giới hạn tải file
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i 's/memory_limit = 128M/memory_limit = 256M/g' "$PHP_INI_DIR/php.ini" \
    && sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 32M/g' "$PHP_INI_DIR/php.ini" \
    && sed -i 's/post_max_size = 8M/post_max_size = 32M/g' "$PHP_INI_DIR/php.ini"\
    && echo "pdo_mysql.verify_server_cert=0" >> "$PHP_INI_DIR/php.ini"

# Cài đặt Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy toàn bộ mã nguồn vào container
COPY . .

# Copy thư mục build từ Giai đoạn 1
COPY --from=frontend /app/public/build /var/www/html/public/build

# Cài đặt các dependency PHP (bỏ qua dev để tối ưu dung lượng và tốc độ)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Phân quyền cho Laravel ghi log và cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cấu hình Nginx, Supervisor và Entrypoint
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
