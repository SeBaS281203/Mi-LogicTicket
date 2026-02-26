<?php

namespace App\Services;

use App\Models\Order;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::SERVER);
    }

    public function createPreference(Order $order): object
    {
        $client = new PreferenceClient();

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                "id" => $item->id,
                "title" => $item->event_title . " - " . $item->ticket_type_name,
                "quantity" => (int) $item->quantity,
                "unit_price" => (float) ($item->subtotal / $item->quantity),
            ];
        }

        // Add commission if any
        if ($order->commission_amount > 0) {
            $items[] = [
                "id" => "commission",
                "title" => "Comisión por servicio",
                "quantity" => 1,
                "unit_price" => (float) $order->commission_amount,
            ];
        }

        $request = [
            "items" => $items,
            "payer" => [
                "name" => $order->customer_name,
                "email" => $order->customer_email,
            ],
            "back_urls" => [
                "success" => route('payment.success'),
                "failure" => route('payment.failure'),
                "pending" => route('payment.pending'),
            ],
            "auto_return" => "approved",
            "notification_url" => config('services.mercadopago.webhook_url'),
            "external_reference" => (string) $order->order_number,
            "statement_descriptor" => "ChiclayoTicket",
        ];

        return $client->create($request);
    }
}
