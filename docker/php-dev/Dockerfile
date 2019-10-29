FROM php:7.3.11-fpm

# update bash profile
COPY .profile /root/.profile
COPY .profile /root/.bashrc

RUN pecl install xdebug-2.7.2;
RUN docker-php-ext-enable xdebug;
