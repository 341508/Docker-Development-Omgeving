# Gebruik het officiële PHP image met Apache voorgeïnstalleerd
FROM php:8.2-apache

# Installeer de benodigde PHP-extensies voor MySQL (PDO)
RUN docker-php-ext-install pdo pdo_mysql

# Kopieer de lokale broncode naar de webmap van de Apache server
COPY src/ /var/www/html/

# Documenteer dat de container intern op poort 80 luistert
EXPOSE 80
