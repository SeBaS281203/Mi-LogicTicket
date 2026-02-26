<?php

namespace App\Http\Controllers\Cuenta;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CuentaDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $ordersQuery = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('customer_email', $user->email);
        });

        $totalOrders = (clone $ordersQuery)->count();
        $ordersPaid = (clone $ordersQuery)->where('status', 'paid')->count();
        $totalSpent = (clone $ordersQuery)->where('status', 'paid')->sum('total');

        $ticketsCount = Order::where('status', 'paid')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('customer_email', $user->email);
            })
            ->with('items.tickets')
            ->get()
            ->sum(fn ($o) => $o->items->sum(fn ($i) => $i->tickets->count()));

        $recentOrders = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('customer_email', $user->email);
        })
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        return view('cuenta.dashboard', compact(
            'totalOrders', 'ordersPaid', 'totalSpent', 'ticketsCount', 'recentOrders'
        ));
    }
}
