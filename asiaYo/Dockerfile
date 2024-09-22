# Use the official PHP image with Apache
FROM php:8.2-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Install PostgreSQL PDO
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Copy the application code to the container
COPY . /var/www/html

# Copy the Apache configuration file to the container
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Update the ownership and permissions for Laravel folders
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html/storage -type f -exec chmod 644 {} \; \
    && find /var/www/html/storage -type d -exec chmod 755 {} \; \
    && find /var/www/html/bootstrap/cache -type f -exec chmod 644 {} \; \
    && find /var/www/html/bootstrap/cache -type d -exec chmod 755 {} \;

# Expose port 80 to access Apache
EXPOSE 80