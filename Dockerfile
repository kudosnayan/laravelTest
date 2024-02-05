FROM php:8.1-fpm-alpine

# Copy composer.lock and composer.json
COPY composer.lock composer.json startup.sh /var/www/

# Set working directory
WORKDIR /var/www

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN composer install --ignore-platform-reqs

# Change current user to www
USER www

# Expose ports (adjust as needed)
EXPOSE 6000

RUN chmod u+x,g+x startup.sh

ENTRYPOINT ["/bin/sh","./startup.sh"]
