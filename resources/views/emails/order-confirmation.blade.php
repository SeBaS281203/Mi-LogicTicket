<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmación de compra - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #0f172a;
            background: #f1f5f9;
            margin: 0;
            padding: 24px 12px;
        }
        .shell {
            max-width: 640px;
            margin: 0 auto;
        }
        .header {
            background: #7c3aed;
            color: #ecfdf5;
            padding: 20px 24px;
            border-radius: 16px 16px 0 0;
            text-align: left;
        }
        .header-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .header-logo {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: #ecfdf5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #00a650;
            font-size: 18px;
        }
        .header-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }
        .header-subtitle {
            margin: 4px 0 0 42px;
            font-size: 13px;
            opacity: .9;
        }
        .content {
            background: #ffffff;
            padding: 24px;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.07);
            border: 1px solid #e2e8f0;
            border-top: none;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            background: #ecfdf5;
            color: #15803d;
            margin-bottom: 6px;
        }
        .order-number {
            font-weight: 600;
            color: #0f172a;
        }
        .event-card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f9fafb;
            padding: 14px 16px;
            margin-top: 16px;
        }
        .event-title {
            font-weight: 600;
            margin: 0 0 4px 0;
            color: #0f172a;
        }
        .event-meta {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0 4px 0;
            font-size: 13px;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #4b5563;
        }
        .total-row td {
            border-bottom: none;
            padding-top: 12px;
        }
        .total-label {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }
        .total-amount {
            font-size: 18px;
            font-weight: 800;
            color: #00a650;
            text-align: right;
        }
        .summary {
            font-size: 13px;
            color: #4b5563;
            margin-top: 16px;
        }
        .summary strong {
            color: #111827;
        }
        .footer {
            margin-top: 18px;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="header">
            <div class="header-brand">
                <div class="header-logo">C</div>
                <div>
                    <p class="header-title">ChiclayoTicket</p>
                    <p class="header-subtitle">Confirmación de compra</p>
                </div>
            </div>
        </div>
        <div class="content">
            <span class="badge">Orden confirmada</span>
            <p style="margin: 0 0 8px 0;">Hola {{ $order->customer_name }},</p>
            <p style="margin: 0 0 12px 0;">
                Gracias por tu compra. Tu orden
                <span class="order-number">#{{ $order->order_number }}</span>
                se ha registrado correctamente.
            </p>

            @php
                $firstItem = $order->items->first();
            @endphp
            @if($firstItem)
                <div class="event-card">
                    <p class="event-title">{{ $firstItem->event_title }}</p>
                    @if($firstItem->event?->start_date)
                        <p class="event-meta">
                            {{ $firstItem->event->start_date->translatedFormat('d \\d\\e F Y') }}
                            · {{ $firstItem->event->start_date->format('H:i') }}
                        </p>
                    @endif
                    @if($firstItem->event?->venue_name || $firstItem->event?->city)
                        <p class="event-meta">
                            {{ $firstItem->event?->venue_name }}{{ $firstItem->event?->city ? ', '.$firstItem->event->city : '' }}
                        </p>
                    @endif
                </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>Entrada</th>
                        <th>Cant.</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->ticket_type_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td style="text-align:right;">S/ {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                    <tr class="total-row">
                        <td colspan="2" class="total-label">Total pagado</td>
                        <td class="total-amount">S/ {{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="summary">
                <p style="margin: 0 0 6px 0;">
                    <strong>Comprador:</strong> {{ $order->customer_name }} &lt;{{ $order->customer_email }}&gt;
                </p>
                <p style="margin: 0 0 10px 0;">
                    En este correo se adjunta el PDF con tus entradas y códigos QR.
                    Muéstralos en tu móvil o impresos al ingresar al evento.
                </p>
                <p style="margin: 0;">
                    Te recomendamos conservar este mensaje como comprobante de tu compra.
                </p>
            </div>

            <div class="footer">
                ChiclayoTicket · Venta de entradas para eventos en vivo
            </div>
        </div>
    </div>
</body>
</html>
