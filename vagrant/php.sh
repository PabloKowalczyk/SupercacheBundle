#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive;

PHP_VERSION=$1;

mkdir -p /home/vagrant/tmp/php/opcache &&
chown ubuntu:ubuntu /home/vagrant/tmp/php/opcache &&

cp -f /vagrant/vagrant/php/cli-php.ini "/etc/php/$PHP_VERSION/cli/php.ini"
