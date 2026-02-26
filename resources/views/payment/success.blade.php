@extends('layouts.app')

@section('title', '¡Pago Exitoso!')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 lg:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Top Section: Animated Checkmark --}}
        <div class="text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-violet-100 mb-8 animate-bounce transition-all duration-1000">
                <svg class="w-12 h-12 text-[#7c3aed] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4 tracking-tight">¡Pago exitoso!</h1>
            <p class="text-lg text-slate-500 font-medium">
                Tu orden <span class="text-slate-900 font-bold">#{{ $order->order_number }}</span> ha sido confirmada satisfactoriamente.
            </p>
            <div class="mt-6 flex items-center justify-center gap-2 text-violet-600 font-bold">
                <i class="fas fa-envelope"></i>
                <span>Hemos enviado tus entradas a <span class="underline">{{ $order->customer_email }}</span></span>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 items-start">
            
            {{-- Left: Order Summary & Preview --}}
            <div class="space-y-6">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-shopping-bag text-[#7c3aed]"></i>
                            Resumen de Compra
                        </h2>
                        
                        @foreach($order->items as $item)
                        <div class="flex gap-4 mb-6">
                            <div class="w-20 h-20 rounded-2xl bg-violet-600 flex-shrink-0 flex items-center justify-center text-white overflow-hidden shadow-lg shadow-violet-200">
                                @php
                                    $imageUrl = $item->event->event_image ?? $item->event->image ?? null;
                                    $imgSrc = $imageUrl 
                                        ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl)) 
                                        : 'https://picsum.photos/seed/event-'.$item->event->id.'/200/200';
                                @endphp
                                <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h3 class="font-black text-slate-900 leading-tight">{{ $item->event_title }}</h3>
                                <div class="flex flex-col gap-1 mt-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    <span class="flex items-center gap-1.5"><i class="fas fa-calendar-alt text-[#7c3aed]"></i> {{ $item->event->start_date->translatedFormat('d M, Y') }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-[#7c3aed]"></i> {{ $item->event->venue_name }}</span>
                                </div>
                                <p class="mt-3 text-sm font-black text-slate-900">{{ $item->quantity }}x {{ $item->ticket_type_name }}</p>
                            </div>
                        </div>
                        @endforeach

                        <div class="border-t border-slate-100 pt-6 flex justify-between items-center">
                            <span class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Total Pagado</span>
                            <span class="text-2xl font-black text-[#7c3aed]">S/ {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Ticket Preview --}}
                <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 bg-white p-3 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-qrcode text-slate-900 text-5xl"></i>
                        </div>
                        <h3 class="text-lg font-black mb-6">Tus entradas están listas</h3>
                        
                        <div class="space-y-4">
                            <a href="{{ route('cuenta.tickets.download', $order) }}" class="flex items-center justify-center gap-3 w-full py-4 bg-[#7c3aed] hover:bg-[#059669] text-white font-black rounded-xl shadow-lg shadow-violet-500/20 transition-all transform active:scale-95">
                                <i class="fas fa-file-pdf"></i>
                                DESCARGAR ENTRADAS (PDF)
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center justify-center gap-3 w-full py-4 bg-white/10 hover:bg-white/20 text-white font-black rounded-xl border border-white/10 transition-all">
                                <i class="fas fa-ticket-alt"></i>
                                VER EN MIS TICKETS
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Next Steps & Share --}}
            <div class="space-y-6">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                    <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#7c3aed]"></i>
                        Próximos Pasos
                    </h2>
                    
                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0">
                                <i class="fas fa-envelope-open-text text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 mb-1">Revisa tu correo</h4>
                                <p class="text-sm text-slate-500 font-medium">Te enviamos tus entradas adjuntas en formato PDF para que las tengas siempre a mano.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0">
                                <i class="fas fa-mobile-alt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 mb-1">Guarda tu QR</h4>
                                <p class="text-sm text-slate-500 font-medium">Puedes llevarlo en tu celular o impreso. Preséntalo directamente en la entrada del evento.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0">
                                <i class="fas fa-glass-cheers text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 mb-1">¡Disfruta!</h4>
                                <p class="text-sm text-slate-500 font-medium">¡Eso es todo! Prepárate para vivir una experiencia inolvidable. Nos vemos en el evento.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-6 text-center">¿Vas con alguien? Comparte el evento:</h3>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        @php
                            $shareUrl = route('events.show', $order->items->first()->event->slug);
                            $whatsappUrl = "https://api.whatsapp.com/send?text=" . urlencode("¡Ya tengo mis entradas para " . $order->items->first()->event_title . "! Compra las tuyas aquí: " . $shareUrl);
                        @endphp
                        <a href="{{ $whatsappUrl }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 py-4 bg-[#25D366] hover:bg-[#128C7E] text-white font-black rounded-xl shadow-lg shadow-green-100 transition-all">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ $shareUrl }}'); alert('Enlace copiado al portapapeles');" class="flex-1 flex items-center justify-center gap-2 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black rounded-xl transition-all">
                            <i class="fas fa-link"></i>
                            Copiar Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-slate-900 hover:bg-black text-white font-black rounded-2xl shadow-2xl transition-all transform hover:-translate-y-1">
                DESCUBRE MÁS EVENTOS
                <i class="fas fa-chevron-right text-[#7c3aed]"></i>
            </a>
        </div>
    </div>
</div>
@endsection
