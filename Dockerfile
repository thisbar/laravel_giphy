FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    curl \
    zip \
    unzip \
    git \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    libzip-dev \
    libxml2-dev \
    gmp-dev \
    libsodium-dev \
    oniguruma-dev \
    mariadb-dev

RUN docker-php-ext-configure gd --with-freetype=/usr/include --with-jpeg=/usr/include \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    intl \
    zip \
    gmp \
    sodium \
    xml

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www/storage \
    chmod -R 775 /var/www/storage

EXPOSE 9000

CMD ["php-fpm"]
