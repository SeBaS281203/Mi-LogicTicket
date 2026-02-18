<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia {{ $reclamo->codigo_reclamo }} - Libro de Reclamaciones</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; padding: 24px; }
        .header-logo { background: #00a650; color: #fff; padding: 14px 20px; margin: -24px -24px 20px -24px; }
        .header-logo .logo-box { display: inline-block; width: 36px; height: 36px; background: #fff; color: #00a650; text-align: center; line-height: 36px; font-weight: bold; font-size: 18px; margin-right: 10px; vertical-align: middle; }
        .header-logo .title { font-size: 15px; font-weight: bold; }
        .header-logo .sub { font-size: 9px; opacity: 0.9; }
        h1 { font-size: 16px; color: #0f766e; margin-bottom: 8px; }
        .codigo { font-size: 14px; font-weight: bold; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #f1f5f9; font-size: 10px; }
        .qr-wrap { text-align: center; margin: 16px 0; }
        .qr-wrap img { max-width: 120px; height: auto; }
        .footer { margin-top: 20px; font-size: 9px; color: #64748b; }
    </style>
</head>
<body>
    <div class="header-logo">
        <span class="logo-box">L</span><span class="title">LogicTicket</span> <span style="color: rgba(255,255,255,0.8);">·</span> <span class="sub">Libro de Reclamaciones Virtual · INDECOPI (Perú)</span>
    </div>
    <h1>Constancia de registro - Libro de Reclamaciones Virtual</h1>
    <p class="codigo">Código: {{ $reclamo->codigo_reclamo }}</p>
    <p>Fecha de registro: {{ $reclamo->created_at->format('d/m/Y H:i') }}</p>

    <table>
        <tr><th>Tipo documento</th><td>{{ $reclamo->tipo_documento }}</td></tr>
        <tr><th>Nº documento</th><td>{{ $reclamo->numero_documento }}</td></tr>
        <tr><th>Nombre completo</th><td>{{ $reclamo->nombre_completo }}</td></tr>
        <tr><th>Dirección</th><td>{{ $reclamo->direccion }}</td></tr>
        <tr><th>Teléfono</th><td>{{ $reclamo->telefono }}</td></tr>
        <tr><th>Correo electrónico</th><td>{{ $reclamo->email }}</td></tr>
        <tr><th>Tipo</th><td>{{ $reclamo->tipo_reclamo === 'reclamo' ? 'Reclamo' : 'Queja' }}</td></tr>
        @if($reclamo->evento)
        <tr><th>Servicio/Evento relacionado</th><td>{{ $reclamo->evento->title }} ({{ $reclamo->evento->start_date->format('d/m/Y') }})</td></tr>
        @endif
        <tr><th>Descripción</th><td>{{ $reclamo->descripcion }}</td></tr>
        @if($reclamo->pedido_consumidor)
        <tr><th>Pedido del consumidor</th><td>{{ $reclamo->pedido_consumidor }}</td></tr>
        @endif
    </table>

    <div class="qr-wrap">
        <img src="{{ $qr_data_uri }}" alt="QR {{ $reclamo->codigo_reclamo }}">
        <p>{{ $reclamo->codigo_reclamo }}</p>
    </div>

    <p class="footer">LogicTicket - Libro de Reclamaciones Virtual conforme a la normativa de INDECOPI (Perú). Conserve esta constancia.</p>
</body>
</html>
