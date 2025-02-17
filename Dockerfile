# Use the official PHP 8.1+ Apache image
FROM php:8.1-apache

# Set the working directory
WORKDIR /var/www/html

# Install system packages & PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mysqli zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy your Laravel project into the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# (Optional) Set correct permissions for storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
