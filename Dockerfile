FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite env

# Evita el aviso AH00558 en logs ("Could not reliably determine FQDN")
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY docker/entrypoint-apache.sh /usr/local/bin/entrypoint-apache.sh
RUN chmod +x /usr/local/bin/entrypoint-apache.sh

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && test -f vendor/autoload.php

RUN chown -R www-data:www-data /var/www/html

# Raíz web = carpeta public/ de Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Laravel usa .htaccess en public/ — hace falta AllowOverride All
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Que PHP (mod_php) vea las variables que inyecta Render / el entrypoint
RUN printf '%s\n' \
  '<IfModule mod_env.c>' \
  '  PassEnv APP_KEY APP_ENV APP_DEBUG APP_URL APP_TIMEZONE APP_LOCALE' \
  '  PassEnv LOG_CHANNEL LOG_LEVEL LOG_STACK' \
  '  PassEnv DATABASE_URL DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD DB_SSLMODE' \
  '  PassEnv SESSION_DRIVER SESSION_SECURE_COOKIE SESSION_SAME_SITE SESSION_LIFETIME' \
  '  PassEnv CACHE_STORE QUEUE_CONNECTION FILESYSTEM_DISK' \
  '  PassEnv GANATELO_DEMO_SIN_BILLETERA' \
  '</IfModule>' \
  > /etc/apache2/conf-available/laravel-passenv.conf \
  && a2enconf laravel-passenv

ENV LOG_CHANNEL=stderr

CMD ["/usr/local/bin/entrypoint-apache.sh"]
