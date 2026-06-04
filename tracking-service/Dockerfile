FROM php:8.3-fpm-alpine

# Install core utilities Linux Alpine untuk keperluan kompilasi ekstensi PHP
RUN apk add --no-cache linux-headers build-base

# Sekarang install ekstensi PHP dengan aman beserta sockets
RUN docker-php-ext-install pdo pdo_mysql sockets

# Install Composer resmi versi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependencies saat build, bukan saat runtime
RUN composer install --no-dev --optimize-autoloader

# Perintah otomatis saat container dihidupkan
CMD php artisan serve --host=0.0.0.0 --port=8000