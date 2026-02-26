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

<a href="{{ route('events.show', $event->slug) }}" class="group block w-full bg-white rounded-xl overflow-hidden border border-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-violet-500/25 focus:ring-offset-2" {{ $attributes }}>
    <div class="relative w-full h-[180px] overflow-hidden rounded-t-xl bg-slate-100">
        <img src="{{ $imgSrc }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" loading="lazy" width="280" height="180">
        @if($soldOut)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow">Agotado</span>
            </div>
        @endif
        <div class="absolute top-3 left-3">
            <span class="inline-flex px-2.5 py-1 rounded-lg bg-violet-600 text-white text-xs font-bold shadow-md shadow-violet-500/30">
                {{ $event->start_date->format('d') }} {{ strtoupper($event->start_date->translatedFormat('M')) }}
            </span>
        </div>
        <div class="absolute top-3 right-3 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <button type="button" onclick="event.preventDefault();" class="w-8 h-8 rounded-full bg-white/95 backdrop-blur-sm text-slate-600 hover:text-violet-600 flex items-center justify-center shadow-sm hover:shadow transition-all" aria-label="Añadir a favoritos">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
            <button type="button" onclick="event.preventDefault();" class="w-8 h-8 rounded-full bg-white/95 backdrop-blur-sm text-slate-600 hover:text-violet-600 flex items-center justify-center shadow-sm hover:shadow transition-all" aria-label="Compartir">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            </button>
        </div>
    </div>
    <div class="p-4 flex flex-col min-h-[140px]">
        <p class="flex items-center gap-1.5 text-slate-500 text-xs mb-1.5 truncate">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $event->city }}
        </p>
        <h3 class="font-semibold text-slate-900 text-sm leading-snug line-clamp-2 group-hover:text-violet-600 transition-colors flex-1 min-h-0 mb-3">
            {{ $event->title }}
        </h3>
        <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-auto">
            <span class="text-sm font-bold text-violet-600">Desde S/ {{ number_format($price, 2) }}</span>
        </div>
    </div>
</a>
