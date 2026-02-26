<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use App\Services\CartSummaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Devuelve stock disponible para un tipo de entrada (validación en tiempo real).
     */
    public function stock(int $ticketTypeId): JsonResponse
    {
        $tt = TicketType::with('event')->find($ticketTypeId);
        if (!$tt) {
            return response()->json(['available' => 0, 'on_sale' => false]);
        }
        return response()->json([
            'available' => $tt->available_quantity,
            'on_sale' => $tt->isOnSale(),
            'event_published' => $tt->event?->status === 'published',
        ]);
    }
    public function index(CartSummaryService $cartSummary): View
    {
        $this->processPendingCartAdd();
        $cart = session('cart', []);
        $summary = $cartSummary->buildFromCart($cart);

        if (!empty($summary['warnings']) && !empty($summary['adjusted_cart'])) {
            session(['cart' => $summary['adjusted_cart']]);
        }

        return view('cart.index', [
            'items' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'commission_amount' => $summary['commission_amount'],
            'commission_percentage' => $cartSummary->getCommissionPercentage(),
            'total' => $summary['total'],
            'warnings' => $summary['warnings'] ?? [],
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $ticketType = \App\Models\TicketType::with('event')->findOrFail($validated['ticket_type_id']);
        if ($ticketType->event->status !== 'published') {
            return back()->with('error', 'Este evento no está disponible.');
        }
        if (!$ticketType->isOnSale()) {
            return back()->with('error', 'Este tipo de ticket no está disponible.');
        }
        $qty = (int) $validated['quantity'];
        if ($qty > $ticketType->available_quantity) {
            return back()->with('error', 'Solo hay ' . $ticketType->available_quantity . ' entradas disponibles.');
        }
        $cart = session('cart', []);
        $current = $cart[$ticketType->id] ?? 0;
        $newQty = min($current + $qty, $ticketType->available_quantity);
        $cart[$ticketType->id] = $newQty;
        session(['cart' => $cart]);

        return back()->with('success', 'Añadido al resumen.');
    }

    public function update(Request $request, ?int $ticketType = null): JsonResponse|RedirectResponse
    {
        // El ID puede venir de la URL ($ticketType) o del cuerpo (fallback)
        $id = $ticketType ?? $request->input('ticket_type_id');
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);
        
        $ticketTypeModel = \App\Models\TicketType::findOrFail($id);
        $cart = session('cart', []);
        
        if ($validated['quantity'] === 0) {
            unset($cart[$id]);
            $newQty = 0;
        } else {
            $newQty = min($validated['quantity'], $ticketTypeModel->available_quantity);
            $cart[$id] = $newQty;
        }
        
        session(['cart' => $cart]);

        if ($request->ajax()) {
            $cartSummary = app(CartSummaryService::class);
            $summary = $cartSummary->buildFromCart($cart);
            
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $ticketTypeModel->id,
                    'quantity' => $newQty,
                    'subtotal' => (float) $ticketTypeModel->price * $newQty
                ],
                'summary' => [
                    'subtotal' => $summary['subtotal'],
                    'commission_amount' => $summary['commission_amount'],
                    'total' => $summary['total'],
                    'item_count' => count($summary['items'])
                ]
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Resumen actualizado.');
    }

    public function remove(Request $request, int $ticketType): JsonResponse|RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$ticketType]);
        session(['cart' => $cart]);

        if ($request->ajax()) {
            $cartSummary = app(CartSummaryService::class);
            $summary = $cartSummary->buildFromCart($cart);
            
            return response()->json([
                'success' => true,
                'summary' => [
                    'subtotal' => $summary['subtotal'],
                    'commission_amount' => $summary['commission_amount'],
                    'total' => $summary['total'],
                    'item_count' => count($summary['items'])
                ]
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function summary(CartSummaryService $cartSummary): JsonResponse
    {
        $cart = session('cart', []);
        $summary = $cartSummary->buildFromCart($cart);
        
        return response()->json([
            'subtotal' => $summary['subtotal'],
            'commission_amount' => $summary['commission_amount'],
            'total' => $summary['total'],
            'item_count' => count($summary['items'])
        ]);
    }

    private function processPendingCartAdd(): void
    {
        $pending = session()->pull('pending_cart_add');
        if (!$pending || empty($pending['ticket_type_id'])) {
            return;
        }

        $ticketType = TicketType::with('event')->find($pending['ticket_type_id']);
        if (!$ticketType || $ticketType->event?->status !== 'published' || !$ticketType->isOnSale()) {
            return;
        }

        $qty = max(1, (int) ($pending['quantity'] ?? 1));
        $qty = min($qty, $ticketType->available_quantity);

        $cart = session('cart', []);
        $current = $cart[$ticketType->id] ?? 0;
        $cart[$ticketType->id] = min($current + $qty, $ticketType->available_quantity);
        session(['cart' => $cart]);
    }
}
