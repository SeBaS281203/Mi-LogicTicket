<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\CartSummaryService;
use App\Services\MercadoPagoService;
use App\Services\StripeService;
use App\Services\TicketPdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(CartSummaryService $cartSummary): View|RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'No hay entradas en tu resumen.');
        }

        $summary = $cartSummary->buildFromCart($cart);
        if (empty($summary['items'])) {
            session()->forget('cart');
            return redirect()->route('cart.index')->with('error', 'No hay entradas válidas. El stock pudo haber cambiado.');
        }

        if (!empty($summary['warnings'])) {
            session(['cart' => $summary['adjusted_cart']]);
        }

        $paymentDriver = config('logic-ticket.payment_driver', 'stripe');
        $paymentProviderName = match ($paymentDriver) {
            'stripe' => 'Stripe',
            'mercadopago' => 'Mercado Pago',
            default => 'ChiclayoTicket',
        };

        $useStripe = config('logic-ticket.stripe.enabled') && ($paymentDriver === 'stripe' || $paymentDriver === '');

        $itemsForFrontend = collect($summary['items'])->map(function ($item) {
            $tt = $item->ticket_type;
            $qty = (int) $item->quantity;
            $unitPrice = (float) ($tt->price ?? 0);
            return [
                'ticket_type' => [
                    'id' => $tt->id,
                    'name' => $tt->name,
                    'price' => $unitPrice,
                    'event' => ['title' => $tt->event->title ?? 'Evento'],
                ],
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'subtotal' => $item->subtotal ?? ($unitPrice * $qty),
            ];
        })->values()->all();

        return view('checkout.index', [
            'items' => $summary['items'],
            'items_for_js' => $itemsForFrontend,
            'subtotal' => $summary['subtotal'],
            'commission_amount' => $summary['commission_amount'],
            'commission_percentage' => $cartSummary->getCommissionPercentage(),
            'total' => $summary['total'],
            'warnings' => $summary['warnings'] ?? [],
            'payment_provider_name' => $paymentProviderName,
            'stripe_enabled' => $useStripe,
            'stripe_key' => $useStripe ? (config('services.stripe.key') ?? '') : '',
        ]);
    }

    public function store(Request $request, CartSummaryService $cartSummary): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:20',
            'accept_terms' => 'required|accepted',
        ], [
            'accept_terms.required' => 'Debes aceptar los términos y condiciones para continuar.',
            'accept_terms.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Resumen vacío.');
        }

        $summary = $cartSummary->buildFromCart($cart);
        if (empty($summary['items'])) {
            session()->forget('cart');
            return redirect()->route('cart.index')->with('error', 'Las entradas seleccionadas ya no están disponibles. Por favor, elige otras.');
        }

        $subtotal = $summary['subtotal'];
        $commissionAmount = $summary['commission_amount'];
        $total = $summary['total'];
        $items = $summary['items'];

        try {
            $order = $this->createOrderWithLock($validated, $items, $subtotal, $commissionAmount, $total);
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', 'No se pudo completar la compra: ' . $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Ocurrió un error inesperado. Por favor, intenta de nuevo.');
        }

        $paymentDriver = config('logic-ticket.payment_driver', 'stripe');
        $useStripe = config('logic-ticket.stripe.enabled') && ($paymentDriver === 'stripe' || $paymentDriver === '');
        $useMercadoPago = config('logic-ticket.mercadopago.enabled') && $paymentDriver === 'mercadopago';

        if ($useStripe) {
            try {
                $redirectUrl = app(StripeService::class)->createCheckoutSession($order);
                return $request->ajax() ? response()->json(['redirect' => $redirectUrl]) : redirect()->away($redirectUrl);
            } catch (\Throwable $e) {
                report($e);

                // Fallback automático a Mercado Pago cuando Stripe está caído o mal configurado.
                if (config('logic-ticket.mercadopago.enabled')) {
                    try {
                        $preference = app(MercadoPagoService::class)->createPreference($order);
                        return $request->ajax()
                            ? response()->json(['redirect' => $preference->init_point, 'provider' => 'mercadopago'])
                            : redirect()->away($preference->init_point);
                    } catch (\Throwable $mpError) {
                        report($mpError);
                    }
                }

                // Entorno local: completar compra sin pasarela para no bloquear QA/desarrollo.
                if (app()->environment(['local', 'testing'])) {
                    $redirect = $this->confirmOrderWithoutGateway($order)->getTargetUrl();
                    return $request->ajax()
                        ? response()->json(['redirect' => $redirect, 'provider' => 'manual'])
                        : redirect()->to($redirect)->with('info', 'Pago completado en modo local.');
                }

                return $request->ajax()
                    ? response()->json(['message' => 'Error al iniciar el pago. Verifica la configuración de Stripe.'], 500)
                    : back()->with('error', 'Error al iniciar el pago. Verifica la configuración de Stripe.');
            }
        }

        if ($useMercadoPago) {
            try {
                $preference = app(MercadoPagoService::class)->createPreference($order);
                return $request->ajax() 
                    ? response()->json(['redirect' => $preference->init_point]) 
                    : redirect()->away($preference->init_point);
            } catch (\Throwable $e) {
                report($e);

                if (app()->environment(['local', 'testing'])) {
                    $redirect = $this->confirmOrderWithoutGateway($order)->getTargetUrl();
                    return $request->ajax()
                        ? response()->json(['redirect' => $redirect, 'provider' => 'manual'])
                        : redirect()->to($redirect)->with('info', 'Pago completado en modo local.');
                }

                return $request->ajax()
                    ? response()->json(['error' => 'Error al conectar con Mercado Pago.'], 500)
                    : back()->with('error', 'Error al conectar con Mercado Pago. Intenta de nuevo.');
            }
        }

        // Sin pasarela: marcar como pagado, crear tickets, enviar email con PDF
        if ($request->ajax()) {
            $this->confirmOrderWithoutGateway($order);
            return response()->json(['redirect' => route('orders.confirmation', ['order' => $order])]);
        }

        return $this->confirmOrderWithoutGateway($order);
    }

    /**
     * Crea la orden y un PaymentIntent de Stripe para pago con tarjeta en la misma página (Elements).
     */
    public function createPaymentIntent(Request $request, CartSummaryService $cartSummary): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:20',
            'accept_terms' => 'required|accepted',
        ], [
            'accept_terms.required' => 'Debes aceptar los términos y condiciones.',
            'accept_terms.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Resumen vacío.'], 422);
        }

        $summary = $cartSummary->buildFromCart($cart);
        if (empty($summary['items'])) {
            session()->forget('cart');
            return response()->json(['message' => 'Las entradas ya no están disponibles.'], 422);
        }

        $subtotal = $summary['subtotal'];
        $commissionAmount = $summary['commission_amount'];
        $total = $summary['total'];
        $items = $summary['items'];

        try {
            $order = $this->createOrderWithLock($validated, $items, $subtotal, $commissionAmount, $total);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Error inesperado. Intenta de nuevo.'], 500);
        }

        if (! config('logic-ticket.stripe.enabled')) {
            if (config('logic-ticket.mercadopago.enabled')) {
                try {
                    $preference = app(MercadoPagoService::class)->createPreference($order);
                    return response()->json(['redirect' => $preference->init_point, 'provider' => 'mercadopago']);
                } catch (\Throwable $mpError) {
                    report($mpError);

                    if (app()->environment(['local', 'testing'])) {
                        $redirect = $this->confirmOrderWithoutGateway($order)->getTargetUrl();
                        return response()->json(['redirect' => $redirect, 'provider' => 'manual']);
                    }
                }
            }
            return response()->json(['message' => 'Pago con tarjeta no disponible.'], 503);
        }

        try {
            $clientSecret = app(StripeService::class)->createPaymentIntent($order);
            return response()->json(['client_secret' => $clientSecret, 'order_id' => $order->id]);
        } catch (\Throwable $e) {
            report($e);
            // Fallback automático a Mercado Pago si Stripe falla (ej: API key inválida).
            if (config('logic-ticket.mercadopago.enabled')) {
                try {
                    $preference = app(MercadoPagoService::class)->createPreference($order);
                    return response()->json(['redirect' => $preference->init_point, 'provider' => 'mercadopago']);
                } catch (\Throwable $mpError) {
                    report($mpError);
                }
            }

            if (app()->environment(['local', 'testing'])) {
                $redirect = $this->confirmOrderWithoutGateway($order)->getTargetUrl();
                return response()->json(['redirect' => $redirect, 'provider' => 'manual']);
            }

            return response()->json(['message' => 'Error al iniciar el pago. Verifica la configuración de Stripe.'], 500);
        }
    }

    /**
     * Crea la orden y los items dentro de DB::transaction con lockForUpdate para validar stock en tiempo real.
     *
     * @throws \RuntimeException si no hay stock suficiente
     */
    private function createOrderWithLock(array $validated, array $items, float $subtotal, float $commissionAmount, float $total): Order
    {
        return DB::transaction(function () use ($validated, $items, $subtotal, $commissionAmount, $total) {
            $ticketTypeIds = array_map(fn ($i) => $i->ticket_type->id, $items);
            TicketType::whereIn('id', $ticketTypeIds)->lockForUpdate()->get();

            foreach ($items as $item) {
                $tt = TicketType::find($item->ticket_type->id);
                $available = $tt->quantity - $tt->quantity_sold;
                if ($item->quantity > $available) {
                    throw new \RuntimeException(
                        'Ya no hay stock suficiente para "' . $tt->name . '". Disponible: ' . $available
                    );
                }
            }

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'subtotal' => $subtotal,
                'commission_amount' => $commissionAmount,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_type_id' => $item->ticket_type->id,
                    'event_id' => $item->ticket_type->event_id,
                    'ticket_type_name' => $item->ticket_type->name,
                    'event_title' => $item->ticket_type->event->title,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->ticket_type->price,
                    'subtotal' => $item->subtotal,
                ]);
            }

            return $order->fresh('items');
        });
    }

    private function redirectToMercadoPago(Order $order, array $items, float $total): RedirectResponse
    {
        try {
            $preference = app(MercadoPagoService::class)->createPreference($order);
            return redirect()->away($preference->init_point);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Error al conectar con Mercado Pago. Intenta de nuevo.');
        }
    }

    private function confirmOrderWithoutGateway(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid', 'payment_method' => 'manual']);
            foreach ($order->items as $item) {
                $item->ticketType->increment('quantity_sold', $item->quantity);
                for ($i = 0; $i < $item->quantity; $i++) {
                    Ticket::create([
                        'order_item_id' => $item->id,
                        'code' => Ticket::generateUniqueCode(),
                    ]);
                }
            }
        });

        session()->forget('cart');
        $this->sendOrderConfirmationWithPdf($order->fresh(['items.tickets', 'items.event']));

        return redirect()->signedRoute('orders.confirmation', ['order' => $order])->with('success', '¡Compra realizada! Revisa tu correo.');
    }

    /**
     * Llamado desde Stripe/MercadoPago success para marcar orden pagada, crear tickets y enviar email con PDF.
     */
    public function confirmPaidOrder(Order $order): void
    {
        if ($order->status !== 'pending') {
            return;
        }

        DB::transaction(function () use ($order) {
            $order->lockForUpdate()->update(['status' => 'paid']);
            foreach ($order->items as $item) {
                $item->ticketType->increment('quantity_sold', $item->quantity);
                for ($i = 0; $i < $item->quantity; $i++) {
                    Ticket::create([
                        'order_item_id' => $item->id,
                        'code' => Ticket::generateUniqueCode(),
                    ]);
                }
            }
        });

        $this->sendOrderConfirmationWithPdf($order->fresh(['items.tickets', 'items.event']));
    }

    private function sendOrderConfirmationWithPdf(Order $order): void
    {
        try {
            $pdfService = app(TicketPdfService::class);
            $pdfContent = $pdfService->generateOrderTicketsPdfContent($order);
            Mail::to($order->customer_email)->queue(new OrderConfirmation($order, $pdfContent));
        } catch (\Throwable $e) {
            Log::error('Order Confirmation Email Error: ' . $e->getMessage());
            report($e);
        }
    }
}
