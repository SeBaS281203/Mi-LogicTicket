<?php

namespace App\Http\Controllers;

use App\Services\CartSummaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartSummaryService $cartSummary): View
    {
        $cart = session('cart', []);
        $summary = $cartSummary->buildFromCart($cart);

        return view('cart.index', [
            'items' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'commission_amount' => $summary['commission_amount'],
            'commission_percentage' => $cartSummary->getCommissionPercentage(),
            'total' => $summary['total'],
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

        return back()->with('success', 'Añadido al carrito.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:0',
        ]);
        $cart = session('cart', []);
        if ($validated['quantity'] === 0) {
            unset($cart[$validated['ticket_type_id']]);
        } else {
            $ticketType = \App\Models\TicketType::findOrFail($validated['ticket_type_id']);
            $cart[$validated['ticket_type_id']] = min($validated['quantity'], $ticketType->available_quantity);
        }
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Carrito actualizado.');
    }

    public function remove(int $ticketType): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$ticketType]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }
}
