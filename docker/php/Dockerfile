FROM php:7.4-fpm-alpine

RUN apk add --update \
    composer \
    icu-dev \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install intl \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql

WORKDIR /code/api

COPY ./code/api/composer.json ./code/api/composer.lock /code/api/
RUN composer install

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
