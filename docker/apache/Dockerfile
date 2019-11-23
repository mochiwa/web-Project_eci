FROM php:7.1-apache

#################################
#		Base components
#################################
RUN apt-get update -y \
&& apt-get install -y \
	wget \
	unzip \
	nano \
	libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql


#################################
#		Config Apache
#################################
#Active rewrite
RUN a2enmod rewrite

#Copy config file
COPY ./000-default.conf /etc/apache2/sites-enabled/
COPY ./phpunit.xml /var/www/html
COPY ./.htaccess /var/www/html


