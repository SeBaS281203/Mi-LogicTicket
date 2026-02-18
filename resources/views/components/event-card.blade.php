@props(['event', 'class' => ''])

@php
    $imageUrl = $event->event_image ?? $event->image ?? null;
    $price = $event->ticketTypes->isNotEmpty()
        ? $event->ticketTypes->min('price')
        : ($event->ticket_price ?? 0);
    $soldOut = $event->ticketTypes->isNotEmpty() && $event->ticketTypes->every(fn($t) => $t->quantity <= ($t->quantity_sold ?? 0));
@endphp

<a href="{{ route('events.show', $event->slug) }}" class="group block bg-white rounded-2xl overflow-hidden border border-neutral-100 shadow-sm hover:shadow-lg hover:border-neutral-200 transition-all duration-300 {{ $class }}" {{ $attributes }}>
    <div class="aspect-[4/3] overflow-hidden bg-neutral-100 relative">
        @php
            $imgSrc = $imageUrl
                ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl))
                : 'https://picsum.photos/seed/event-' . $event->id . '/600/450';
        @endphp
        <img src="{{ $imgSrc }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" loading="lazy">
        @if($soldOut)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="px-4 py-2 bg-red-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg">Agotado</span>
            </div>
        @endif
        {{-- Categoría (esquina superior) --}}
        <span class="absolute top-3 left-3 px-2 py-1 rounded-lg bg-white/95 backdrop-blur text-xs font-semibold text-neutral-700 shadow-sm">
            {{ $event->category->name }}
        </span>
        <div class="absolute top-3 right-3 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <button type="button" class="p-1.5 rounded-full bg-white/95 shadow hover:bg-white" onclick="event.preventDefault();" aria-label="Guardar">
                <svg class="w-4 h-4 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
            <button type="button" class="p-1.5 rounded-full bg-white/95 shadow hover:bg-white" onclick="event.preventDefault();" aria-label="Compartir">
                <svg class="w-4 h-4 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            </button>
        </div>
    </div>
    <div class="p-4">
        {{-- Fila "18 FEB | Lima" (estilo Joinnus) --}}
        <p class="flex items-center gap-2 mb-2 text-sm">
            <span class="font-bold text-[#00a650]">{{ $event->start_date->format('d') }} {{ strtoupper($event->start_date->translatedFormat('M')) }}</span>
            <span class="text-neutral-400">|</span>
            <span class="text-neutral-600 flex items-center gap-1 truncate">
                <svg class="w-3.5 h-3.5 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $event->city }}
            </span>
        </p>
        <h2 class="font-semibold text-neutral-900 text-base leading-snug line-clamp-2 group-hover:text-[#00a650] transition-colors">
            {{ $event->title }}
        </h2>
        <p class="mt-3 flex items-center justify-between">
            <span class="text-sm font-bold text-[#00a650]">Desde S/ {{ number_format($price, 2) }}</span>
        </p>
    </div>
</a>
