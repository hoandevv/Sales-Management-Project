FROM php:8.2-apache

# Cài extension PHP cần cho MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy file cấu hình Apache (nếu bạn cần rewrite URL)
COPY ./docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Bật mod_rewrite
RUN a2enmod rewrite

EXPOSE 80
