<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\CartSummaryService;
use App\Services\TicketPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(CartSummaryService $cartSummary): View|RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $summary = $cartSummary->buildFromCart($cart);
        if (empty($summary['items'])) {
            return redirect()->route('cart.index')->with('error', 'No hay entradas válidas en el carrito.');
        }

        return view('checkout.index', [
            'items' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'commission_amount' => $summary['commission_amount'],
            'commission_percentage' => $cartSummary->getCommissionPercentage(),
            'total' => $summary['total'],
        ]);
    }

    public function store(Request $request, CartSummaryService $cartSummary): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Carrito vacío.');
        }

        $summary = $cartSummary->buildFromCart($cart);
        if (empty($summary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Algunas entradas ya no están disponibles. Revisa el carrito.');
        }

        $subtotal = $summary['subtotal'];
        $commissionAmount = $summary['commission_amount'];
        $total = $summary['total'];
        $items = $summary['items'];

        try {
            $order = $this->createOrderWithLock($validated, $items, $subtotal, $commissionAmount, $total);
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $paymentDriver = config('logic-ticket.payment_driver', 'stripe');
        $useStripe = config('logic-ticket.stripe.enabled') && ($paymentDriver === 'stripe' || $paymentDriver === '');
        $useMercadoPago = config('logic-ticket.mercadopago.enabled') && $paymentDriver === 'mercadopago';

        if ($useStripe) {
            return $this->redirectToStripe($order, $items, $total);
        }

        if ($useMercadoPago) {
            return $this->redirectToMercadoPago($order, $items, $total);
        }

        // Sin pasarela: marcar como pagado, crear tickets, enviar email con PDF
        return $this->confirmOrderWithoutGateway($order);
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

    private function redirectToStripe(Order $order, array $items, float $total): RedirectResponse
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $currency = config('services.stripe.currency', 'pen');
            $lineItems = array_map(function ($item) use ($currency) {
                return [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $item->ticket_type->event->title . ' - ' . $item->ticket_type->name,
                            'description' => $item->quantity . ' entrada(s)',
                        ],
                        'unit_amount' => (int) round($item->ticket_type->price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }, $items);

            $order->load('items');
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

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'metadata' => ['order_id' => $order->id],
                'customer_email' => $order->customer_email,
            ]);
            session()->forget('cart');

            return redirect()->away($session->url);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Error al conectar con la pasarela de pago. Intenta de nuevo.');
        }
    }

    private function redirectToMercadoPago(Order $order, array $items, float $total): RedirectResponse
    {
        try {
            $preference = $this->buildMercadoPagoPreference($order, $items, $total);
            session()->forget('cart');
            return redirect()->away($preference->init_point);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Error al conectar con Mercado Pago. Intenta de nuevo.');
        }
    }

    private function buildMercadoPagoPreference(Order $order, array $items, float $total): \MercadoPago\Resources\Preference
    {
        \MercadoPago\MercadoPagoConfig::setAccessToken(config('logic-ticket.mercadopago.access_token'));

        $preferenceItems = [];
        foreach ($items as $item) {
            $preferenceItems[] = [
                'title' => $item->ticket_type->event->title . ' - ' . $item->ticket_type->name,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->ticket_type->price,
                'currency_id' => config('logic-ticket.mercadopago.currency', 'PEN'),
            ];
        }
        $commissionAmount = (float) $order->commission_amount;
        if ($commissionAmount > 0) {
            $preferenceItems[] = [
                'title' => 'Comisión por servicio',
                'quantity' => 1,
                'unit_price' => $commissionAmount,
                'currency_id' => config('logic-ticket.mercadopago.currency', 'PEN'),
            ];
        }

        $client = new \MercadoPago\Client\Preference\PreferenceClient();
        $preference = $client->create([
            'items' => $preferenceItems,
            'payer' => [
                'email' => $order->customer_email,
                'name' => $order->customer_name,
            ],
            'back_urls' => [
                'success' => route('mercadopago.success'),
                'failure' => route('mercadopago.failure'),
                'pending' => route('mercadopago.pending'),
            ],
            'auto_return' => 'approved',
            'external_reference' => (string) $order->id,
        ]);

        return $preference;
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
            Mail::to($order->customer_email)->send(new OrderConfirmation($order, $pdfContent));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
