FROM php:7.1-cli-alpine

RUN apk add --no-cache \
        shadow \
        su-exec && \
    usermod --non-unique --uid 1000 www-data && \
    apk del \
        shadow

RUN mkdir -p \
        /var/log/php \
    && touch /var/log/php/error.log \
    && chown www-data:www-data \
        /var/log/php/error.log

COPY --from=composer:1.8 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_HOME /var/www/.composer
