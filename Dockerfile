# FROM nginx
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/laramentor

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libpng-dev \
    libreadline-dev \
    libonig-dev \
    sudo \
    unzip \
    zip \
    nano \
    cron

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql exif pcntl bcmath gd pdo pdo_mysql sockets

#cron
# COPY /docker-files/cron/laramentor-cron /etc/cron.d/root
# RUN chmod -R 0644 /etc/cron.d/root && crontab /etc/cron.d/root


# add user ubuntu, add group ubuntu
RUN useradd -rm -d /home/ubuntu -s /bin/bash -g root -G sudo,www-data -u 1000 ubuntu
RUN groupadd ubuntu
# RUN usermod -a -G ubuntu

# RUN chmod -R 775 /var/www/laramentor
# RUN chown -R ubuntu:www-data /var/www/laramentor


# Get latest Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer



# install nodejs
RUN curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh
RUN apt-get update && apt-get install -y \
    nodejs \
    yarn \
    && rm -rf /var/lib/apt/lists/*

RUN rm nodesource_setup.sh

# CMD ["cron", "-f"]

