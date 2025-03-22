FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    git

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Install Node.js and npm
# Ensure latest npm version || 
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest 

# Configure Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader

# Copy package.json and package-lock.json
COPY package.json package-lock.json* vite.config.js ./

# Copy the rest of the application code
COPY . .

# Install Node.js dependencies and build frontend assets
RUN npm install
RUN npm run build

# Generate optimized autoloader and run other scripts
RUN composer dump-autoload --optimize && \
    composer run-script post-autoload-dump

# Create symlink for storage
RUN php artisan storage:link

# Verify Vite build assets exist
RUN ls -la public/build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# Create .env file from example if not exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate application key if not exists
RUN php artisan key:generate --force