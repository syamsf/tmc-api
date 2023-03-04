FROM composer:2.4.1 AS composer

FROM php:8.1.9-cli AS build

WORKDIR /app

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get -y install  \
    git\
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install sockets

RUN composer create-project laravel/laravel /app

COPY composer.json ./

RUN composer update

RUN composer install

COPY app ./app
COPY config ./config
COPY resources ./resources
COPY database ./database
COPY routes ./routes
COPY .env.example ./.env

RUN mkdir -p storage/framework/cache
RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/testing
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/logs
RUN mkdir -p storage/app

FROM php:8.1.9-apache

WORKDIR /var/www/html/

COPY --from=build --chown=www-data:www-data /app /var/www/html/

COPY provision/webserver/vhosts.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite \
    && a2enmod ssl \
    && apt-get update \
    && apt-get install -y \
    && docker-php-ext-install pdo_mysql \
    && apt-get install supervisor net-tools iputils-ping -y \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/*

COPY provision/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY provision/supervisor/config /etc/supervisor/conf.d

RUN php artisan cache:clear

RUN php artisan config:clear
RUN php artisan config:cache

RUN php artisan route:clear

RUN php artisan event:clear
RUN php artisan event:cache

CMD ["/bin/sh", "-c", "/etc/init.d/supervisor start ; apache2-foreground"]
