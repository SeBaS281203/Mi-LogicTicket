<?php

namespace App\Services;

use App\Models\TicketType;

class CartSummaryService
{
    public function __construct(
        protected ?float $commissionPercentage = null
    ) {
        $this->commissionPercentage = $commissionPercentage
            ?? (float) (\App\Models\Setting::get('commission_percentage') ?: config('logic-ticket.commission_percentage', 0));
    }

    /**
     * @param  array<int, int>  $cart  [ticket_type_id => quantity]
     * @return array{items: array, subtotal: float, commission_amount: float, total: float, adjusted_cart: array, warnings: array}
     */
    public function buildFromCart(array $cart): array
    {
        $items = [];
        $subtotal = 0.0;
        $adjustedCart = [];
        $warnings = [];

        // Eager load all ticket types from the cart to avoid N+1 issues
        $ticketTypeIds = array_keys($cart);
        $ticketTypes = TicketType::with('event')->whereIn('id', $ticketTypeIds)->get()->keyBy('id');

        foreach ($cart as $ticketTypeId => $quantity) {
            $ticketType = $ticketTypes->get($ticketTypeId);
            if (!$ticketType) {
                continue;
            }
            if (!$ticketType->isOnSale()) {
                $warnings[] = "«{$ticketType->name}» ya no está disponible.";
                continue;
            }
            if ((int) $quantity <= 0) {
                continue;
            }
            $available = $ticketType->available_quantity;
            $qty = min((int) $quantity, $available);
            if ($qty <= 0) {
                $warnings[] = "«{$ticketType->name}»: se agotó el stock.";
                continue;
            }
            if ($qty < (int) $quantity) {
                $warnings[] = "«{$ticketType->name}»: solo quedan {$available}. Se ajustó la cantidad.";
            }
            $adjustedCart[$ticketTypeId] = $qty;
            $itemSubtotal = (float) $ticketType->price * $qty;
            $subtotal += $itemSubtotal;
            $items[] = (object) [
                'ticket_type' => $ticketType,
                'quantity' => $qty,
                'subtotal' => $itemSubtotal,
            ];
        }

        $commissionAmount = round($subtotal * ($this->commissionPercentage / 100), 2);
        $total = round($subtotal + $commissionAmount, 2);

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'commission_amount' => $commissionAmount,
            'total' => $total,
            'adjusted_cart' => $adjustedCart,
            'warnings' => $warnings,
        ];
    }

    public function getCommissionPercentage(): float
    {
        return $this->commissionPercentage;
    }
}
