FROM php:8.2-apache

RUN apt update && apt install -y git zip unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
USER www-data
COPY ./src /var/www/html

USER root
RUN chown -R www-data:www-data .

USER www-data

# Iniciar server de desarrollo Laravel en primer plano
EXPOSE 8000
CMD "php artisan serve --host 0.0.0.0"
