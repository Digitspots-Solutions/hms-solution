FROM php:7.4-apache

# Install mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable apache mod_rewrite if needed
RUN a2enmod rewrite

# Change ownership of the document root
RUN chown -R www-data:www-data /var/www/html
