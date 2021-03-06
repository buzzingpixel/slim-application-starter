FROM php:7.3.11-fpm

# update bash profile
COPY .profile /root/.profile
COPY .profile /root/.bashrc

RUN set -ex; \
    \
    apt-get update; \
    apt-get install -my --no-install-recommends \
        libmagick++-dev \
        libmagickwand-dev \
        libssl-dev \
        libwebp-dev \
        libxml2 \
        libxml2-dev \
        libxpm-dev \
        libzip-dev \
        openssl \
        unzip \
    ; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*; \
    \
    docker-php-ext-configure gd \
        --with-png-dir=/usr \
        --with-jpeg-dir=/usr \
        --with-freetype-dir=/usr \
        --with-xpm-dir=/usr \
        --with-webp-dir=/usr \
    ; \
    docker-php-ext-install \
        bcmath \
        bz2 \
        exif \
        gd \
        iconv \
        mbstring \
        opcache \
        pdo_mysql \
        xmlrpc \
        zip \
    ; \
    pecl install \
        imagick \
    ; \
    docker-php-ext-enable \
        imagick \
    ;
