<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libro de Reclamaciones - Exportación</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 4px 6px; }
        th { background: #f1f5f9; }
        h1 { font-size: 14px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <h1>Libro de Reclamaciones - LogicTicket</h1>
    <p>Exportado: {{ now()->format('d/m/Y H:i') }} · Total: {{ $reclamos->count() }} registros</p>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Descripción (resumen)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reclamos as $r)
                <tr>
                    <td>{{ $r->codigo_reclamo }}</td>
                    <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $r->nombre_completo }}</td>
                    <td>{{ $r->email }}</td>
                    <td>{{ ucfirst($r->tipo_reclamo) }}</td>
                    <td>{{ ucfirst($r->estado) }}</td>
                    <td>{{ Str::limit($r->descripcion, 80) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
