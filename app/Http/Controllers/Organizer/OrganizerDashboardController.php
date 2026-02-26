<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $totalEvents = $user->events()->count();

        $recentOrders = OrderItem::whereIn('event_id', $eventIds)
            ->with(['order', 'event'])
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->latest()
            ->take(15)
            ->get();

        $events = $user->events()->withCount('ticketTypes')->latest()->take(5)->get();

        $driver = DB::getDriverName();
        $revenueQuery = OrderItem::whereIn('event_id', $eventIds)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid');
        $revenueByMonthRaw = match ($driver) {
            'mysql' => $revenueQuery->clone()->selectRaw('DATE_FORMAT(orders.created_at, "%Y-%m") as month, SUM(order_items.subtotal) as total')->groupBy('month')->orderBy('month')->get()->pluck('total', 'month')->toArray(),
            'sqlite' => $revenueQuery->clone()->selectRaw("strftime('%Y-%m', orders.created_at) as month, SUM(order_items.subtotal) as total")->groupBy('month')->orderBy('month')->get()->pluck('total', 'month')->toArray(),
            default => $revenueQuery->clone()->selectRaw("to_char(orders.created_at, 'YYYY-MM') as month, SUM(order_items.subtotal) as total")->groupByRaw("to_char(orders.created_at, 'YYYY-MM')")->orderBy('month')->get()->pluck('total', 'month')->toArray(),
        };

        $last12 = [];
        $last12Labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i)->format('Y-m');
            $last12[] = (float) ($revenueByMonthRaw[$m] ?? 0);
            $last12Labels[] = now()->subMonths($i)->translatedFormat('M y');
        }

        $eventsByStatus = [
            'published' => $user->events()->where('status', 'published')->count(),
            'pending' => $user->events()->where('status', 'pending_approval')->count(),
            'draft' => $user->events()->where('status', 'draft')->count(),
            'cancelled' => $user->events()->where('status', 'cancelled')->count(),
        ];

        $revenueByMonthLabels = $last12Labels;
        $revenueByMonthData = $last12;

        return view('organizer.dashboard', compact(
            'totalSales', 'totalTicketsSold', 'totalEvents', 'recentOrders', 'events',
            'revenueByMonthLabels', 'revenueByMonthData', 'eventsByStatus'
        ));
    }
}
