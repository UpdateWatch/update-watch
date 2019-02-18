FROM php:7-fpm

# PHP extensions

RUN docker-php-ext-install pdo pdo_mysql