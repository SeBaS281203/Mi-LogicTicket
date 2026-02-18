# LogicTicket

Plataforma de venta de entradas para eventos (estilo Joinnus).

## Requisitos

- PHP 8.2+
- Composer
- Base de datos (MySQL/PostgreSQL/SQLite)

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Usuarios de prueba (tras `db:seed`)

| Rol         | Email                  | Contraseña |
|------------|------------------------|------------|
| Admin      | admin@logicticket.com  | password   |
| Organizador| organizer@logicticket.com | password |

## Funcionalidades

- **Autenticación**: registro, login, recuperación de contraseña
- **Roles**: Admin, Organizador, Cliente
- **Panel Admin** (`/admin`): dashboard, categorías, usuarios
- **Panel Organizador** (`/organizer`): crear/editar eventos, tipos de entrada, dashboard de ventas
- **Página pública**: listado de eventos con filtros por categoría, ciudad y fecha
- **Carrito y checkout**: sesión, confirmación de compra
- **Pagos**: integración con Stripe (opcional). Si no configuras Stripe, el checkout marca la orden como pagada directamente (modo demo).
- **Email**: confirmación de compra enviada al completar la orden

## Configuración Stripe (opcional)

En `.env`:

```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_CURRENCY=pen
```

Si no se configuran, el checkout confirma la compra sin redirigir a Stripe.

## Rutas principales

- `/` — Eventos (público)
- `/login`, `/register` — Auth
- `/cart` — Carrito
- `/checkout` — Finalizar compra
- `/admin` — Panel administración (rol admin)
- `/organizer` — Panel organizador (rol organizer u admin)

## Desarrollo

```bash
npm install && npm run dev
php artisan serve
```
