FROM dwchiang/nginx-php-fpm:8.1.9-fpm-bullseye-nginx-1.21.6

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libpq-dev \
    libssl-dev

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql
RUN apt-get install -y \
        libonig-dev \
    && docker-php-ext-install iconv mbstring
RUN apt-get install -y \
        libzip-dev \
        zlib1g-dev \
    && docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update \
    && apt-get install -y --no-install-recommends openssl libssl-dev libcurl4-openssl-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Project
COPY . /var/www/html/
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY dockerbuild/docker-php-entrypoint /usr/bin/
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html/

# Permission
RUN chown root /var/www/html/storage/
RUN chmod -R 777 /var/www/html/storage/
RUN chmod -R 777 /var/www/html/storage/

USER root

EXPOSE 80
