FROM php:8.3-fpm
ENV DEBIAN_FRONTEND=noninteractive

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            tzdata \
            curl \
            cron \
            nano \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev \
            libmcrypt-dev \
            libonig-dev; \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    docker-php-ext-install pdo_mysql; \
    docker-php-ext-install pdo_pgsql; \
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd; \
    php -r 'var_dump(gd_info());'

RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    echo "America/Sao_Paulo" > /etc/timezone && \
    dpkg-reconfigure --frontend noninteractive tzdata

RUN apt-get update && apt-get install -y libxml2-dev \
    && docker-php-ext-install dom

# Install Composer
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

#ALTERAR USER PARA ROOT
USER root

#COPIAR OS ARQUIVOS DO REPOSITORY
COPY ../www /usr/share/nginx/html

#RUN MIGRATIONS 
RUN php /usr/share/nginx/html/artisan migrate

# ADD REPSOITORY AND PERMISSION
RUN chown -R www-data:www-data /usr/share/nginx/html && \
    chmod -R 750 /usr/share/nginx/html && \
    chmod -R 775 /usr/share/nginx/html/storage && \
    chmod -R 775 /usr/share/nginx/html/bootstrap/cache && \
    chmod -R 775 /usr/share/nginx/html/vendor && \
    chmod -R 640 /usr/share/nginx/html/.env && \
    chmod -R 755 /usr/share/nginx/html/public

# Executar o Composer como www-data antes de mudar o usuário
RUN composer install --no-dev --working-dir=/usr/share/nginx/html && \
    chown -R www-data:www-data /usr/share/nginx/html

CMD ["php-fpm"]