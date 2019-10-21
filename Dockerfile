# Set the base image for subsequent instructions
FROM php:7.2.13-fpm-alpine3.8

# Create app directory
WORKDIR /var/www

# Update packages
RUN apk --update add curl \
        g++ \
        gcc \
        gnupg \
        libgcc \
        make \
        libmcrypt-dev \
        zlib-dev \
        autoconf

# Install PHP required extension
RUN pecl install mcrypt-1.0.1
RUN docker-php-ext-install zip

COPY . .