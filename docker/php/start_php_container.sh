#!/bin/bash

# Verifica se a pasta vendor existe. Se não, roda o composer install
if [ ! -d "/usr/share/nginx/html/vendor" ]; then
    cd /usr/share/nginx/html && composer install --no-dev
fi

if [ ! -f "/usr/share/nginx/html/storage/oauth-private.key" ]; then
    cd /usr/share/nginx/html && php artisan passport:key
fi

# Agora que a pasta vendor está garantida, inicia o supervisord
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

# Inicia o PHP-FPM (caso não seja iniciado pelo supervisor)
php-fpm