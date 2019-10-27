FROM php:7.3.11-fpm

# update bash profile
COPY .profile /root/.profile
COPY .profile /root/.bashrc

RUN docker-php-ext-install pdo_mysql
