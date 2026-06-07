FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 1. Copia só o necessário para instalar dependências
COPY composer.json composer.lock ./

# 2. .env mínimo para o package:discover não quebrar
RUN cp composer.json /tmp/composer.json && \
    echo 'APP_NAME=Laravel\nAPP_ENV=production\nAPP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=\nAPP_DEBUG=false\nDB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=laravel\nDB_USERNAME=root\nDB_PASSWORD=root\nCACHE_DRIVER=array\nSESSION_DRIVER=array\nQUEUE_CONNECTION=sync\nREDIS_HOST=127.0.0.1\nREDIS_PORT=6379' > .env

# 3. Instala dependências
RUN composer install --force

# 4. Copia o restante da aplicação
COPY --chown=www-data:www-data . /var/www

# 5. Gera key real e limpa cache
RUN php artisan key:generate --force \
    && php artisan config:clear \
    && php artisan view:clear

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ARG UID=1000
ARG GID=1000

RUN groupadd -g ${GID} appgroup 2>/dev/null || true && \
    useradd -u ${UID} -g appgroup -m appuser 2>/dev/null || true && \
    chown -R appuser:appgroup /var/www

USER appuser

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]