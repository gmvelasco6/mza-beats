FROM php:8.2-apache

# Instalar extension mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Configurar Apache para que no borre las variables de entorno de Render
RUN echo "PassEnv MYSQLHOST MYSQLUSER MYSQLPASSWORD MYSQLDATABASE MYSQLPORT" >> /etc/apache2/apache2.conf

# Copiar todo el código
COPY . /var/www/html/

# Configurar la carpeta frontend como raiz web
ENV APACHE_DOCUMENT_ROOT /var/www/html/frontend
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80