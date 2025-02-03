FROM php:8.3-fpm
ENV DEBIAN_FRONTEND=noninteractive

# Instalar pacotes necessários e o Supervisor
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
            libonig-dev \
            libzip-dev \
            python3-pip \
            supervisor; \
    rm -rf /var/lib/apt/lists/*

# Instalar as extensões do PHP
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

# Configurar parâmetros do PHP
RUN echo "upload_max_filesize=100M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size=100M" >> /usr/local/etc/php/conf.d/uploads.ini && \   
    echo "memory_limit=2G" > /usr/local/etc/php/conf.d/memory-limit.ini && \
    echo "max_execution_time=600" > /usr/local/etc/php/conf.d/max-execution-time.ini

# Instalar a extensão zip
RUN apt-get update && apt-get install -y libzip-dev && \
    docker-php-ext-install zip

# Configurar o fuso horário
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    echo "America/Sao_Paulo" > /etc/timezone && \
    dpkg-reconfigure --frontend noninteractive tzdata

# Instalar Composer
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

#rodar o supervisor 
RUN supervisord -c /etc/supervisor/supervisord.conf

# Trabalhar no diretório do Nginx
WORKDIR /usr/share/nginx/html

# Alterar usuário para root
USER root

# Copiar os arquivos de configuração do Supervisor
COPY ../docker/supervisor/conf.d /etc/supervisor/conf.d

# Copiar os arquivos do repositório
COPY ../www /usr/share/nginx/html

# Executar o Composer como www-data antes de mudar o usuário
RUN composer install --no-dev --working-dir=/usr/share/nginx/html && \
    chown -R www-data:www-data /usr/share/nginx/html

# Instalar as permissões
RUN chown -R www-data:www-data /usr/share/nginx/html && \
    chmod -R 750 /usr/share/nginx/html && \
    chmod -R 775 /usr/share/nginx/html/storage && \
    chmod -R 775 /usr/share/nginx/html/bootstrap/cache && \
    chmod -R 775 /usr/share/nginx/html/vendor && \
    chmod -R 755 /usr/share/nginx/html/public

# Adicione o script de inicialização
COPY ../docker/php/start_php_container.sh /start_php_container.sh
RUN chmod +x /start_php_container.sh

# Defina o script como comando padrão
CMD ["php-fpm"]