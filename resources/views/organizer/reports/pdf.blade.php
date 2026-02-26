<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de ingresos {{ $from }} - {{ $to }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 5px 6px; text-align: left; }
        th { background: #f1f5f9; font-weight: 600; }
        .total { font-weight: bold; margin-top: 16px; font-size: 11px; }
        h1 { font-size: 14px; }
    </style>
</head>
<body>
    <h1>Reporte de ingresos - Mis eventos</h1>
    <p>Período: {{ $from }} a {{ $to }}</p>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Orden</th>
                <th>Evento</th>
                <th>Tipo entrada</th>
                <th>Cant.</th>
                <th>Subtotal</th>
                <th>Cliente</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i)
                <tr>
                    <td>{{ $i->order?->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $i->order?->order_number }}</td>
                    <td>{{ $i->event_title }}</td>
                    <td>{{ $i->ticket_type_name }}</td>
                    <td>{{ $i->quantity }}</td>
                    <td>S/ {{ number_format($i->subtotal, 2) }}</td>
                    <td>{{ $i->order?->customer_email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total ingresos: S/ {{ number_format($total, 2) }}</p>
</body>
</html>
