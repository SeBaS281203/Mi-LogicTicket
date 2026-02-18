<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
    /**
     * Usuario vuelve desde Mercado Pago (éxito). payment_id y status vienen por query.
     */
    public function success(Request $request): RedirectResponse
    {
        $paymentId = $request->query('payment_id');
        $externalReference = $request->query('external_reference'); // order_id
        $status = $request->query('status');

        $orderId = $externalReference ?: $request->query('order_id');
        if (! $orderId) {
            return redirect()->route('home')->with('error', 'Orden no encontrada.');
        }

        $order = Order::find($orderId);
        if (! $order || $order->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Orden no válida o ya procesada.');
        }

        $order->update([
            'payment_method' => 'mercadopago',
            'payment_id' => $paymentId ?? $request->query('preference_id'),
        ]);

        app(CheckoutController::class)->confirmPaidOrder($order);

        return redirect()->signedRoute('orders.confirmation', ['order' => $order])->with('success', '¡Pago realizado! Revisa tu correo.');
    }

    public function failure(Request $request): RedirectResponse
    {
        return redirect()->route('cart.index')->with('error', 'El pago no pudo completarse. Intenta de nuevo.');
    }

    public function pending(Request $request): RedirectResponse
    {
        return redirect()->route('orders.index')->with('info', 'Tu pago está pendiente. Te avisaremos cuando se confirme.');
    }
}
