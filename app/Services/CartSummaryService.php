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
     * @return array{items: array, subtotal: float, commission_amount: float, total: float}
     */
    public function buildFromCart(array $cart): array
    {
        $items = [];
        $subtotal = 0.0;

        foreach ($cart as $ticketTypeId => $quantity) {
            $ticketType = TicketType::with('event')->find($ticketTypeId);
            if (!$ticketType || !$ticketType->isOnSale() || $quantity <= 0) {
                continue;
            }
            $qty = min((int) $quantity, $ticketType->available_quantity);
            if ($qty <= 0) {
                continue;
            }
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
        ];
    }

    public function getCommissionPercentage(): float
    {
        return $this->commissionPercentage;
    }
}
