<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'events' => Event::count(),
            'organizers' => User::where('role', 'organizer')->count(),
            'sales' => Order::where('status', 'paid')->count(),
            'revenue' => (float) Order::where('status', 'paid')->sum('total'),
            'events_published' => Event::where('status', 'published')->count(),
            'events_pending' => Event::where('status', 'pending_approval')->count(),
            'users' => User::count(),
            'clients' => User::where('role', 'client')->count(),
        ];

        $driver = DB::getDriverName();
        $revenueByMonth = Order::where('status', 'paid')
            ->when($driver === 'mysql', fn ($q) => $q->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total')->groupBy('month'))
            ->when($driver === 'sqlite', fn ($q) => $q->selectRaw("strftime('%Y-%m', created_at) as month, SUM(total) as total")->groupBy('month'))
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $last12 = [];
        $last12Labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i)->format('Y-m');
            $last12[] = (float) ($revenueByMonth[$m] ?? 0);
            $last12Labels[] = now()->subMonths($i)->translatedFormat('M y');
        }

        $eventsByStatus = [
            'published' => Event::where('status', 'published')->count(),
            'pending' => Event::where('status', 'pending_approval')->count(),
            'draft' => Event::where('status', 'draft')->count(),
            'cancelled' => Event::where('status', 'cancelled')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();
        $recentEvents = Event::with('user', 'category')->latest()->take(5)->get();

        // 1. Ingresos por Organizador (Tabla)
        $organizerEarnings = DB::table('users')
            ->where('users.role', 'organizer')
            ->leftJoin('events', 'users.id', '=', 'events.user_id')
            ->leftJoin('order_items', 'events.id', '=', 'order_items.event_id')
            ->leftJoin('orders', function($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.status', '=', 'paid');
            })
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(DISTINCT events.id) as events_count'),
                DB::raw('SUM(order_items.quantity) as tickets_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_revenue')
            ->get();

        // 2. Ingresos por Organizador (Gráfico - Datasets)
        $monthKeys = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthKeys[] = now()->subMonths($i)->format('Y-m');
        }

        $organizersForChart = User::where('role', 'organizer')->get();
        $chartDatasets = [];
        $colors = [
            'rgba(99, 102, 241, 0.7)',  // Indigo
            'rgba(168, 85, 247, 0.7)',  // Purple
            'rgba(236, 72, 153, 0.7)',  // Pink
            'rgba(16, 185, 129, 0.7)',  // Emerald
            'rgba(245, 158, 11, 0.7)',  // Amber
            'rgba(59, 130, 246, 0.7)',  // Blue
            'rgba(239, 68, 68, 0.7)',   // Red
        ];

        foreach ($organizersForChart as $index => $org) {
            $orgMonthlyRevenue = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('events', 'order_items.event_id', '=', 'events.id')
                ->where('orders.status', 'paid')
                ->where('events.user_id', $org->id)
                ->when($driver === 'mysql', fn($q) => $q->selectRaw('DATE_FORMAT(orders.created_at, "%Y-%m") as month, SUM(order_items.subtotal) as total'))
                ->when($driver === 'sqlite', fn($q) => $q->selectRaw("strftime('%Y-%m', orders.created_at) as month, SUM(order_items.subtotal) as total"))
                ->groupBy('month')
                ->get()
                ->pluck('total', 'month')
                ->toArray();

            $data = [];
            $hasData = false;
            foreach ($monthKeys as $mKey) {
                $val = (float)($orgMonthlyRevenue[$mKey] ?? 0);
                $data[] = $val;
                if ($val > 0) $hasData = true;
            }

            if ($hasData) {
                $chartDatasets[] = [
                    'label' => $org->name,
                    'data' => $data,
                    'backgroundColor' => $colors[$index % count($colors)],
                    'borderColor' => str_replace('0.7', '1', $colors[$index % count($colors)]),
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ];
            }
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentEvents' => $recentEvents,
            'organizerEarnings' => $organizerEarnings,
            'revenueByMonthLabels' => $last12Labels,
            'revenueByMonthData' => $last12,
            'chartDatasets' => $chartDatasets,
            'eventsByStatus' => $eventsByStatus,
        ]);
    }
}
