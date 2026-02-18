<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmación de compra - {{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #334155; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 24px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-weight: 600; }
        .total { font-size: 1.25rem; font-weight: bold; color: #4f46e5; margin-top: 16px; }
        .footer { margin-top: 24px; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0;">LogicTicket</h1>
        <p style="margin:8px 0 0 0;">Confirmación de compra</p>
    </div>
    <div class="content">
        <p>Hola {{ $order->customer_name }},</p>
        <p>Gracias por tu compra. Tu orden <strong>{{ $order->order_number }}</strong> ha sido registrada correctamente.</p>

        <table>
            <thead>
                <tr>
                    <th>Evento / Entrada</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->event_title }} - {{ $item->ticket_type_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>S/ {{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total: S/ {{ number_format($order->total, 2) }}</p>

        <p>En este correo encontrarás adjunto el PDF con tus entradas y códigos QR. Presenta el código o el QR en la entrada del evento.</p>
        <p>Guarda este correo como comprobante de tu compra.</p>

        <div class="footer">
            <p>LogicTicket - Venta de entradas para eventos</p>
        </div>
    </div>
</body>
</html>
