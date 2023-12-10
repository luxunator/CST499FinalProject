FROM php:5.4.45-apache

RUN docker-php-ext-install mysqli

COPY ./htdocs /var/www/html
