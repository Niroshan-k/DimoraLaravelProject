# Use Composer image to get composer
FROM composer:latest as composer

# Final PHP image
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    pkg-config \
    libssl-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install PHP MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copy composer from the composer image
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Install Node.js (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create storage symlink for Laravel file uploads
RUN php artisan storage:link || true

# Set permissions for storage and cache
RUN chmod -R 775 storage bootstrap/cache

# Clear Laravel config cache
RUN php artisan config:clear

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port 10000