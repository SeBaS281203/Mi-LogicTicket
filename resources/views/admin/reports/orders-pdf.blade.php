<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de órdenes {{ $from }} - {{ $to }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f1f5f9; font-weight: 600; }
        .total { font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>
    <h1>Reporte de órdenes (pagadas)</h1>
    <p>Período: {{ $from }} a {{ $to }}</p>
    <table>
        <thead>
            <tr>
                <th>Orden</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->customer_email }}</td>
                    <td>S/ {{ number_format($order->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total ingresos: S/ {{ number_format($total, 2) }}</p>
</body>
</html>
