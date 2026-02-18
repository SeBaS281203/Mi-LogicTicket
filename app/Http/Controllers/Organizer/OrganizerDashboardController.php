<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrganizerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $eventIds = $user->events()->pluck('id');

        $totalSales = OrderItem::whereIn('event_id', $eventIds)
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->sum('subtotal');

        $totalTicketsSold = OrderItem::whereIn('event_id', $eventIds)
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->sum('quantity');

        $recentOrders = OrderItem::whereIn('event_id', $eventIds)
            ->with(['order', 'event'])
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->latest()
            ->take(15)
            ->get();

        $events = $user->events()->withCount('ticketTypes')->latest()->take(5)->get();

        return view('organizer.dashboard', compact(
            'totalSales', 'totalTicketsSold', 'recentOrders', 'events'
        ));
    }
}
