@props(['event', 'class' => ''])

@php
    $imageUrl = $event->event_image ?? $event->image ?? null;
    $price = $event->ticketTypes->isNotEmpty()
        ? $event->ticketTypes->min('price')
        : ($event->ticket_price ?? 0);
    $soldOut = $event->ticketTypes->isNotEmpty() && $event->ticketTypes->every(fn($t) => $t->quantity <= ($t->quantity_sold ?? 0));
    $imgSrc = $imageUrl
        ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl))
        : 'https://picsum.photos/seed/event-' . $event->id . '/600/450';
@endphp

<a href="{{ route('events.show', $event->slug) }}" class="group block bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-[0_1px_3px_0_rgb(0_0_0_/_.05)] hover:shadow-[0_8px_30px_-12px_rgb(0_0_0_/_.15)] hover:border-slate-200/80 transition-all duration-300 hover:-translate-y-1 active:scale-[0.99] {{ $class }}" {{ $attributes }}>
    <div class="aspect-[4/3] overflow-hidden bg-slate-100 relative">
        <img src="{{ $imgSrc }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" loading="lazy">
        @if($soldOut)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center backdrop-blur-[1px]">
                <span class="px-4 py-2 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-lg">Agotado</span>
            </div>
        @endif
        <span class="absolute top-4 left-4 px-3 py-1.5 rounded-lg bg-white/95 backdrop-blur-sm text-xs font-semibold text-slate-700 shadow-sm">
            {{ $event->category->name ?? 'Evento' }}
        </span>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-5">
            <span class="px-5 py-2.5 bg-violet-600 text-white font-semibold text-sm rounded-xl shadow-lg transform translate-y-3 group-hover:translate-y-0 transition-transform duration-300 ease-out">Comprar ticket</span>
        </div>
    </div>
    <div class="p-5 sm:p-6">
        <p class="flex items-center gap-2 mb-2.5 text-sm text-slate-600">
            <span class="font-bold text-violet-600">{{ $event->start_date->format('d') }} {{ strtoupper($event->start_date->translatedFormat('M')) }}</span>
            <span class="text-slate-300">Â·</span>
            <span class="flex items-center gap-1 truncate">
                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $event->city }}
            </span>
        </p>
        <h2 class="font-semibold text-slate-900 text-base sm:text-lg leading-snug line-clamp-2 group-hover:text-violet-600 transition-colors duration-200 min-h-[2.75rem]">
            {{ $event->title }}
        </h2>
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <span class="text-base font-bold text-violet-600">Desde S/ {{ number_format($price, 2) }}</span>
            <span class="text-xs font-medium text-slate-500 group-hover:text-violet-600 transition-colors">Ver evento â†’</span>
        </div>
    </div>
</a>

