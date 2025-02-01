#!/bin/bash
# Inicia o supervisord
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

# Inicia o PHP-FPM
php-fpm