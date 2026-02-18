<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('admin.reports.index');
    }

    public function exportOrdersExcel(Request $request): StreamedResponse
    {
        $from = $request->date_from ?: now()->subYear()->format('Y-m-d');
        $to = $request->date_to ?: now()->format('Y-m-d');
        $orders = Order::with('items')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        $filename = 'ordenes-' . $from . '_' . $to . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return Response::stream(function () use ($orders) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($out, ['Orden', 'Fecha', 'Email', 'Nombre', 'Subtotal', 'Comisión', 'Total', 'Estado', 'Método pago']);
            foreach ($orders as $o) {
                fputcsv($out, [
                    $o->order_number,
                    $o->created_at->format('Y-m-d H:i'),
                    $o->customer_email,
                    $o->customer_name,
                    $o->subtotal,
                    $o->commission_amount ?? 0,
                    $o->total,
                    $o->status,
                    $o->payment_method ?? '-',
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    public function exportOrdersPdf(Request $request)
    {
        $from = $request->date_from ?: now()->subMonth()->format('Y-m-d');
        $to = $request->date_to ?: now()->format('Y-m-d');
        $orders = Order::where('status', 'paid')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->with('items')
            ->orderBy('created_at')
            ->get();
        $total = $orders->sum('total');
        $pdf = Pdf::loadView('admin.reports.orders-pdf', compact('orders', 'total', 'from', 'to'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('reporte-ordenes-' . $from . '-' . $to . '.pdf');
    }

    public function exportEventsExcel(Request $request): StreamedResponse
    {
        $query = Event::with(['user', 'category']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $events = $query->orderBy('created_at')->get();

        $filename = 'eventos-' . ($request->status ?: 'todos') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return Response::stream(function () use ($events) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['ID', 'Título', 'Organizador', 'Categoría', 'Ciudad', 'Fecha inicio', 'Estado', 'Creado']);
            foreach ($events as $e) {
                fputcsv($out, [
                    $e->id,
                    $e->title,
                    $e->user?->name ?? $e->user?->email,
                    $e->category?->name,
                    $e->city,
                    $e->start_date?->format('Y-m-d H:i'),
                    $e->status,
                    $e->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }
}
