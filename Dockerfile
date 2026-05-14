FROM php:8.3-fpm
# =========================================
# SYSTEM DEPENDENCIES
# =========================================
RUN apt-get update && apt-get install -y \
git curl unzip zip \
gnupg2 ca-certificates \
libpng-dev libonig-dev libxml2-dev libzip-dev \
unixodbc-dev gcc g++ make autoconf pkg-config \
libssl-dev \
&& docker-php-ext-install \
pdo_mysql mbstring exif pcntl bcmath gd zip
# =========================================
# MICROSOFT ODBC DRIVER (REQUIRED FOR MSSQL)
# =========================================
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /usr/share/keyrings/microsoft.gpg \
&& echo "deb [signed-by=/usr/share/keyrings/microsoft.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" \
> /etc/apt/sources.list.d/mssql-release.list \
&& apt-get update \
&& ACCEPT_EULA=Y apt-get install -y msodbcsql18 unixodbc-dev
# =========================================
# PHP SQLSRV EXTENSIONS
# =========================================
RUN pecl channel-update pecl.php.net \
&& pecl install sqlsrv pdo_sqlsrv \
&& docker-php-ext-enable sqlsrv pdo_sqlsrv
# =========================================
# FIX TLS FOR LEGACY SQL SERVER 2008 R2
# =========================================
RUN cat > /etc/ssl/openssl_legacy.cnf << 'EOF'
openssl_conf = openssl_init

[openssl_init]
ssl_conf = ssl_sect

[ssl_sect]
system_default = system_default_sect

[system_default_sect]
MinProtocol = TLSv1
CipherString = DEFAULT@SECLEVEL=0
EOF

ENV OPENSSL_CONF=/etc/ssl/openssl_legacy.cnf
# =========================================
# COMPOSER
# =========================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# =========================================
# WORKDIR
# =========================================
WORKDIR /var/www
# =========================================
# COPY APP
# =========================================
COPY . .
# =========================================
# INSTALL LARAVEL DEPENDENCIES
# =========================================
RUN composer install --no-dev --optimize-autoloader
# =========================================
# PERMISSIONS
# =========================================
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www \
    && chmod -R 777 /var/www/storage /var/www/bootstrap/cache
EXPOSE 9000
CMD ["php-fpm"]