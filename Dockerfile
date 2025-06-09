# Use official PHP CLI image as base
FROM php:8.3-cli

# Install required system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install opcache zip

# Install PHP extensions
RUN pecl install swoole && docker-php-ext-enable swoole

# Create working directory
WORKDIR /var/www/html

# Copy and install Composer dependencies first
COPY composer.json composer.lock ./
COPY --no-cache-dir . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate Laravel key
RUN php artisan key:generate

# Expose port and set command
EXPOSE 8080

CMD ["php", "vendor/bin/frankenphp", "serve"]