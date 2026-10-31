FROM php:8.2-apache

# Installer les dépendances système et utilitaires requis
RUN apt-get update && apt-get install -y --no-install-recommends \
    sqlite3 \
    libsqlite3-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP (pdo_sqlite, sqlite3, pdo_pgsql, pgsql, pdo_mysql si besoin)
RUN apt-get update && apt-get install -y --no-install-recommends libpq-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql pdo_mysql

# Activer le module Apache mod_rewrite
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier l'ensemble des fichiers du projet
COPY . /var/www/html/

# Installer les dépendances PHP (PHPMailer)
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader; fi

# Créer le dossier database s'il n'existe pas et configurer les permissions
# Apache (www-data) a besoin des droits d'écriture sur le dossier database et le fichier sqlite
RUN mkdir -p /var/www/html/database && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/database

EXPOSE 80

CMD ["apache2-foreground"]

