# ---------------------------------------
# Stage 1: Build the PHP dependencies
# ---------------------------------------
FROM php:8.2-fpm AS build

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy only the files needed for composer first (for better caching)
COPY composer.json composer.lock ./

# Copy the rest of the application code
COPY . .

# Install PHP dependencies (no-dev for production)
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-plugins


# ---------------------------------------
# Stage 2: Production container
# ---------------------------------------
FROM php:8.2-fpm

# Install system dependencies, NGINX, and Supervisor
RUN apt-get update && apt-get install -y \
    nginx supervisor libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Copy application from build stage
COPY --from=build /var/www/html /var/www/html

# Set working directory
WORKDIR /var/www/html

# Set permissions for Laravel folders
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy the NGINX and Supervisor configuration files
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80 for HTTP
EXPOSE 80

# Start Supervisor (which runs both NGINX and PHP-FPM)
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]


# Run the migrations 
RUN php artisan migrate --force 

# seed the database
RUN php artisan db:seed --force

