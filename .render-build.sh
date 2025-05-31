#!/usr/bin/env bash
set -o errexit

# Install Composer if not present
if ! command -v composer &> /dev/null
then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

composer install --no-dev --optimize-autoloader