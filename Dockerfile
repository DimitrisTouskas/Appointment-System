FROM php:8.3-apache
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www/html/appointment-system
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install
EXPOSE 80