FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer v2 (stable & compatible)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create non-root user matching host (avoid permission issues)
ARG UID=1000
ARG GID=1000

RUN groupadd -g ${GID} laravel \
    && useradd -u ${UID} -g laravel -m laravel

WORKDIR /var/www


# ---------- START: For production set up ---------- 

# Copy Laravel app from src folder
COPY src/ .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate APP_KEY for Laravel
RUN php artisan key:generate

# Expose Render port
EXPOSE 10000

# Run Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]

# ---------- END: For production set up ---------- 

USER laravel
