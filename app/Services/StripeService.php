<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crea una sesión de Stripe Checkout y devuelve la URL para redirigir al usuario.
     */
    public function createCheckoutSession(Order $order): string
    {
        $order->load('items');
        $currency = config('services.stripe.currency', 'pen');

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $item->event_title . ' - ' . $item->ticket_type_name,
                        'description' => $item->quantity . ' entrada(s)',
                    ],
                    'unit_amount' => (int) round($item->unit_price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        $commissionAmount = (float) $order->commission_amount;
        if ($commissionAmount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => 'Comisión por servicio',
                        'description' => config('logic-ticket.commission_percentage', 0) . '%',
                    ],
                    'unit_amount' => (int) round($commissionAmount * 100),
                ],
                'quantity' => 1,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),
            'metadata' => ['order_id' => $order->id],
            'customer_email' => $order->customer_email,
        ]);

        return $session->url;
    }

    /**
     * Recupera una sesión de Checkout por ID (para success o webhook).
     */
    public function retrieveSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId, ['expand' => ['payment_intent']]);
    }

    /**
     * Crea un PaymentIntent para pago con tarjeta en la misma página (Stripe Elements).
     * Devuelve el client_secret para confirmar desde el frontend.
     */
    public function createPaymentIntent(Order $order): string
    {
        $amount = (int) round((float) $order->total * 100); // centimos
        $currency = config('services.stripe.currency', 'pen');

        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => ['order_id' => $order->id],
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return $intent->client_secret;
    }
}
