FROM php:8.2-apache-bullseye

WORKDIR /var/www/voice-recorder-web/

RUN apt-get update && apt-get install -y \
    build-essential \
    software-properties-common \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    libonig-dev \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    ca-certificates \
    vim \
    tmux \
    unzip \
    git \
    cron \
    supervisor \
    curl \
    libpq-dev \
    net-tools \
    acl \
    redis-server \
    # nodejs \
    # npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mbstring zip exif pcntl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install gd

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@10

COPY jdk1.8.0_202 /opt/jdk1.8.0_202

ENV JAVA_HOME /opt/jdk1.8.0_202
ENV PATH "$PATH:$JAVA_HOME/bin"

RUN update-alternatives --install /usr/bin/java java /opt/jdk1.8.0_202/bin/java 100 \
    && chmod 755 /opt/jdk1.8.0_202/bin/java \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/voice-recorder-web

COPY php.ini-development /usr/local/etc/php/
COPY php.ini-production /usr/local/etc/php/

RUN cp .env.example .env

RUN composer update \
    && composer require pusher/pusher-php-server "^7.2" \
	&& php artisan l5-swagger:generate \
    && npm install \
    && npm run build

RUN touch /var/log/cron.log \
    && crontab -l | { cat; echo "* * * * * cd /var/www/voice-recorder-web && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1"; } | crontab - \
    # && crontab -l | { cat; echo "* * * * * cd /var/www/voice-recorder-web && /usr/local/bin/php artisan reverb:start >> /var/log/cron.log 2>&1"; } | crontab - \
    && mkdir -p /var/log/supervisor \
    && chmod -R 777 /var/www/voice-recorder-web/storage/ \
    && chmod -R 755 /var/www/voice-recorder-web/public/ \
    && chmod -R 755 /var/www/voice-recorder-web/bootstrap/ \
    && chmod -R 755 /var/www/voice-recorder-web/app/ \
    && sed -i 's/^exec /service cron start\n\nexec /' /usr/local/bin/apache2-foreground \
    && echo setfacl -d -m group:root:rwx /var/www/voice-recorder-web/app/ > /var/www/voice-recorder-web/setfacl.sh \
    && echo setfacl -d -m group:root:rwx /var/www/voice-recorder-web/storage/ > /var/www/voice-recorder-web/setfacl.sh \
    && chmod 755 /var/www/voice-recorder-web/setfacl.sh \
    && chmod 755 /var/www/voice-recorder-web/start.sh \
    && a2enmod rewrite \
    && composer update \
    && chown www-data:www-data -R ./storage

COPY --chown=root:root supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker-php.conf /etc/apache2/conf-available/docker-php.conf
COPY apache2.conf /etc/apache2/apache2.conf

# CMD /var/www/voice-recorder-web/setfacl.sh && service cron start && /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
# CMD ["sh", "-c", "/var/www/voice-recorder-web/setfacl.sh && service cron start && /usr/bin/supervisord -c /etc/supervisor/supervisord.conf"]
# CMD /var/www/voice-recorder-web/setfacl.sh && /usr/bin/supervisord -c /etc/supervisor/supervisord.conf

CMD ["/var/www/voice-recorder-web/start.sh"]
