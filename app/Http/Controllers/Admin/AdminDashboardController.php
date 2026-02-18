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
            'users' => User::count(),
            'organizers' => User::where('role', 'organizer')->count(),
            'events' => Event::count(),
            'events_published' => Event::where('status', 'published')->count(),
            'events_pending' => Event::where('status', 'pending_approval')->count(),
            'events_active' => Event::where('status', 'published')->where('start_date', '>=', now())->count(),
            'orders' => Order::where('status', 'paid')->count(),
            'revenue' => Order::where('status', 'paid')->sum('total'),
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
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i)->format('Y-m');
            $last12[$m] = (float) ($revenueByMonth[$m] ?? 0);
        }

        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'revenueByMonthLabels' => array_keys($last12),
            'revenueByMonthData' => array_values($last12),
        ]);
    }
}
