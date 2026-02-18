<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class TicketPdfService
{
    /**
     * Genera un PDF con una página por ticket (código QR único por entrada).
     * Los tickets se crean al confirmar el pago; este método asume que la orden ya tiene tickets.
     */
    public function generateOrderTicketsPdf(Order $order): string
    {
        $order->load(['items.tickets', 'items.event']);

        $ticketsData = [];
        foreach ($order->items as $item) {
            foreach ($item->tickets as $ticket) {
                $ticketsData[] = [
                    'ticket' => $ticket,
                    'event_title' => $item->event_title,
                    'ticket_type_name' => $item->ticket_type_name,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'qr_data_uri' => $this->qrDataUri($ticket->code),
                ];
            }
        }

        $html = view('pdf.tickets', [
            'order' => $order,
            'ticketsData' => $ticketsData,
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');

        $path = 'tickets/order-' . $order->id . '-' . now()->format('Y-m-d-His') . '.pdf';
        $fullPath = Storage::disk('local')->path($path);
        Storage::disk('local')->makeDirectory(dirname($path));
        $pdf->save($fullPath);

        return $fullPath;
    }

    /**
     * Genera data URI del QR para incrustar en HTML.
     */
    public function qrDataUri(string $code): string
    {
        $qrCode = new QrCode($code);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }

    /**
     * Genera el PDF y devuelve el contenido para adjuntar al email (sin guardar en disco si se desea).
     */
    public function generateOrderTicketsPdfContent(Order $order): string
    {
        $order->load(['items.tickets', 'items.event']);

        $ticketsData = [];
        foreach ($order->items as $item) {
            foreach ($item->tickets as $ticket) {
                $ticketsData[] = [
                    'ticket' => $ticket,
                    'event_title' => $item->event_title,
                    'ticket_type_name' => $item->ticket_type_name,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'qr_data_uri' => $this->qrDataUri($ticket->code),
                ];
            }
        }

        $pdf = Pdf::loadHTML(view('pdf.tickets', [
            'order' => $order,
            'ticketsData' => $ticketsData,
        ])->render());
        $pdf->setPaper('a4', 'portrait');

        return $pdf->output();
    }
}
