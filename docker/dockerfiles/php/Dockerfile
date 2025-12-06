FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    linux-headers \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libpq-dev \
    curl-dev \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    mysql-client \
    postgresql-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        mbstring \
        mysqli \
        pdo_mysql \
        pdo_pgsql \
        curl \
        xml \
        zip \
        bcmath \
        intl \
        opcache

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]