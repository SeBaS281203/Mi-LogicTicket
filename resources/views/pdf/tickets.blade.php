<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket - {{ $order->order_number }}</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            color: #161616;
            background: #ffffff;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .ticket-page {
            page-break-after: always;
            padding: 12px 16px 0;
        }

        .ticket-page:last-child {
            page-break-after: auto;
        }

        .banner-wrap {
            width: 420px;
            height: 78px;
            margin: 0 auto 10px;
            border: 1px solid #d9d9d9;
            background: #141924;
            overflow: hidden;
        }

        .brand-banner {
            width: 100%;
            height: 100%;
            color: #ffffff;
            text-align: center;
            line-height: 78px;
            font-weight: 800;
            letter-spacing: 1.2px;
            font-size: 24px;
            background: #1f2b3d;
        }

        .ticket-shell {
            border: 1px solid #dcdcdc;
            background: #f5f5f5;
            position: relative;
        }

        .ticket-main {
            padding: 14px 14px 10px;
            position: relative;
            overflow: hidden;
        }

        .wm-circle {
            position: absolute;
            width: 390px;
            height: 390px;
            border: 2px solid #ebebeb;
            border-radius: 50%;
            left: 50%;
            top: 54%;
            margin-left: -195px;
            margin-top: -195px;
        }

        .wm-grid {
            position: absolute;
            left: -30px;
            right: -30px;
            top: -10px;
            bottom: -10px;
            opacity: 0.45;
            background-size: 78px 78px;
            background-image:
                linear-gradient(45deg, #ececec 14%, transparent 14%, transparent 50%, #ececec 50%, #ececec 64%, transparent 64%, transparent),
                linear-gradient(-45deg, #efefef 14%, transparent 14%, transparent 50%, #efefef 50%, #efefef 64%, transparent 64%, transparent);
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            position: relative;
            z-index: 2;
        }

        .barcode-col {
            width: 80px;
            vertical-align: top;
            padding-right: 8px;
        }

        .barcode-box {
            width: 62px;
            margin: 6px auto 0;
            border-right: 1px solid #c8c8c8;
            padding-right: 7px;
        }

        .barcode-box img {
            display: block;
            width: 52px;
            height: 206px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #d7d7d7;
        }

        .detail-col {
            vertical-align: top;
            padding: 6px 12px 0 2px;
        }

        .qr-col {
            width: 172px;
            vertical-align: top;
            text-align: center;
            padding-top: 4px;
        }

        .trans {
            margin-bottom: 14px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .trans .lbl {
            font-size: 15px;
            font-weight: 700;
            margin-right: 6px;
        }

        .trans .val {
            font-size: 15px;
            font-weight: 700;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            line-height: 1.2;
        }

        .info-table td {
            padding: 0 0 10px;
            vertical-align: top;
        }

        .label {
            width: 64px;
            color: #2d2d2d;
            font-weight: 700;
        }

        .value {
            color: #161616;
            font-weight: 700;
        }

        .split-row {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .split-row td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .right-prices {
            text-align: right;
            font-size: 11px;
            line-height: 1.45;
            padding-top: 2px;
            white-space: nowrap;
        }

        .right-prices .price-line {
            margin-bottom: 4px;
            font-weight: 700;
        }

        .right-prices .light {
            font-weight: 600;
        }

        .qr-ticket-no {
            text-align: right;
            font-size: 12px;
            font-weight: 700;
            margin: 0 2px 6px 0;
        }

        .qr-box {
            width: 146px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #d6d6d6;
            padding: 8px;
        }

        .qr-box img {
            width: 100%;
            display: block;
        }

        .ticket-code {
            margin-top: 5px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-align: center;
        }

        .print-badge {
            text-align: right;
            margin-top: 7px;
            font-size: 10px;
            color: #303030;
            font-weight: 700;
        }

        .important-box {
            width: 430px;
            margin: 14px auto 16px;
            border: 2px dashed #888888;
            background: #fafafa;
            padding: 8px 10px;
            position: relative;
            z-index: 2;
        }

        .important-title {
            text-align: center;
            color: #d81f1f;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .important-table {
            width: 100%;
            border-collapse: collapse;
        }

        .important-table td {
            width: 50%;
            vertical-align: middle;
            text-align: center;
            padding: 2px 4px;
            border-right: 1px solid #777777;
        }

        .important-table td:last-child {
            border-right: none;
        }

        .imp-label {
            font-size: 11px;
            font-weight: 700;
            color: #303030;
            line-height: 1.25;
        }

        .terms {
            background: #15191f;
            color: #f5f5f5;
            padding: 16px 18px 20px;
            border-top: 1px solid #0f1216;
        }

        .terms h4 {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .terms ol {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .terms li {
            margin-bottom: 5px;
            font-size: 10px;
            line-height: 1.3;
            color: #f2f2f2;
        }

        .terms li .n {
            color: #d92a2a;
            font-weight: 800;
            margin-right: 6px;
        }
    </style>
</head>
<body>
@foreach($ticketsData as $data)
    @php
        $date = $data['event_date'];
        $displayDate = $date ? $date->translatedFormat('l, d \\d\\e F \\d\\e Y') : 'Por definir';
        $displayHour = $date ? $date->format('H:i') : '00:00';
    @endphp

    <div class="ticket-page">
        <div class="banner-wrap">
            <div class="brand-banner">CHICLAYOTICKET</div>
        </div>

        <div class="ticket-shell">
            <div class="ticket-main">
                <div class="wm-grid"></div>
                <div class="wm-circle"></div>

                <table class="main-table">
                    <tr>
                        <td class="barcode-col">
                            <div class="barcode-box">
                                <img src="{{ $data['barcode_data_uri'] }}" alt="Barcode">
                            </div>
                        </td>

                        <td class="detail-col">
                            <div class="trans">
                                <span class="lbl">Transaccion:</span>
                                <span class="val">{{ $data['transaction_reference'] }}</span>
                            </div>

                            <table class="info-table">
                                <tr>
                                    <td class="label">Evento:</td>
                                    <td class="value">{{ $data['event_title'] }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Lugar:</td>
                                    <td class="value">{{ $data['event_location'] ?: 'Por definir' }}</td>
                                </tr>
                            </table>

                            <table class="split-row">
                                <tr>
                                    <td>
                                        <table class="info-table">
                                            <tr>
                                                <td class="label">Fecha:</td>
                                                <td class="value">
                                                    {{ $displayDate }}<br>
                                                    {{ $displayHour }} hrs
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">Sector:</td>
                                                <td class="value">{{ $data['ticket_type_name'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <div class="right-prices">
                                            <div class="price-line"><span class="light">Precio:</span> S/{{ number_format($data['price'], 2) }}</div>
                                            <div class="price-line"><span class="light">Cargo por Servicio:</span> S/{{ number_format($data['service_fee'], 2) }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div class="print-badge">BOLETO DIGITAL CHICLAYOTICKET</div>
                        </td>

                        <td class="qr-col">
                            <div class="qr-ticket-no">N&deg; {{ $data['ticket_number'] }}</div>
                            <div class="qr-box">
                                <img src="{{ $data['qr_data_uri'] }}" alt="QR Ticket">
                            </div>
                            <div class="ticket-code">{{ $data['ticket']->code }}</div>
                        </td>
                    </tr>
                </table>

                <div class="important-box">
                    <div class="important-title">IMPORTANTE:</div>
                    <table class="important-table">
                        <tr>
                            <td>
                                <div class="imp-label">Llega temprano<br>al lugar</div>
                            </td>
                            <td>
                                <div class="imp-label">Lleva tu ticket<br>impreso</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="terms">
                <h4>Valido para una persona y solo para el evento y fecha indicada - ChiclayoTicket</h4>
                <ol>
                    <li><span class="n">1.</span>El ticket es una entrada valida, por lo que no sera canjeado por una entrada tradicional en boleteria.</li>
                    <li><span class="n">2.</span>Con el ticket puedes acercarte directamente al evento presentandolo de manera impresa.</li>
                    <li><span class="n">3.</span>Al elegir ticket, estas aceptando no divulgarlo ni compartirlo con terceros, ya que esto podria afectar tu ingreso al evento.</li>
                    <li><span class="n">4.</span>El ticket tendra un sistema de control y seguridad para el acceso al evento, el cual tambien impedira el ingreso en caso de generarse duplicados.</li>
                </ol>
            </div>
        </div>
    </div>
@endforeach
</body>
</html>
