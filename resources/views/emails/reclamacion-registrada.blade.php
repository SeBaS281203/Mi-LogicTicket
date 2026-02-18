<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de registro</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #334155; line-height: 1.5; }
        .container { max-width: 600px; margin: 0 auto; padding: 24px; }
        .codigo { font-size: 18px; font-weight: bold; color: #0f766e; margin: 16px 0; }
        .box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin: 16px 0; }
        .footer { font-size: 12px; color: #64748b; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <p>Estimado/a <strong>{{ $reclamo->nombre_completo }}</strong>,</p>
        <p>Hemos registrado su {{ $reclamo->tipo_reclamo === 'reclamo' ? 'reclamo' : 'queja' }} en nuestro Libro de Reclamaciones Virtual, conforme a la normativa de INDECOPI.</p>
        <div class="box">
            <p><strong>Código de registro:</strong></p>
            <p class="codigo">{{ $reclamo->codigo_reclamo }}</p>
            <p>Fecha: {{ $reclamo->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <p>Adjunto encontrará la constancia en PDF. Le atenderemos a la brevedad posible.</p>
        <p class="footer">LogicTicket · Libro de Reclamaciones Virtual</p>
    </div>
</body>
</html>
