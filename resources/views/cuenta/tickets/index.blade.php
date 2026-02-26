@extends('layouts.cuenta')

@section('title', 'Mis Entradas')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 lg:py-16" x-data="ticketsPage({
    initialOrders: @js($orders->map(function($o) {
        return [
            'id' => $o->id,
            'number' => $o->order_number,
            'status' => $o->status,
            'total' => $o->total,
            'download_url' => route('cuenta.tickets.download', $o),
            'items' => $o->items->map(function($i) {
                return [
                    'event_title' => $i->event_title,
                    'event_slug' => $i->event->slug ?? '',
                    'event_image' => $i->event->event_image ?? $i->event->image ?? null,
                    'is_past' => $i->event ? $i->event->start_date < now() : false,
                    'date' => $i->event ? $i->event->start_date->translatedFormat('d M Y') : 'TBD',
                    'time' => $i->event ? $i->event->start_date->format('H:i A') : 'TBD',
                    'location' => $i->event ? $i->event->venue_name . ', ' . $i->event->city : 'TBD',
                    'ticket_type' => $i->ticket_type_name,
                    'quantity' => $i->quantity,
                    'tickets' => $i->tickets->map(fn($t) => ['code' => $t->code, 'qr_url' => route('cuenta.tickets.qr', $t->code)])
                ];
            })
        ];
    }))
})">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Stats Bar --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-6 mb-10 flex flex-wrap items-center justify-around gap-6 text-center">
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Entradas</p>
                <p class="text-2xl font-black text-slate-900">{{ $stats['total_tickets'] }}</p>
            </div>
            <div class="w-px h-10 bg-slate-100 hidden sm:block"></div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Eventos Próximos</p>
                <p class="text-2xl font-black text-[#7c3aed]">{{ $stats['upcoming_events'] }}</p>
            </div>
            <div class="w-px h-10 bg-slate-100 hidden sm:block"></div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Invertido</p>
                <p class="text-2xl font-black text-slate-900">S/ {{ number_format($stats['total_invested'], 2) }}</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 flex items-center gap-3">
                    Mis Entradas
                    <span class="bg-slate-900 text-white text-xs px-2.5 py-1 rounded-full" x-text="filteredOrders.length"></span>
                </h1>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                {{-- Tabs --}}
                <div class="bg-slate-200/50 p-1 rounded-xl flex w-full sm:w-auto">
                    <button @click="filter = 'upcoming'" :class="filter === 'upcoming' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-700'" class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-black transition-all">Próximos</button>
                    <button @click="filter = 'past'" :class="filter === 'past' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-700'" class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-black transition-all">Pasados</button>
                    <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-700'" class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-black transition-all">Todos</button>
                </div>

                {{-- Search --}}
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" x-model="search" placeholder="Buscar evento..." 
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#7c3aed] focus:ring-0 text-sm font-bold transition-all placeholder:text-slate-300">
                </div>
            </div>
        </div>

        {{-- Ticket Cards Grid --}}
        <div class="grid md:grid-cols-2 gap-8" x-show="filteredOrders.length > 0">
            <template x-for="order in filteredOrders" :key="order.id">
                <template x-for="(item, idx) in order.items" :key="order.id + '-' + idx">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden group hover:border-[#7c3aed]/30 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex h-full">
                            {{-- Event Image --}}
                            <div class="w-32 sm:w-40 relative shrink-0 overflow-hidden bg-slate-100">
                                <img :src="item.event_image ? (item.event_image.startsWith('http') ? item.event_image : '/storage/' + item.event_image) : 'https://picsum.photos/seed/' + order.id + '/400/600'" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                <template x-if="item.is_past">
                                    <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center p-2 text-center">
                                        <span class="text-[10px] font-black text-white uppercase tracking-widest leading-tight">Evento Finalizado</span>
                                    </div>
                                </template>
                            </div>

                            {{-- Ticket Content --}}
                            <div class="flex-1 p-5 sm:p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-3">
                                        <span :class="{
                                            'bg-violet-100 text-violet-700': order.status === 'paid',
                                            'bg-amber-100 text-amber-700': order.status === 'pending',
                                            'bg-red-100 text-red-700': order.status === 'failed',
                                            'bg-slate-100 text-slate-500': item.is_past
                                        }" class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-lg" x-text="item.is_past ? 'USADO' : order.status"></span>
                                        
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest" x-text="'#' + order.number.split('-')[1]"></span>
                                    </div>

                                    <h3 class="font-black text-slate-900 leading-tight mb-2 group-hover:text-[#7c3aed] transition-colors" x-text="item.event_title"></h3>
                                    
                                    <div class="space-y-1 text-[11px] font-bold text-slate-400">
                                        <p class="flex items-center gap-2"><i class="fas fa-calendar-alt text-[#7c3aed] w-3"></i> <span x-text="item.date + ' · ' + item.time"></span></p>
                                        <p class="flex items-center gap-2 truncate"><i class="fas fa-map-marker-alt text-[#7c3aed] w-3"></i> <span x-text="item.location"></span></p>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between gap-3 flex-wrap">
                                    <div>
                                        <p class="text-[10px] font-black uppercase text-slate-300 tracking-widest" x-text="item.ticket_type + ' × ' + item.quantity"></p>
                                        <p class="text-sm font-black text-slate-900 mt-1" x-text="'S/ ' + order.total.toFixed(2)"></p>
                                    </div>

                                    <div class="ml-auto">
                                        <a :href="order.download_url" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-[11px] font-semibold shadow-md shadow-slate-200 hover:bg-black transition-all whitespace-nowrap">
                                            <i class="fas fa-ticket-alt text-xs"></i>
                                            <span>Descargar ticket</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </div>

        {{-- Empty State --}}
        <div x-show="filteredOrders.length === 0" class="text-center py-20 bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <div class="w-24 h-24 bg-violet-50 text-violet-600 rounded-full flex items-center justify-center mx-auto mb-8">
                <i class="fas fa-ticket-alt text-4xl transform rotate-12"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-2">Aún no tienes entradas</h2>
            <p class="text-slate-400 font-bold mb-10 max-w-xs mx-auto">Descubre eventos increíbles y compra tus primeras entradas en segundos.</p>
            <a href="{{ route('events.index') }}" class="px-8 py-4 bg-slate-900 hover:bg-black text-white font-black rounded-2xl shadow-xl transition-all inline-flex items-center gap-3 transform hover:-translate-y-1">
                VER EVENTOS
                <i class="fas fa-chevron-right text-[#7c3aed]"></i>
            </a>
        </div>
    </div>

    {{-- QR MODAL (Alpine.js) --}}
    <div x-show="qrModal.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/95 backdrop-blur-sm"
         x-cloak>
        
        <div @click.away="qrModal.open = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="scale-90 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             class="bg-white rounded-[3rem] p-8 sm:p-12 max-w-sm w-full text-center relative shadow-2xl">
            
            <button @click="qrModal.open = false" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-slate-100 text-slate-400 hover:text-slate-900 transition-colors flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>

            <h3 class="text-xl font-black text-slate-900 mb-2" x-text="qrModal.title"></h3>
            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-8" x-text="qrModal.subtitle"></p>

            <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100 mb-8 aspect-square flex items-center justify-center relative group">
                <img :src="qrModal.qrUrl" class="w-full h-full object-contain" alt="QR Code">
                <div class="absolute inset-0 bg-white/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-[2rem]">
                    <p class="text-xs font-black text-slate-900 tracking-tighter">LISTO PARA ESCANEAR</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="py-3 px-6 bg-slate-100 rounded-xl font-mono font-bold text-slate-900 tracking-widest text-sm" x-text="qrModal.code"></div>
                
                <div class="flex items-center justify-center gap-3 text-[#7c3aed] bg-violet-50 py-3 rounded-xl">
                    <i class="fas fa-lightbulb"></i>
                    <p class="text-[10px] font-black uppercase tracking-widest leading-none">Sube el brillo al máximo</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function ticketsPage(data) {
    return {
        orders: data.initialOrders,
        filter: 'upcoming',
        search: '',
        qrModal: {
            open: false,
            title: '',
            subtitle: '',
            qrUrl: '',
            code: ''
        },

        get filteredOrders() {
            let filtered = this.orders;

            // Search filter (on event titles)
            if (this.search) {
                const s = this.search.toLowerCase();
                filtered = filtered.filter(o => 
                    o.items.some(i => i.event_title.toLowerCase().includes(s))
                );
            }

            // Tab filter
            if (this.filter === 'upcoming') {
                return filtered.filter(o => o.items.some(i => !i.is_past) && o.status === 'paid');
            } else if (this.filter === 'past') {
                return filtered.filter(o => o.items.some(i => i.is_past) && o.status === 'paid');
            }

            return filtered;
        },

        openQRModal(item) {
            const ticket = item.tickets[0]; // Mostramos el primer ticket por ahora
            this.qrModal = {
                open: true,
                title: item.event_title,
                subtitle: item.ticket_type + ' - ' + item.date,
                qrUrl: ticket.qr_url,
                code: ticket.code
            };
        }
    };
}
</script>
@endpush
@endsection
