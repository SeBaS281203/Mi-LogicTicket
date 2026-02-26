<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user', 'items.event')->latest();

        if ($request->filled('organizer_id')) {
            $query->whereHas('items.event', function ($q) use ($request) {
                $q->where('user_id', $request->organizer_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', '%' . $term . '%')
                    ->orWhere('customer_email', 'like', '%' . $term . '%')
                    ->orWhere('customer_name', 'like', '%' . $term . '%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.event', 'items.ticketType', 'user']);

        return view('admin.orders.show', compact('order'));
    }
}
