#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive;

CS_FIXER_VERSION=$1;
BIN_DIR=$2;
CS_FIXER_FILE=$3;

mkdir -p "$BIN_DIR" &&

wget -q -O "$CS_FIXER_FILE" "https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v$CS_FIXER_VERSION/php-cs-fixer.phar" &&

chmod +x "$CS_FIXER_FILE";
