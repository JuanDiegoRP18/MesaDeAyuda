FROM php:8.1-apache

ENV DEBIAN_FRONTEND=noninteractive

# Install required packages: mariadb-server and supervisor
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    mariadb-server \
    supervisor \
    procps \
  && docker-php-ext-install mysqli pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Copy application into the image
COPY . /var/www/html/

# Supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entrypoint to initialize the database on first run
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
