@props(['event'])

@php
    $imageUrl = $event->event_image ?? $event->image ?? null;
    $imgSrc = $imageUrl
        ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl))
        : 'https://picsum.photos/seed/event-' . $event->id . '/560/400';
    $price = $event->ticketTypes->isNotEmpty()
        ? $event->ticketTypes->min('price')
        : ($event->ticket_price ?? 0);
    $soldOut = $event->ticketTypes->isNotEmpty() && $event->ticketTypes->every(fn($t) => $t->quantity <= ($t->quantity_sold ?? 0));
@endphp

<a href="{{ route('events.show', $event->slug) }}" class="group block w-full bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-[0_1px_3px_0_rgb(0_0_0_/_.05)] hover:shadow-[0_8px_30px_-12px_rgb(0_0_0_/_.15)] hover:border-slate-200/80 transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-violet-500/25 focus:ring-offset-2" style="height: 380px;" {{ $attributes }}>
    <div class="relative w-full h-[200px] overflow-hidden rounded-t-2xl bg-slate-100">
        <img src="{{ $imgSrc }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" loading="lazy" width="280" height="200">
        @if($soldOut)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow">Agotado</span>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-white/95 backdrop-blur-sm text-xs font-semibold text-slate-700">{{ $event->category->name ?? 'Evento' }}</span>
    </div>
    <div class="p-4 sm:p-5 flex flex-col h-[180px]">
        <span class="inline-flex self-start px-3 py-1 rounded-lg bg-violet-100 text-violet-700 text-xs font-bold mb-2">
            {{ $event->start_date->format('d') }} {{ strtoupper($event->start_date->translatedFormat('M')) }}
        </span>
        <p class="flex items-center gap-1.5 text-slate-500 text-xs mb-1 truncate">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $event->city }}
        </p>
        <h3 class="font-semibold text-slate-900 text-sm sm:text-base leading-snug line-clamp-2 group-hover:text-violet-600 transition-colors flex-1 min-h-0">
            {{ $event->title }}
        </h3>
        <div class="flex items-center justify-between gap-2 mt-auto pt-3 border-t border-slate-100">
            <span class="text-sm font-bold text-violet-600">Desde S/ {{ number_format($price, 2) }}</span>
            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-600 text-white text-xs font-semibold rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">Comprar</span>
        </div>
    </div>
</a>

