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
        $order->load('items');
        return view('orders.confirmation', compact('order'));
    }

    public function index(Request $request): View
    {
        $query = Order::with('items');
        if (!$request->user()->isAdmin()) {
            $query->where(function ($q) {
                $q->where('user_id', Auth::id())
                    ->orWhere('customer_email', Auth::user()->email);
            });
        }
        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }
}
