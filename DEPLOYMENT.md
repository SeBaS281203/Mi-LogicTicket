# Guía de despliegue en producción - LogicTicket

## Requisitos del servidor

- PHP 8.2+
- Extensión GD (recomendada para optimización de imágenes)
- Composer 2
- Node.js 18+ (para compilar assets)
- Base de datos MySQL/PostgreSQL
- Redis (recomendado para caché en producción)

## Configuración de entorno

1. Copiar `.env.example` a `.env`
2. Configurar variables para producción:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logicticket
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Caché (recomendado Redis en producción)
CACHE_STORE=redis
# O CACHE_STORE=database si no hay Redis

# Sesiones
SESSION_DRIVER=database

# Cola de trabajos
QUEUE_CONNECTION=database
# O QUEUE_CONNECTION=redis

# Logs
LOG_CHANNEL=production
LOG_LEVEL=error
LOG_DAILY_DAYS=30

# Stripe / Mercado Pago (producción)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
```

## Comandos de despliegue

```bash
# 1. Instalar dependencias
composer install --optimize-autoloader --no-dev

# 2. Generar clave de aplicación
php artisan key:generate

# 3. Ejecutar migraciones
php artisan migrate --force

# 4. Crear enlace de almacenamiento
php artisan storage:link

# 5. Compilar assets
npm ci && npm run build

# 6. Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Crear tabla de caché (si usa database)
php artisan cache:table
php artisan migrate --force
```

## Cola de trabajos

Si usas colas (emails, etc.), ejecuta un worker:

```bash
php artisan queue:work --tries=3 --timeout=90
```

En producción, usa Supervisor o systemd para mantener el worker activo.

## Programación (Cron)

En el crontab del servidor:

```bash
* * * * * cd /ruta/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

## Seguridad

- **APP_DEBUG=false** en producción
- **HTTPS** obligatorio (configurar certificado SSL)
- Las cabeceras de seguridad se aplican automáticamente vía `SecurityHeadersMiddleware`
- Rate limiting: login/registro (5/min), checkout (10/min), libro de reclamaciones (10/min)

## Después de actualizar código

```bash
git pull
composer install --optimize-autoloader --no-dev
php artisan migrate --force
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

## Índices de base de datos

La migración `add_production_indexes` añade índices para mejorar rendimiento. Ejecutar:

```bash
php artisan migrate --force
```
