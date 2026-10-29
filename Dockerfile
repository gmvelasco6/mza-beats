FROM php:8.2-apache

# Instalar extensión mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Forzar a Apache y PHP a leer las variables de entorno
RUN echo "variables_order = \"EGPCS\"" > /usr/local/etc/php/conf.d/custom-env.ini
RUN echo "PassEnv MYSQLHOST MYSQLUSER MYSQLPASSWORD MYSQLDATABASE MYSQLPORT" >> /etc/apache2/apache2.conf

# Seguridad: no mostrar versiones ni errores internos, y no listar carpetas
RUN { \
        echo 'expose_php = Off'; \
        echo 'display_errors = Off'; \
        echo 'log_errors = On'; \
        echo 'session.use_strict_mode = 1'; \
    } > /usr/local/etc/php/conf.d/seguridad.ini \
    && { \
        echo 'ServerTokens Prod'; \
        echo 'ServerSignature Off'; \
    } >> /etc/apache2/conf-available/security.conf \
    && sed -ri 's/Options Indexes FollowSymLinks/Options FollowSymLinks/' /etc/apache2/apache2.conf

# Copiar el código (ver .dockerignore para lo que queda afuera)
COPY . /var/www/html/

# Configurar la carpeta frontend como la raíz web pública.
# backend/ queda fuera y no se puede abrir desde el navegador.
ENV APACHE_DOCUMENT_ROOT /var/www/html/frontend
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
