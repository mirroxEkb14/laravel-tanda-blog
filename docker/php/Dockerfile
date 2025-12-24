FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    libicu-dev \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && docker-php-ext-install pdo_mysql mbstring zip exif pcntl intl opcache \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY docker/php/entrypoint-scheduler.sh /usr/local/bin/entrypoint-scheduler.sh
RUN chmod +x /usr/local/bin/entrypoint-scheduler.sh

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
