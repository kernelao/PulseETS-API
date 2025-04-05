FROM php:8.2-cli

# Installe les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    gnupg2 \
    unixodbc-dev \
    libgssapi-krb5-2 \
    curl \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && pecl install pdo_sqlsrv sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv sqlsrv

# Installe les extensions PHP Symfony classiques
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app
COPY . /app

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
