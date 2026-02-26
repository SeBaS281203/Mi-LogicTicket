<?php

namespace App\Http\Controllers\Cuenta;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\TicketPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CuentaTicketController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $orders = Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
            })
            ->with(['items.tickets', 'items.event'])
            ->latest()
            ->get();

        $stats = [
            'total_tickets' => $orders->where('status', 'paid')->flatMap->items->sum('quantity'),
            'upcoming_events' => $orders->where('status', 'paid')->flatMap->items->filter(fn($i) => $i->event && $i->event->start_date > now())->groupBy('event_id')->count(),
            'total_invested' => $orders->where('status', 'paid')->sum('total'),
        ];

        return view('cuenta.tickets.index', compact('orders', 'stats'));
    }

    public function getQr(string $code, TicketPdfService $pdfService): Response
    {
        // Verificar si el ticket pertenece al usuario (opcional, para mayor seguridad)
        $qrDataUri = $pdfService->qrDataUri(config('app.url') . '/ticket/verify/' . $code);
        
        // El QR generado por el servicio es un Data URI (image/svg+xml;base64,... o similar)
        // Lo extraemos para servirlo como imagen pura
        if (str_contains($qrDataUri, 'base64,')) {
            $data = explode('base64,', $qrDataUri);
            $type = explode(':', explode(';', $data[0])[0])[1];
            return response(base64_decode($data[1]))->header('Content-Type', $type);
        }

        abort(404);
    }

    public function downloadPdf(Request $request, Order $order, TicketPdfService $pdfService): Response|StreamedResponse
    {
        $user = $request->user();

        $ownsOrder = $order->user_id === $user->id || $order->customer_email === $user->email;
        if (!$ownsOrder || $order->status !== 'paid') {
            abort(403, 'No tienes permiso para descargar estos tickets.');
        }

        $order->load(['items.tickets', 'items.event.user']);
        if ($order->items->flatMap->tickets->isEmpty()) {
            abort(404, 'No hay tickets para esta orden.');
        }

        $pdfContent = $pdfService->generateOrderTicketsPdfContent($order);
        $filename = 'tickets-' . $order->order_number . '.pdf';

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
