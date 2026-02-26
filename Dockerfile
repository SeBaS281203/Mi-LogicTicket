FROM php:8.2-cli

# 1. Instalar dependencias del sistema, PHP, Node.js y npm
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip

# 2. Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Directorio de trabajo
WORKDIR /app

# 4. COPIAR TODOS LOS ARCHIVOS DEL PROYECTO AL SERVIDOR (¡Esto era lo que faltaba!)
COPY . .

# 5. Instalar librerías de PHP (Laravel) y optimizarlas
RUN composer install --no-dev --optimize-autoloader

# 6. Instalar librerías de JavaScript/CSS y compilar los diseños (Vite)
RUN npm install
RUN npm run build

# 7. Iniciar el servidor
CMD php artisan serve --host=0.0.0.0 --port=8000