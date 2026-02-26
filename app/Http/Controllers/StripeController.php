<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function success(Request $request, StripeService $stripe): RedirectResponse
    {
        $paymentIntentId = $request->query('payment_intent');
        if ($paymentIntentId) {
            return $this->handlePaymentIntentSuccess($paymentIntentId);
        }

        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('cart.index')->with('error', 'Sesión inválida.');
        }

        try {
            $session = $stripe->retrieveSession($sessionId);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('cart.index')->with('error', 'No se pudo verificar el pago. Intenta de nuevo.');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('cart.index')->with('info', 'El pago no se completó. Tu carrito sigue disponible.');
        }

        $orderId = $session->metadata->order_id ?? null;
        if (! $orderId) {
            return redirect()->route('cart.index')->with('error', 'Orden no encontrada.');
        }

        $order = Order::find($orderId);
        if (! $order || $order->status !== 'pending') {
            return redirect()->route('cart.index')->with('error', 'Orden no válida o ya procesada.');
        }

        $order->update([
            'payment_method' => 'stripe',
            'payment_id' => $session->payment_intent ?? $sessionId,
        ]);

        app(CheckoutController::class)->confirmPaidOrder($order);
        session()->forget('cart');

        return redirect()->signedRoute('orders.confirmation', ['order' => $order])->with('success', '¡Pago realizado! Revisa tu correo.');
    }

    private function handlePaymentIntentSuccess(string $paymentIntentId): RedirectResponse
    {
        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $intent = $stripe->paymentIntents->retrieve($paymentIntentId);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('cart.index')->with('error', 'No se pudo verificar el pago.');
        }

        if ($intent->status !== 'succeeded') {
            return redirect()->route('cart.index')->with('info', 'El pago no se completó. Tu carrito sigue disponible.');
        }

        $orderId = $intent->metadata->order_id ?? null;
        if (! $orderId) {
            return redirect()->route('cart.index')->with('error', 'Orden no encontrada.');
        }

        $order = Order::find($orderId);
        if (! $order || $order->status !== 'pending') {
            return redirect()->route('cart.index')->with('error', 'Orden no válida o ya procesada.');
        }

        $order->update([
            'payment_method' => 'stripe',
            'payment_id' => $paymentIntentId,
        ]);

        app(CheckoutController::class)->confirmPaidOrder($order);
        session()->forget('cart');

        return redirect()->signedRoute('orders.confirmation', ['order' => $order])->with('success', '¡Pago realizado! Revisa tu correo.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('cart.index')->with('info', 'Pago cancelado. Tu carrito sigue disponible.');
    }

    /**
     * Webhook de Stripe (checkout.session.completed y payment_intent.succeeded como respaldo).
     * Útil si el usuario cierra el navegador antes de llegar a success_url.
     */
    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (empty($webhookSecret)) {
            Log::warning('Stripe webhook: STRIPE_WEBHOOK_SECRET no configurado.');
            return response()->json(['error' => 'Webhook no configurado'], 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook payload inválido: ' . $e->getMessage());
            return response()->json(['error' => 'Payload inválido'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook firma inválida: ' . $e->getMessage());
            return response()->json(['error' => 'Firma inválida'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            if ($session->payment_status === 'paid') {
                $orderId = $session->metadata->order_id ?? null;
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order && $order->status === 'pending') {
                        $order->update([
                            'payment_method' => 'stripe',
                            'payment_id' => $session->payment_intent ?? $session->id,
                        ]);
                        app(CheckoutController::class)->confirmPaidOrder($order);
                        Log::info('Stripe webhook: orden ' . $order->order_number . ' confirmada.');
                    }
                }
            }
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $orderId = $intent->metadata->order_id ?? null;
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->status === 'pending') {
                    $order->update([
                        'payment_method' => 'stripe',
                        'payment_id' => $intent->id,
                    ]);
                    app(CheckoutController::class)->confirmPaidOrder($order);
                    Log::info('Stripe webhook PaymentIntent: orden ' . $order->order_number . ' confirmada.');
                }
            }
        }

        return response()->json(['received' => true]);
    }
}
