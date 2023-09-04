#!/bin/sh

echo "install composer"
composer install && composer dump-autoload
echo "starting php-fpm"
exec $@
