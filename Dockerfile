FROM php:8.1-apache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar la configuración de Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar el código fuente
COPY . /var/www/html/

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html 