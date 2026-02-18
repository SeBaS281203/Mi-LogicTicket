<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas - {{ $order->order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; }
        .ticket-page { page-break-after: always; padding: 20px; border: 2px solid #6366f1; margin: 10px 0; }
        .ticket-page:last-child { page-break-after: auto; }
        h1 { font-size: 18px; color: #4338ca; margin-bottom: 4px; }
        .meta { color: #64748b; margin-bottom: 12px; }
        .qr-wrap { text-align: center; margin: 16px 0; }
        .qr-wrap img { max-width: 180px; height: auto; }
        .code { font-size: 14px; font-weight: bold; letter-spacing: 1px; word-break: break-all; margin-top: 8px; }
        .footer { margin-top: 20px; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    @foreach($ticketsData as $data)
        <div class="ticket-page">
            <h1>{{ $data['event_title'] }}</h1>
            <p class="meta">{{ $data['ticket_type_name'] }} · Orden {{ $data['order_number'] }}</p>
            <p class="meta">Asistente: {{ $data['customer_name'] }}</p>
            <div class="qr-wrap">
                <img src="{{ $data['qr_data_uri'] }}" alt="QR {{ $data['ticket']->code }}">
                <p class="code">{{ $data['ticket']->code }}</p>
            </div>
            <p class="footer">LogicTicket · Presenta este código o el QR en la entrada. Válido una sola vez.</p>
        </div>
    @endforeach
</body>
</html>
