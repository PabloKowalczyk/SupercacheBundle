#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive;

PHP_VERSION=$1;

## Add Onrej PPA for php 5.6 - 7.2
add-apt-repository -y -u ppa:ondrej/php > /dev/null 2>&1;

apt-get update

apt-get install git \
    make \
    htop \
    "php$PHP_VERSION" \
    "php$PHP_VERSION-common" \
    "php$PHP_VERSION-cli" \
    "php$PHP_VERSION-zip" \
    "php$PHP_VERSION-mbstring" \
    "php$PHP_VERSION-opcache" \
    "php$PHP_VERSION-xml" \
    "php-xdebug" \
    "php$PHP_VERSION-intl" -y > /dev/null

phpdismod xdebug;
