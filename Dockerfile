FROM php:7.3-apache
RUN docker-php-ext-install mbstring mysqli pdo_mysql
RUN a2enmod rewrite
