# -*- mode: ruby -*-
# vi: set ft=ruby :

$apt = <<SCRIPT

export DEBIAN_FRONTEND=noninteractive;

PHP_VERSION="7.1";

## Add Onrej PPA for php 5.6 - 7.2
add-apt-repository -y -u ppa:ondrej/php > /dev/null 2>&1;

apt-get update

apt-get install git \
    make \
    "php$PHP_VERSION" \
    "php$PHP_VERSION-zip" \
    "php$PHP_VERSION-mbstring" \
    "php$PHP_VERSION-opcache" \
    "php$PHP_VERSION-xml" \
    "php-xdebug" \
    "php$PHP_VERSION-intl" -y > /dev/null

phpdismod xdebug;

SCRIPT

$composer = <<SCRIPT

COMPOSER_VERSION="1.5.2";
BIN_DIR="/home/ubuntu/bin";
COMPOSER_FILE="$BIN_DIR/composer";

mkdir -p "$BIN_DIR" &&

wget -q -O "$COMPOSER_FILE" "https://getcomposer.org/download/$COMPOSER_VERSION/composer.phar" &&

chmod +x "$COMPOSER_FILE";

SCRIPT

Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/xenial64"

    config.vm.network "forwarded_port", guest: 8000, host: 8001
    config.vm.network "private_network", ip: "192.168.56.26"

    config.vm.provider "virtualbox" do |vb|
        vb.memory = "768"
        vb.cpus = 4
        vb.name = "supercache-bundle"
    end

    config.vm.provision "shell", inline: $apt
    config.vm.provision "shell", inline: $composer
end
