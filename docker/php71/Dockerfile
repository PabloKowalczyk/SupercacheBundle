FROM php:7.1-cli-alpine

RUN apk add --no-cache \
        su-exec && \
    docker-php-ext-install -j$(nproc) \
        opcache

RUN mkdir -p \
        /var/log/php \
    && touch /var/log/php/error.log \
    && chown www-data:www-data \
        /var/log/php/error.log

COPY --from=composer:1.8 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_HOME /var/www/.composer
