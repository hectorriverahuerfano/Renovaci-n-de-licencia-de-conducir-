FROM php:8.2-apache

# Metadatos del contenedor institucional
LABEL maintainer="Gobierno de El Salvador <innovacion@presidencia.gob.sv>"
LABEL description="MVP Renovación de Licencia de Conducir (VMT / SERTRACEN / GOES)"

# Instalar dependencias del sistema y extensiones SQLite
RUN apt-get update && apt-get install -y --no-install-recommends \
    sqlite3 \
    libsqlite3-dev \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_sqlite bcmath opcache zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar Apache DocumentRoot a /var/www/html/public y habilitar mod_rewrite y headers
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite headers

# Configuración de producción para PHP y Opcache
RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "opcache.enable=1\nopcache.memory_consumption=128\nopcache.max_accelerated_files=10000\nopcache.validate_timestamps=0" > /usr/local/etc/php/conf.d/opcache.ini

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar dependencias de Composer primero para aprovechar la caché de capas Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar el resto del código del MVP
COPY . .

# Generar autoloader optimizado
RUN composer dump-autoload --optimize --no-dev

# Crear carpetas de almacenamiento y caché con permisos correctos
RUN mkdir -p /var/data storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html /var/data \
    && chmod -R 775 storage bootstrap/cache

# Configurar script de inicio
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exponer el puerto estándar HTTP
EXPOSE 80

# Variables de entorno por defecto en el contenedor
ENV PORT=80 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/data/database.sqlite

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
