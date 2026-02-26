<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function confirmation(Request $request, Order $order): View
    {
        $allowed = false;
        if (Auth::check()) {
            $allowed = $order->user_id === Auth::id() || $order->customer_email === Auth::user()->email;
        }
        if (! $allowed && ! $request->hasValidSignature()) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }
        $order->load(['items', 'items.tickets', 'items.event.user']);
        return view('orders.confirmation', compact('order'));
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $orders = Order::with(['items', 'items.tickets'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
            })
            ->latest()
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }
}
