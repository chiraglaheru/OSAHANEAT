FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    zip unzip git curl nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Build frontend assets
RUN npm install && npm run build

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

RUN mkdir -p /var/www/html/public/upload/menu_images \
    /var/www/html/public/upload/category_images \
    /var/www/html/public/upload/admin_images \
    /var/www/html/public/upload/client_images \
    && chmod -R 775 /var/www/html/public/upload \
    && chown -R www-data:www-data /var/www/html/public/upload

CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan storage:link && \
    php artisan migrate --force && \
    apache2-foreground
