# Probar Stripe en 3 pasos

Tu `.env` ya tiene las claves de prueba. Solo haz esto:

---

## 1. Arrancar la app

```bash
php artisan serve
```

(Si usas otra URL/puerto, asegúrate de que `APP_URL` en `.env` coincida, ej. `http://127.0.0.1:8000`.)

---

## 2. Hacer una compra de prueba

1. Abre en el navegador la URL que te indique `php artisan serve` (ej. http://127.0.0.1:8000).
2. Entra a un evento y **añade entradas al carrito**.
3. Ve a **Carrito** → **Finalizar compra**.
4. Rellena nombre, email, acepta términos y avanza hasta el paso de **pago**.
5. Pulsa el botón para pagar: te redirigirá a **Stripe Checkout**.

---

## 3. Pagar en Stripe con tarjeta de prueba

En la página de Stripe usa estos datos (no se cobra nada):

| Campo    | Valor              |
|----------|--------------------|
| Número   | `4242 4242 4242 4242` |
| Fecha    | Cualquier futura (ej. `12/30`) |
| CVC      | Cualquier 3 dígitos (ej. `123`) |
| Nombre   | El que quieras     |
| Email    | El que quieras     |

Completa el pago. Stripe te devolverá a tu sitio y deberías ver la **confirmación de la orden** y, si el correo está configurado, el email con los tickets.

---

**Importante:** No subas el archivo `.env` a Git (debería estar en `.gitignore`). Para producción usarás claves Live y otro `.env` en el servidor.
