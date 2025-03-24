FROM php:8.2-apache
LABEL authors="jose"

# Set the working directory in the container
WORKDIR /var/www/html

# Install necessary system dependencies and PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev git unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Install Composer (PHP package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Expose port 80 to the host
EXPOSE 80

# Copy the project files into the container
COPY . /var/www/html

# Start Apache in the foreground
CMD ["apache2-foreground"]