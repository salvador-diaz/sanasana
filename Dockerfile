FROM php:8.2-apache

RUN apt update && apt install -y \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
USER www-data
COPY ./src /var/www/html

EXPOSE 80

# Ejecutar proceso Apache en primer plano
CMD "apache2-foreground"
