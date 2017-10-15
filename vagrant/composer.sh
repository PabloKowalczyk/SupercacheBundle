#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive;

COMPOSER_VERSION=$1;
BIN_DIR=$2;
COMPOSER_FILE=$3;

mkdir -p "$BIN_DIR" &&

wget -q -O "$COMPOSER_FILE" "https://getcomposer.org/download/$COMPOSER_VERSION/composer.phar" &&

chmod +x "$COMPOSER_FILE";
