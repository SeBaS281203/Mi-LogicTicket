<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrganizerSalesController extends Controller
{
    public function index(Request $request): View
    {
        $eventIds = Auth::user()->events()->pluck('id');

        $query = OrderItem::whereIn('event_id', $eventIds)
            ->with(['order', 'event', 'ticketType'])
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'));

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        if ($request->filled('date_from')) {
            $query->whereHas('order', fn ($q) => $q->where('created_at', '>=', $request->date_from . ' 00:00:00'));
        }
        if ($request->filled('date_to')) {
            $query->whereHas('order', fn ($q) => $q->where('created_at', '<=', $request->date_to . ' 23:59:59'));
        }

        $sales = $query->latest()->paginate(20)->withQueryString();
        $events = Auth::user()->events()->orderBy('title')->get();

        return view('organizer.sales.index', compact('sales', 'events'));
    }

    public function buyers(Request $request): View
    {
        $eventIds = Auth::user()->events()->pluck('id');

        $orders = Order::where('status', 'paid')
            ->whereHas('items', fn ($q) => $q->whereIn('event_id', $eventIds))
            ->with(['items' => fn ($q) => $q->whereIn('event_id', $eventIds)->with('event')]);

        if ($request->filled('event_id')) {
            $orders->whereHas('items', fn ($q) => $q->where('event_id', $request->event_id));
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $orders->where(function ($oq) use ($q) {
                $oq->where('customer_email', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('order_number', 'like', "%{$q}%");
            });
        }

        $buyers = $orders->latest()->paginate(20)->withQueryString();
        $events = Auth::user()->events()->orderBy('title')->get();

        return view('organizer.buyers.index', compact('buyers', 'events'));
    }

    public function report(): View
    {
        $eventIds = Auth::user()->events()->pluck('id');
        $totalRevenue = OrderItem::whereIn('event_id', $eventIds)
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->sum('subtotal');
        $totalTickets = OrderItem::whereIn('event_id', $eventIds)
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->sum('quantity');

        $byEvent = OrderItem::whereIn('event_id', $eventIds)
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->selectRaw('event_id, SUM(subtotal) as revenue, SUM(quantity) as tickets')
            ->groupBy('event_id')
            ->with('event')
            ->get();

        return view('organizer.reports.index', compact('totalRevenue', 'totalTickets', 'byEvent'));
    }

    public function exportReport(Request $request): StreamedResponse
    {
        $eventIds = Auth::user()->events()->pluck('id');
        $from = $request->date_from ?: now()->subMonth()->format('Y-m-d');
        $to = $request->date_to ?: now()->format('Y-m-d');

        $items = OrderItem::whereIn('event_id', $eventIds)
            ->with(['order', 'event', 'ticketType'])
            ->whereHas('order', fn ($q) => $q->where('status', 'paid')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']))
            ->orderBy('created_at')
            ->get();

        $filename = 'ingresos-' . $from . '_' . $to . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return Response::stream(function () use ($items) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Fecha', 'Orden', 'Evento', 'Tipo entrada', 'Cantidad', 'Precio unit.', 'Subtotal', 'Cliente', 'Email']);
            foreach ($items as $i) {
                fputcsv($out, [
                    $i->order?->created_at?->format('Y-m-d H:i'),
                    $i->order?->order_number,
                    $i->event_title,
                    $i->ticket_type_name,
                    $i->quantity,
                    $i->unit_price,
                    $i->subtotal,
                    $i->order?->customer_name,
                    $i->order?->customer_email,
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    public function exportReportPdf(Request $request)
    {
        $eventIds = Auth::user()->events()->pluck('id');
        $from = $request->date_from ?: now()->subMonth()->format('Y-m-d');
        $to = $request->date_to ?: now()->format('Y-m-d');

        $items = OrderItem::whereIn('event_id', $eventIds)
            ->with(['order', 'event', 'ticketType'])
            ->whereHas('order', fn ($q) => $q->where('status', 'paid')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']))
            ->orderBy('created_at')
            ->get();

        $total = $items->sum('subtotal');
        $pdf = Pdf::loadView('organizer.reports.pdf', compact('items', 'total', 'from', 'to'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('reporte-ingresos-' . $from . '-' . $to . '.pdf');
    }
}
