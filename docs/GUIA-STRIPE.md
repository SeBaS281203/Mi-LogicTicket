# Guía para conectar ChiclayoTicket con Stripe

Esta guía te lleva paso a paso desde crear una cuenta en Stripe hasta tener pagos funcionando en tu entorno (local y producción).

---

## 1. Crear cuenta en Stripe

1. Entra en **[https://dashboard.stripe.com/register](https://dashboard.stripe.com/register)**.
2. Regístrate con tu correo (o inicia sesión si ya tienes cuenta).
3. Completa el perfil de tu negocio cuando Stripe lo pida (puedes hacerlo más tarde).
4. Por defecto empiezas en **modo prueba (Test mode)**. Así puedes probar sin cobrar de verdad.

---

## 2. Obtener las claves API (modo prueba)

1. En el panel de Stripe, ve a **Developers** (desarrolladores) → **API keys**.
2. Comprueba que arriba diga **“Test mode”** (interruptor en ON).
3. Verás:
   - **Publishable key**: empieza por `pk_test_...` → esta va en tu frontend si algún día usas Stripe.js en la misma página.
   - **Secret key**: haz clic en “Reveal” y copia la que empieza por `sk_test_...` → **nunca** la subas a GitHub ni la muestres en el navegador.

En ChiclayoTicket usamos **Stripe Checkout** (el usuario paga en la página de Stripe), así que lo mínimo que necesitas ahora es la **Secret key** para el backend. La **Publishable key** puedes guardarla por si más adelante quieres mostrar el botón de Stripe en tu sitio.

---

## 3. Configurar tu proyecto (archivo `.env`)

En la raíz del proyecto abre (o crea) el archivo **`.env`** y añade o edita estas líneas:

```env
# Stripe - Modo prueba
STRIPE_KEY=TU_STRIPE_PUBLIC_KEY_DE_PRUEBA
STRIPE_SECRET=TU_STRIPE_SECRET_KEY_DE_PRUEBA
STRIPE_CURRENCY=pen
STRIPE_WEBHOOK_SECRET=

# Qué pasarela usar (stripe | mercadopago | none)
LOGIC_TICKET_PAYMENT_DRIVER=stripe
```

- **STRIPE_KEY**: la clave pública `pk_test_...`.
- **STRIPE_SECRET**: la clave secreta `sk_test_...`.
- **STRIPE_CURRENCY**: `pen` para soles peruanos (Stripe acepta PEN).
- **STRIPE_WEBHOOK_SECRET**: déjalo vacío de momento; lo rellenarás en el paso 5 (webhook).
- **LOGIC_TICKET_PAYMENT_DRIVER**: pon `stripe` para usar Stripe como método de pago.

Guarda el archivo y **no subas `.env` a Git** (ya debería estar en `.gitignore`).

---

## 4. Probar el flujo de pago (sin webhook)

1. Arranca tu app (por ejemplo `php artisan serve`).
2. Añade entradas al carrito y ve a **Finalizar compra**.
3. Completa datos y confirma: te redirigirá a la página de pago de **Stripe Checkout**.
4. Usa una tarjeta de prueba de Stripe, por ejemplo:
   - Número: `4242 4242 4242 4242`
   - Fecha: cualquier fecha futura (ej. 12/34)
   - CVC: cualquier 3 dígitos (ej. 123)
   - Correo: el que quieras
5. Después del pago te llevará a la página de confirmación de ChiclayoTicket y deberías recibir el correo con los tickets.

Si todo eso funciona, ya tienes “subido” el flujo a Stripe en modo prueba. El webhook es opcional pero recomendable para que, si el usuario cierra el navegador antes de volver a tu web, la orden se confirme igual.

---

## 5. Configurar el webhook de Stripe (recomendado)

El webhook permite a Stripe avisar a tu servidor cuando un pago se completa, aunque el usuario no llegue a la página de “éxito” de tu web.

### Opción A: Desarrollando en local (con Stripe CLI)

1. Instala **Stripe CLI**: [https://stripe.com/docs/stripe-cli](https://stripe.com/docs/stripe-cli) (Windows: descarga el .exe o usa Chocolatey).
2. En la terminal, inicia sesión:
   ```bash
   stripe login
   ```
3. Reenvía los eventos de Stripe a tu servidor local (puerto 80 o el que uses):
   ```bash
   stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhook
   ```
   Si tu proyecto corre en otro puerto (ej. 8080), cambia la URL:
   ```bash
   stripe listen --forward-to http://127.0.0.1:8080/api/stripe/webhook
   ```
4. La CLI te mostrará un **webhook signing secret** que empieza por `whsec_...`. Cópialo.
5. En tu `.env` pon:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxxxxx
   ```
6. Reinicia tu servidor Laravel y vuelve a hacer una compra de prueba. El webhook debería recibir el evento `checkout.session.completed`.

### Opción B: En producción (servidor con URL pública)

1. En Stripe: **Developers** → **Webhooks** → **Add endpoint**.
2. **Endpoint URL**: `https://tu-dominio.com/api/stripe/webhook`  
   (sustituye `tu-dominio.com` por tu dominio real).
3. En “Select events to listen to”, elige **checkout.session.completed** (o “Select events” y busca ese).
4. Clic en **Add endpoint**.
5. En la página del nuevo webhook, abre **Signing secret** → **Reveal** y copia el valor `whsec_...`.
6. En el `.env` de tu servidor de producción añade o edita:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxxxxx
   ```
7. Reinicia PHP/Laravel en el servidor (o el queue/worker si usas colas) para que cargue el nuevo `.env`.

---

## 6. Resumen de URLs que Stripe usa en tu proyecto

| Uso              | URL (ejemplo) |
|------------------|----------------|
| Éxito del pago   | `https://tu-dominio.com/stripe/success?session_id=...` (Laravel la genera) |
| Cancelación      | `https://tu-dominio.com/stripe/cancel` |
| Webhook          | `https://tu-dominio.com/api/stripe/webhook` |

Estas rutas ya están definidas en tu aplicación; solo necesitas que tu dominio apunte al servidor donde está Laravel.

---

## 7. Pasar a producción (cobros reales)

1. En el Dashboard de Stripe activa tu cuenta completando la información de negocio que te pidan (identidad, banco, etc.).
2. Cambia de **Test mode** a **Live mode** (interruptor en la parte superior del dashboard).
3. En **Developers** → **API keys** verás las claves **Live** (`pk_live_...` y `sk_live_...`).
4. En el `.env` de producción usa:
   ```env
   STRIPE_KEY=pk_live_...
   STRIPE_SECRET=sk_live_...
   STRIPE_WEBHOOK_SECRET=whsec_...   # El del endpoint de producción
   STRIPE_CURRENCY=pen
   LOGIC_TICKET_PAYMENT_DRIVER=stripe
   ```
5. Crea un **nuevo** endpoint de webhook en Live mode con la URL de producción (`https://tu-dominio.com/api/stripe/webhook`) y el evento `checkout.session.completed`, y usa su **Signing secret** en `STRIPE_WEBHOOK_SECRET`.
6. Prueba una compra real con una tarjeta real por un importe bajo y comprueba que la orden se marca como pagada y que llega el correo con los tickets.

---

## 8. Solución de problemas

- **“No se pudo verificar el pago”**: Revisa que `STRIPE_SECRET` sea la correcta (test con `sk_test_...`, live con `sk_live_...`) y que no haya espacios al copiar.
- **Webhook no recibe eventos**: Comprueba que la URL sea exactamente `/api/stripe/webhook`, que el endpoint en Stripe esté en “Enabled” y que `STRIPE_WEBHOOK_SECRET` coincida con el del endpoint.
- **Pago OK pero orden no se confirma**: Mira los logs de Laravel (`storage/logs/laravel.log`) y en Stripe → Developers → Webhooks → tu endpoint → “Recent deliveries” para ver si el webhook devuelve 200 o algún error.

Si quieres, en el siguiente paso podemos revisar juntos un caso concreto (por ejemplo un error que te salga en pantalla o en los logs).
