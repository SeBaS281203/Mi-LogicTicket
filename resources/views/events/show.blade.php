@extends('layouts.app')

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->description), 160))

@section('content')
@php
    $imageUrl = $event->event_image ?? $event->image ?? null;
    $imgSrc = $imageUrl
        ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl))
        : 'https://picsum.photos/seed/event-' . $event->id . '/1920/1080';
    
    $mapUrl = null;
    if ($event->latitude && $event->longitude) {
        $minlon = $event->longitude - 0.015;
        $maxlon = $event->longitude + 0.015;
        $minlat = $event->latitude - 0.01;
        $maxlat = $event->latitude + 0.01;
        $mapUrl = 'https://www.openstreetmap.org/export/embed.html?bbox=' . $minlon . '%2C' . $minlat . '%2C' . $maxlon . '%2C' . $maxlat . '&layer=mapnik&marker=' . $event->latitude . '%2C' . $event->longitude;
    }

    $daysUntil = now()->diffInDays($event->start_date);
    $isUrgent = $daysUntil <= 7;
    
    // Simulate social proof
    $recentBuyers = ($event->id % 50) + 20;
    $soldPercentage = max(65, ($event->id % 30) + 65);
@endphp

<article class="min-h-screen bg-slate-50" 
    x-data="eventPage({
        tickets: @js($event->ticketTypes->map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'price' => (float)$t->price,
            'available' => $t->available_quantity,
            'selected' => 0,
            'description' => $t->description
        ])),
        commission: @js(config('logic-ticket.commission_percentage', 5)),
        eventDate: '{{ $event->start_date->toIso8601String() }}'
    })"
    itemscope itemtype="https://schema.org/Event">
    
    {{-- Hero Section --}}
    <section class="relative w-full h-[50vh] min-h-[400px] lg:h-[65vh] overflow-hidden bg-slate-900">
        <img src="{{ $imgSrc }}" alt="{{ $event->title }}" class="w-full h-full object-cover opacity-60" itemprop="image">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
        
        <div class="absolute inset-0 flex items-center lg:items-end">
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-12 lg:pb-20">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                    <div class="max-w-3xl">
                        <nav class="flex items-center gap-2 mb-6 text-white/70 text-sm font-medium">
                            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Eventos</a>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-white">{{ $event->category->name ?? 'Evento' }}</span>
                        </nav>
                        
                        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-white leading-tight mb-6 tracking-tight" itemprop="name">
                            {{ $event->title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-y-4 gap-x-8 text-white/90">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-violet-500">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="text-sm">
                                    <p class="font-bold uppercase tracking-wider text-xs opacity-60">Fecha</p>
                                    <p class="font-semibold">{{ $event->start_date->translatedFormat('d F, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-violet-500">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="text-sm">
                                    <p class="font-bold uppercase tracking-wider text-xs opacity-60">Hora</p>
                                    <p class="font-semibold">{{ $event->start_date->format('H:i') }} hrs</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-violet-500">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="text-sm">
                                    <p class="font-bold uppercase tracking-wider text-xs opacity-60">Lugar</p>
                                    <p class="font-semibold">{{ $event->venue_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hidden lg:flex flex-col items-end gap-4">
                        <button @click="shareEvent()" class="group flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white font-bold rounded-2xl border border-white/20 transition-all">
                            <i class="fas fa-share-alt"></i>
                            Compartir
                        </button>
                        <a href="#ticket-selector" class="px-10 py-5 bg-[#10b981] hover:bg-[#059669] text-white font-black rounded-2xl shadow-2xl shadow-emerald-500/20 transform hover:-translate-y-1 transition-all">
                            COMPRAR ENTRADAS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10 pb-20">
        <div class="grid lg:grid-cols-3 gap-8 lg:gap-12">
            
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Countdown & Urgency (Static for PHP, dynamic if needed) --}}
                @if($isUrgent)
                <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-3xl p-6 text-white shadow-xl shadow-red-200">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">🔥</div>
                            <div>
                                <h3 class="font-black text-xl">¡Queda poco tiempo!</h3>
                                <p class="text-white/80 font-medium tracking-wide">El evento comienza en:</p>
                            </div>
                        </div>
                        <div class="flex gap-4 text-center" x-data="countdown('{{ $event->start_date->toIso8601String() }}')" x-init="init()">
                            <template x-for="(val, unit) in timeLeft" :key="unit">
                                <div class="bg-black/20 backdrop-blur-sm rounded-2xl px-4 py-3 min-w-[70px]">
                                    <p class="text-2xl font-black" x-text="val"></p>
                                    <p class="text-[10px] uppercase font-bold opacity-70" x-text="unit"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tabs Section --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden" x-data="{ activeTab: 'description' }">
                    <div class="flex border-b border-slate-100 bg-slate-50/50">
                        <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-b-4 border-[#10b981] text-violet-500' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-6 font-bold text-sm uppercase tracking-widest transition-all">Descripción</button>
                        <button @click="activeTab = 'location'" :class="activeTab === 'location' ? 'border-b-4 border-[#10b981] text-violet-500' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-6 font-bold text-sm uppercase tracking-widest transition-all">Ubicación</button>
                        <button @click="activeTab = 'organizer'" :class="activeTab === 'organizer' ? 'border-b-4 border-[#10b981] text-violet-500' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-6 font-bold text-sm uppercase tracking-widest transition-all">Organizador</button>
                    </div>
                    
                    <div class="p-8 sm:p-10">
                        {{-- Description Tab --}}
                        <div x-show="activeTab === 'description'" x-cloak class="prose prose-slate max-w-none prose-p:text-slate-600 prose-p:leading-relaxed prose-strong:text-slate-900 prose-headings:text-slate-900">
                            {!! nl2br($event->description) !!}
                        </div>
                        
                        {{-- Location Tab --}}
                        <div x-show="activeTab === 'location'" x-cloak>
                            <div class="flex flex-col md:flex-row gap-8 mb-8">
                                <div class="flex-1">
                                    <h3 class="text-xl font-black text-slate-900 mb-2">{{ $event->venue_name }}</h3>
                                    <p class="text-slate-600 mb-6"><i class="fas fa-map-pin text-violet-500 me-2"></i> {{ $event->venue_address }}, {{ $event->city }}</p>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->venue_name . ' ' . $event->venue_address) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                                        <i class="fab fa-google"></i>
                                        Como llegar
                                    </a>
                                </div>
                                <div class="w-full md:w-1/2 h-[300px] rounded-3xl overflow-hidden border border-slate-100 shadow-inner bg-slate-50">
                                    @if($mapUrl)
                                        <iframe src="{{ $mapUrl }}" class="w-full h-full grayscale-[0.3]" style="border:0;" allowfullscreen loading="lazy"></iframe>
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-3">
                                            <i class="fas fa-map-marked-alt text-4xl"></i>
                                            <p class="text-sm font-medium">Mapa no disponible</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Organizer Tab --}}
                        <div x-show="activeTab === 'organizer'" x-cloak>
                            <div class="flex items-center gap-6 mb-8">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($event->user->name ?? 'O') }}&background=10b981&color=fff&size=128" alt="Org" class="w-24 h-24 rounded-[2rem] shadow-lg">
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900">{{ $event->user->name ?? 'Organizador' }}</h3>
                                    <div class="flex items-center gap-2 text-amber-500 mt-1">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span class="text-slate-400 text-sm font-bold ms-2">(4.8/5)</span>
                                    </div>
                                    <p class="text-xs font-black text-violet-500 uppercase tracking-widest mt-2">Organizador Verificado</p>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] uppercase font-black text-slate-400 tracking-widest">Eventos realizados</p>
                                    <p class="text-lg font-bold text-slate-800">12 Eventos</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] uppercase font-black text-slate-400 tracking-widest">Antigüedad</p>
                                    <p class="text-lg font-bold text-slate-800">Desde 2023</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Proof & Recent Buyers --}}
                <div class="flex flex-col md:flex-row items-center gap-8 px-4 py-4">
                    <div class="flex -space-x-3 overflow-hidden">
                        @for($i=1; $i<=5; $i++)
                            <img class="inline-block h-12 w-12 rounded-full ring-4 ring-slate-50" src="https://i.pravatar.cc/150?u={{ $event->id + $i }}" alt="user">
                        @endfor
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-600 ring-4 ring-slate-50">+{{ $recentBuyers }}</div>
                    </div>
                    <p class="text-slate-600 font-medium italic">
                        <span class="text-slate-900 font-black">{{ $recentBuyers }} personas</span> compraron sus entradas en las últimas 24 horas. ¡No te quedes fuera!
                    </p>
                </div>
            </div>

            {{-- Right Column (Sticky Ticket Selector) --}}
            <div class="lg:col-span-1" id="ticket-selector">
                <div class="lg:sticky lg:top-24 space-y-6">
                    
                    {{-- Urgency Progress Bar --}}
                    @if($soldPercentage > 70)
                    <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                        <div class="flex justify-between items-end mb-3">
                            <h4 class="text-sm font-black text-slate-900 uppercase">Estado de venta</h4>
                            <span class="text-orange-600 font-black text-xs">¡{{ $soldPercentage }}% VENDIDO!</span>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-orange-400 to-red-500 rounded-full" style="width: {{ $soldPercentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">Quedan las últimas entradas disponibles</p>
                    </div>
                    @endif

                    {{-- Ticket Selector Card --}}
                    <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                        <div class="bg-slate-900 p-6 text-white">
                            <h2 class="text-xl font-black tracking-tight flex items-center gap-2">
                                <i class="fas fa-ticket-alt text-violet-500"></i>
                                Selección de entradas
                            </h2>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <template x-for="(ticket, index) in tickets" :key="ticket.id">
                                <div class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-[#10b981] transition-all duration-200"
                                     :class="{ 'border-[#10b981] bg-emerald-50/10': ticket.selected > 0 }">

                                    {{-- Top row: name + price --}}
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="font-bold text-slate-900 text-base" x-text="ticket.name"></span>
                                        <span class="font-bold text-violet-500 text-base" x-text="'S/ ' + ticket.price.toFixed(2)"></span>
                                    </div>

                                    {{-- Bottom row: badge + controls --}}
                                    <div class="flex items-center justify-between">
                                        {{-- Stock badge --}}
                                        <div>
                                            <template x-if="ticket.available > 0">
                                                <span class="text-[10px] bg-slate-100 text-slate-500 px-3 py-1 rounded-full font-black uppercase tracking-widest">
                                                    DISPONIBLE
                                                </span>
                                            </template>
                                            <template x-if="ticket.available === 0">
                                                <span class="text-[10px] bg-red-100 text-red-500 px-3 py-1 rounded-full font-black uppercase tracking-widest">
                                                    AGOTADO
                                                </span>
                                            </template>
                                        </div>

                                        {{-- Quantity controls --}}
                                        <div class="flex items-center gap-3" x-show="ticket.available > 0">
                                            <button @click="updateQty(index, -1)"
                                                    :disabled="ticket.selected <= 0"
                                                    class="w-9 h-9 rounded-full border-2 border-slate-300 bg-white flex items-center justify-center text-slate-500 font-bold text-xl leading-none hover:border-[#10b981] hover:text-violet-500 hover:bg-emerald-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all duration-200 select-none">
                                                −
                                            </button>

                                            <span x-text="ticket.selected"
                                                  class="w-10 text-center font-black text-slate-900 text-lg">
                                            </span>

                                            <button @click="updateQty(index, 1)"
                                                    :disabled="ticket.selected >= ticket.available"
                                                    class="w-9 h-9 rounded-full border-2 border-slate-300 bg-white flex items-center justify-center text-slate-500 font-bold text-xl leading-none hover:border-[#10b981] hover:text-violet-500 hover:bg-emerald-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all duration-200 select-none">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            {{-- Calculator Section --}}
                            <div class="mt-8 pt-6 border-t border-slate-100 bg-slate-50/50 -mx-6 px-6 pb-6">
                                <div class="space-y-2 mb-6">
                                    <div class="flex justify-between text-slate-500 text-sm font-medium">
                                        <span>Subtotal</span>
                                        <span x-text="'S/ ' + calculator.subtotal.toFixed(2)"></span>
                                    </div>
                                    <div class="flex justify-between text-slate-500 text-sm font-medium">
                                        <span>Comisión (<span x-text="commission"></span>%)</span>
                                        <span x-text="'S/ ' + calculator.commission.toFixed(2)"></span>
                                    </div>
                                    <div class="flex justify-between text-slate-900 font-process pt-2 border-t border-slate-200">
                                        <span class="text-lg font-black tracking-tight">Total Final</span>
                                        <span class="text-2xl font-black text-violet-500" x-text="'S/ ' + calculator.total.toFixed(2)"></span>
                                    </div>
                                </div>
                                
                                <form @submit.prevent="addToCart()" id="add-to-cart-form">
                                    <button type="submit" 
                                        :disabled="calculator.total === 0 || loading"
                                        class="w-full py-5 px-8 bg-[#10b981] disabled:bg-slate-200 disabled:cursor-not-allowed hover:bg-[#059669] text-white font-black rounded-2xl shadow-xl shadow-emerald-500/20 transform active:scale-95 transition-all text-lg flex items-center justify-center gap-3">
                                        <template x-if="!loading">
                                            <i class="fas fa-shopping-cart"></i>
                                        </template>
                                        <template x-if="loading">
                                            <i class="fas fa-circle-notch animate-spin"></i>
                                        </template>
                                        <span x-text="loading ? 'PROCESANDO...' : 'AGREGAR AL CARRITO'"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Floating CTA for Mobile --}}
    <div class="lg:hidden fixed bottom-6 left-4 right-4 z-50 transition-transform duration-300"
         :style="showFloatingCta ? 'transform: translateY(0)' : 'transform: translateY(150%)'">
        <a href="#ticket-selector" class="w-full py-5 px-8 bg-slate-900 text-white font-black rounded-3xl shadow-2xl flex items-center justify-between group">
            <span class="flex flex-col text-left">
                <span class="text-[10px] uppercase font-bold text-white/50 leading-none mb-1">Entradas desde</span>
                <span class="text-lg font-black leading-none" x-text="'S/ ' + minimalPrice.toFixed(2)"></span>
            </span>
            <span class="flex items-center gap-2">
                COMPRAR AHORA
                <i class="fas fa-arrow-right text-violet-500 group-hover:translate-x-1 transition-transform"></i>
            </span>
        </a>
    </div>

</article>

@push('scripts')
<script>
    function eventPage(data) {
        return {
            tickets: data.tickets,
            commission: data.commission,
            loading: false,
            showFloatingCta: false,
            
            init() {
                window.addEventListener('scroll', () => {
                    const ticketSelector = document.getElementById('ticket-selector');
                    const rect = ticketSelector.getBoundingClientRect();
                    this.showFloatingCta = rect.top > window.innerHeight && window.scrollY > 400;
                });
            },

            get minimalPrice() {
                return Math.min(...this.tickets.map(t => t.price));
            },

            updateQty(index, delta) {
                const ticket = this.tickets[index];
                const newVal = ticket.selected + delta;
                if (newVal >= 0 && newVal <= ticket.available) {
                    ticket.selected = newVal;
                }
            },

            get calculator() {
                const subtotal = this.tickets.reduce((sum, t) => sum + (t.price * t.selected), 0);
                const comm = subtotal * (this.commission / 100);
                return {
                    subtotal: subtotal,
                    commission: comm,
                    total: subtotal + comm
                };
            },

            async addToCart() {
                if (this.calculator.total === 0) return;
                this.loading = true;
                
                try {
                    // Collect selected tickets
                    const selected = this.tickets.filter(t => t.selected > 0);
                    
                    // Call API to add to cart (using existing endpoint multiple times or a better one)
                    for (const item of selected) {
                        const formData = new FormData();
                        formData.append('ticket_type_id', item.id);
                        formData.append('quantity', item.selected);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        
                        await fetch('{{ route("cart.add") }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                    }

                    // Success animation / feedback
                    if (window.Alpine && window.Alpine.store('toast')) {
                        window.Alpine.store('toast').success('¡Entradas agregadas al carrito!');
                    }
                    
                    // Small delay then redirect or update UI
                    setTimeout(() => {
                        window.location.href = '{{ route("cart.index") }}';
                    }, 500);
                    
                } catch (err) {
                    console.error(err);
                    alert('Error al agregar al carrito');
                } finally {
                    this.loading = false;
                }
            },
            
            shareEvent() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $event->title }}',
                        text: '¡Mira este evento en ChiclayoTicket!',
                        url: window.location.href
                    });
                } else {
                    navigator.clipboard.writeText(window.location.href);
                    alert('Enlace copiado al portapapeles');
                }
            }
        };
    }

    function countdown(dateString) {
        return {
            endDate: new Date(dateString).getTime(),
            timeLeft: { días: 0, horas: 0, min: 0, seg: 0 },
            
            init() {
                this.update();
                setInterval(() => this.update(), 1000);
            },
            
            update() {
                const now = new Date().getTime();
                const dist = this.endDate - now;
                
                if (dist < 0) return;
                
                this.timeLeft.días = Math.floor(dist / (1000 * 60 * 60 * 24));
                this.timeLeft.horas = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.timeLeft.min = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
                this.timeLeft.seg = Math.floor((dist % (1000 * 60)) / 1000);
            }
        };
    }
</script>
@endpush

@endsection
