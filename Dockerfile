FROM php:8.2-cli

# Instala dependências
RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria diretório da aplicação
WORKDIR /var/www

# Copia os arquivos do Laravel
COPY . .

# Instala dependências do Laravel
RUN composer install

# Gera a key (evita erro 500)
RUN php artisan key:generate || true

RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache


# Expõe a porta do servidor Laravel
EXPOSE 8000

# Comando para rodar o servidor Laravel ao iniciar o container
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]