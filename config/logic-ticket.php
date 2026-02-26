<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Comisión por servicio
    |--------------------------------------------------------------------------
    | Porcentaje (0-100) aplicado sobre el subtotal de la compra.
    */
    'commission_percentage' => (float) env('LOGIC_TICKET_COMMISSION_PERCENTAGE', 5),

    /*
    |--------------------------------------------------------------------------
    | Pasarelas de pago
    |--------------------------------------------------------------------------
    */
    'payment_driver' => env('LOGIC_TICKET_PAYMENT_DRIVER', 'stripe'), // stripe, mercadopago, none

    'stripe' => [
        'enabled' => ! empty(env('STRIPE_SECRET')) || ! empty(config('services.stripe.secret')),
        'currency' => env('STRIPE_CURRENCY', config('services.stripe.currency', 'pen')),
    ],

    'mercadopago' => [
        'enabled' => ! empty(env('MP_ACCESS_TOKEN')),
        'public_key' => env('MP_PUBLIC_KEY'),
        'access_token' => env('MP_ACCESS_TOKEN'),
        'currency' => 'PEN',
    ],

];
