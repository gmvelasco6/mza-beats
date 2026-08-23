Dockerfile
FROM php:8.2-apache

# Instalar extensión mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Forzar a Apache y PHP a leer las variables de entorno
RUN echo "variables_order = \"EGPCS\"" > /usr/local/etc/php/conf.d/custom-env.ini
RUN echo "PassEnv MYSQLHOST MYSQLUSER MYSQLPASSWORD MYSQLDATABASE MYSQLPORT" >> /etc/apache2/apache2.conf

# Copiar todo el código
COPY . /var/www/html/

RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80