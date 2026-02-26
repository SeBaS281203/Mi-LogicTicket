<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerificaciÃ³n de Ticket | ChiclayoTicket</title>
    <style>
        /* Estilos Base Optimizados (Sin Dependencias Externas) */
        :root {
            --brand: #7c3aed;
            --brand-dark: #059669;
            --surface: #f3f4f6;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --red: #ef4444;
            --amber: #f59e0b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background-color: var(--surface); 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--gray-900);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .container { padding: 2rem 1rem; }
        
        .boarding-pass {
            max-width: 400px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Cabecera de Estado */
        .status-header {
            padding: 2.5rem 1.25rem;
            text-align: center;
            color: var(--white);
        }
        .status-valid { background-color: var(--brand); }
        .status-invalid { background-color: var(--red); }
        .status-used { background-color: var(--amber); }

        .status-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .status-header h1 { font-size: 1.875rem; font-weight: 900; letter-spacing: -0.025em; }
        .status-header p { margin-top: 0.5rem; opacity: 0.9; font-weight: 500; font-size: 0.875rem; }

        /* Contenido */
        .details { padding: 2rem 1.5rem; }
        
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .logo-box { display: flex; align-items: center; gap: 0.625rem; }
        .logo-l {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            background-color: var(--brand);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.75rem;
        }
        .brand-name { font-weight: 700; font-size: 1rem; }

        .label { color: var(--gray-500); font-size: 0.6875rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem; }
        .value { color: var(--gray-900); font-size: 0.9375rem; font-weight: 600; margin-bottom: 1.25rem; }
        
        .event-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.2; }
        
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* Divisor de Ticket */
        .divider {
            height: 2px;
            border-top: 2px dashed var(--gray-200);
            margin: 1.5rem 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: -11px;
            width: 20px;
            height: 20px;
            background-color: var(--surface);
            border-radius: 50%;
        }
        .divider::before { left: -35px; }
        .divider::after { right: -35px; }

        .code-box {
            background-color: var(--gray-50);
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--gray-100);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .code-value { font-family: monospace; font-size: 1.125rem; font-weight: 700; }

        /* Botones */
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }
        .btn-dark { background-color: var(--gray-800); color: var(--white); }
        .btn-brand { background-color: var(--brand); color: var(--white); padding: 1rem; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); }
        .btn-support { background-color: var(--brand-dark); color: var(--white); }
        .btn:active { transform: scale(0.98); }

        .support-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 0.75rem;
            padding: 0.875rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        .support-number {
            font-size: 1.125rem;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 0.625rem;
            letter-spacing: 0.02em;
        }

        .footer { background-color: var(--gray-50); padding: 1rem; text-align: center; }
        .footer p { font-size: 10px; color: var(--gray-400); font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray-500);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
        }

        .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }

        svg { fill: currentColor; }
    </style>
</head>
<body>
    <div class="container">
        <div class="boarding-pass">
            {{-- Header --}}
            <div class="status-header {{ $status === 'valid' ? 'status-valid' : ($status === 'used' ? 'status-used' : 'status-invalid') }}">
                @if($status === 'valid')
                    <div class="status-icon animate-pulse">
                        <svg viewBox="0 0 24 24" width="60" height="60"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    </div>
                    <h1>TICKET VÃLIDO</h1>
                    <p>Este ticket es autÃ©ntico y estÃ¡ listo para ser usado.</p>
                @else
                    <div class="status-icon">
                        <svg viewBox="0 0 24 24" width="60" height="60"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                    </div>
                    <h1>{{ $status === 'used' ? 'YA UTILIZADO' : 'INVÃLIDO' }}</h1>
                    <p>{{ $status === 'used' ? 'Este ticket ya fue escaneado anteriormente.' : $message }}</p>
                @endif
            </div>

            <div class="details">
                <div class="top-row">
                    <div class="logo-box">
                        <span class="logo-l">L</span>
                        <span class="brand-name">ChiclayoTicket</span>
                    </div>
                    <div style="text-align: right">
                        <span class="label">Estado</span>
                        <span style="font-weight: 800; font-size: 0.75rem; color: {{ $status === 'valid' ? 'var(--brand-dark)' : 'var(--red)' }}">
                            {{ $status === 'valid' ? 'ACTIVO' : 'INACTIVO' }}
                        </span>
                    </div>
                </div>

                @if($ticket)
                    <span class="label">Evento</span>
                    <div class="event-title">{{ $ticket->orderItem->event_title }}</div>

                    <div class="grid">
                        <div>
                            <span class="label">Fecha</span>
                            <div class="value">{{ \Carbon\Carbon::parse($ticket->orderItem->event->start_date)->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            <span class="label">Hora</span>
                            <div class="value">{{ \Carbon\Carbon::parse($ticket->orderItem->event->start_date)->format('H:i') }}</div>
                        </div>
                    </div>

                    <span class="label">UbicaciÃ³n</span>
                    <div class="value">{{ $ticket->orderItem->event->venue_name }}, {{ $ticket->orderItem->event->city }}</div>

                    <div class="divider"></div>

                    <span class="label">Asistente</span>
                    <div class="value">{{ $ticket->orderItem->order->customer_name }}</div>

                    <div class="grid">
                        <div>
                            <span class="label">Tipo</span>
                            <div class="value">{{ $ticket->orderItem->ticket_type_name }}</div>
                        </div>
                        <div>
                            <span class="label">Orden</span>
                            <div class="value">#{{ $ticket->orderItem->order->order_number }}</div>
                        </div>
                    </div>

                    <div class="code-box">
                        <span class="label">CÃ³digo Ãšnico</span>
                        <div class="code-value">{{ $ticket->code }}</div>
                    </div>

                    
                    <div class="support-box">
                        <span class="label">Ayuda al cliente ChiclayoTicket</span>
                        <div class="support-number">948 745 909</div>
                        <a href="tel:+51948745909" class="btn btn-support">LLAMAR AHORA</a>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="{{ route('orders.confirmation', $ticket->orderItem->order_id) }}" class="btn btn-dark">
                            <svg viewBox="0 0 24 24" width="16" height="16" style="margin-right: 4px;"><path d="M20 2H8c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zM8 16V4h12l.001 12H8z"/><path d="M4 8H2v12c0 1.103.897 2 2 2h12v-2H4V8z"/></svg>
                            VER COMPROBANTE
                        </a>
                        
                        @auth
                            @if(!$ticket->is_used && ($status === 'valid'))
                                <form action="{{ route('validate.scan', $ticket->code) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-brand">
                                        REGISTRAR INGRESO
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            <div class="footer">
                <p>VerificaciÃ³n Segura por ChiclayoTicket</p>
            </div>
        </div>
        
        <a href="{{ url('/') }}" class="back-link">
            Volver al inicio
        </a>
    </div>
</body>
</html>

