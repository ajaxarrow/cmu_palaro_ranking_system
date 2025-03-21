# Use the official PHP image as a base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Create directory structure with correct permissions
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache

# Install dependencies as the root user to avoid permission issues
RUN composer install --no-scripts --no-autoloader

# Copy the application code
COPY . .

# Generate the autoloader and run scripts now that all code is available
RUN composer dump-autoload --optimize
RUN composer run-script post-autoload-dump

# Set appropriate file permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 storage bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
