#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive;

PHP_VERSION=$1;

mkdir -p /tmp/php/opcache &&
chown ubuntu:ubuntu /tmp/php/opcache &&

cp -f /vagrant/vagrant/php/cli-php.ini "/etc/php/$PHP_VERSION/cli/php.ini"
