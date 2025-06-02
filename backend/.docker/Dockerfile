FROM php:8.3-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar arquivos do projeto
COPY . /var/www

# Ajustar permissões
RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]
