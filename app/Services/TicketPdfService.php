<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class TicketPdfService
{
    /**
     * Genera un PDF con una pagina por ticket (codigo QR unico por entrada).
     * Los tickets se crean al confirmar el pago; este metodo asume que la orden ya tiene tickets.
     */
    public function generateOrderTicketsPdf(Order $order): string
    {
        $ticketsData = $this->buildTicketsData($order);

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
     * Usa SvgWriter para evitar dependencia de la extension GD.
     */
    public function qrDataUri(string $url): string
    {
        $qrCode = new QrCode($url);
        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }

    /**
     * Genera el PDF y devuelve el contenido para adjuntar al email.
     */
    public function generateOrderTicketsPdfContent(Order $order): string
    {
        $ticketsData = $this->buildTicketsData($order);

        $pdf = Pdf::loadHTML(view('pdf.tickets', [
            'order' => $order,
            'ticketsData' => $ticketsData,
        ])->render());
        $pdf->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    /**
     * Prepara todos los datos necesarios para render de tickets.
     */
    private function buildTicketsData(Order $order): array
    {
        $order->load(['items.tickets', 'items.event.user']);
        $supportPhone = '+51948745909';

        $ticketsCount = max(1, (int) $order->items->sum(fn ($item) => $item->tickets->count()));
        $serviceFeePerTicket = round(((float) $order->commission_amount) / $ticketsCount, 2);

        $transactionReference = preg_replace('/\D+/', '', (string) $order->order_number);
        if (!$transactionReference) {
            $transactionReference = (string) $order->id;
        }

        $ticketsData = [];
        foreach ($order->items as $item) {
            $organizer = $item->event?->user;
            $eventImage = $item->event?->event_image ?? $item->event?->image;

            foreach ($item->tickets as $ticket) {
                $ticketsData[] = [
                    'ticket' => $ticket,
                    'event_title' => $item->event_title,
                    'event_date' => $item->event?->start_date,
                    'event_location' => trim(($item->event?->venue_name ?? '') . ' - ' . ($item->event?->city ?? ''), ' -'),
                    'ticket_type_name' => $item->ticket_type_name,
                    'price' => (float) $item->unit_price,
                    'service_fee' => $serviceFeePerTicket,
                    'order_number' => $order->order_number,
                    'transaction_reference' => $transactionReference,
                    'customer_name' => $order->customer_name,
                    'organizer_name' => $organizer?->name,
                    'organizer_ruc' => $organizer?->ruc,
                    'ticket_number' => str_pad((string) $ticket->id, 5, '0', STR_PAD_LEFT),
                    'qr_data_uri' => $this->qrDataUri('tel:' . $supportPhone),
                    'barcode_data_uri' => $this->barcodeDataUri((string) $ticket->code),
                    'event_banner_data_uri' => $this->imageToDataUri($eventImage),
                ];
            }
        }

        return $ticketsData;
    }

    /**
     * Convierte imagen local/remota a data URI para compatibilidad total con DomPDF.
     */
    private function imageToDataUri(?string $source): ?string
    {
        if (!$source) {
            return null;
        }

        if (Str::startsWith($source, 'data:image/')) {
            return $source;
        }

        $binary = null;

        if (Str::startsWith($source, ['http://', 'https://'])) {
            try {
                $context = stream_context_create([
                    'http' => ['timeout' => 6],
                    'https' => ['timeout' => 6],
                ]);
                $remote = @file_get_contents($source, false, $context);
                if ($remote !== false) {
                    $binary = $remote;
                }
            } catch (Throwable) {
                // fallback to local candidates
            }
        }

        if ($binary === null) {
            $normalized = ltrim($source, '/');
            if (Str::startsWith($normalized, 'storage/')) {
                $normalized = Str::after($normalized, 'storage/');
            }

            $candidates = [];
            if (Storage::disk('public')->exists($normalized)) {
                $candidates[] = Storage::disk('public')->path($normalized);
            }
            $candidates[] = public_path($normalized);
            $candidates[] = storage_path('app/public/' . $normalized);
            $candidates[] = storage_path('app/' . $normalized);

            foreach ($candidates as $candidate) {
                if ($candidate && is_file($candidate)) {
                    $local = @file_get_contents($candidate);
                    if ($local !== false) {
                        $binary = $local;
                        break;
                    }
                }
            }
        }

        if ($binary === null) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($binary) ?: 'image/jpeg';

        if (!Str::startsWith((string) $mime, 'image/')) {
            $mime = 'image/jpeg';
        }

        return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }

    /**
     * Genera un codigo de barras visual (el QR sigue siendo la validacion oficial).
     */
    private function barcodeDataUri(string $value): string
    {
        $clean = strtoupper(preg_replace('/[^A-Z0-9]/', '', $value) ?? '');
        if ($clean === '') {
            $clean = 'TICKET';
        }

        // Patrón de barras compacto para que no se convierta en bloque negro al escalar.
        $svgWidth = 58;
        $svgHeight = 220;
        $x = 4;
        $maxX = 53;
        $seed = abs((int) crc32($clean));
        $rects = [
            '<rect x="2" y="8" width="2" height="178" fill="#111111" />',
        ];

        while ($x < $maxX) {
            $seed = (1103515245 * $seed + 12345) & 0x7fffffff;
            $barWidth = 1 + ($seed % 2); // 1..2
            $gapWidth = 1 + (($seed >> 3) % 2); // 1..2

            if (($x + $barWidth) > $maxX) {
                break;
            }

            $rects[] = '<rect x="' . $x . '" y="8" width="' . $barWidth . '" height="178" fill="#111111" />';
            $x += $barWidth + $gapWidth;
        }

        $rects[] = '<rect x="54" y="8" width="2" height="178" fill="#111111" />';

        $barcodeText = htmlspecialchars($clean, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $svgWidth . '" height="' . $svgHeight . '" viewBox="0 0 ' . $svgWidth . ' ' . $svgHeight . '">'
            . '<rect x="0" y="0" width="' . $svgWidth . '" height="' . $svgHeight . '" fill="#ffffff"/>'
            . implode('', $rects)
            . '<text x="12" y="212" font-size="8" font-family="Arial, sans-serif" fill="#111111" transform="rotate(-90 12 212)">' . $barcodeText . '</text>'
            . '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
