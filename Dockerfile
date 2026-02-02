FROM php:8.2-apache

# PHP extensions 
RUN docker-php-ext-install mysqli

# useful for clean URLs if add rewrite rules
RUN a2enmod rewrite

# Copy app into the container for cloud deployments
WORKDIR /var/www/html
COPY . /var/www/html

# file permissions
RUN chown -R www-data:www-data /var/www/html
