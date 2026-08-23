FROM php:8.2-apache

# Instalar extensión mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Forzar a Apache y PHP a leer las variables de entorno
RUN echo "variables_order = \"EGPCS\"" > /usr/local/etc/php/conf.d/custom-env.ini
RUN echo "PassEnv MYSQLHOST MYSQLUSER MYSQLPASSWORD MYSQLDATABASE MYSQLPORT" >> /etc/apache2/apache2.conf

# Copiar todo el código del repositorio
COPY . /var/www/html/

# Configurar la carpeta frontend como la raíz web pública
ENV APACHE_DOCUMENT_ROOT /var/www/html/frontend
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80