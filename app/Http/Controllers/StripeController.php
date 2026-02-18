<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function success(Request $request): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('home')->with('error', 'Sesión inválida.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($sessionId);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('home')->with('error', 'No se pudo verificar el pago.');
        }

        $orderId = $session->metadata->order_id ?? null;
        if (! $orderId) {
            return redirect()->route('home')->with('error', 'Orden no encontrada.');
        }

        $order = Order::find($orderId);
        if (! $order || $order->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Orden no válida o ya procesada.');
        }

        $order->update([
            'payment_method' => 'stripe',
            'payment_id' => $session->payment_intent ?? $sessionId,
        ]);

        app(CheckoutController::class)->confirmPaidOrder($order);

        return redirect()->signedRoute('orders.confirmation', ['order' => $order])->with('success', '¡Pago realizado! Revisa tu correo.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('cart.index')->with('info', 'Pago cancelado. Tu carrito sigue disponible.');
    }
}
