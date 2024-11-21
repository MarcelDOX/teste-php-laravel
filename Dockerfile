# Use a imagem oficial do PHP 8.1 com Apache
FROM php:8.1-apache

# Atualiza pacotes e instala extensões necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Ativa o mod_rewrite para o Laravel
RUN a2enmod rewrite

# Copia o arquivo de configuração personalizado para o Apache
COPY ./apache-test.conf /etc/apache2/sites-available/000-default.conf

# Reinicia o Apache para aplicar as configurações
RUN service apache2 restart

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho como /var/www/html
WORKDIR /var/www/html

# Copia os arquivos do projeto
COPY . .

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expor a porta 80
EXPOSE 80
