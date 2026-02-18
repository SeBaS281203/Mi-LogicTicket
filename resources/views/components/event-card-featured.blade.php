{{--
  Tarjeta evento destacado – Estructura fija:
  Ancho: 260–280px (definido por contenedor) · Altura total: 380px
  Imagen: 200px, radius solo arriba, object-cover, lazy
  Contenido: fecha (badge), ciudad, título, precio desde, botones favorito/compartir · p-4
  Hover: elevación, scale 1.02, sombra fuerte
--}}
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

<a href="{{ route('events.show', $event->slug) }}" class="group block w-full bg-white rounded-xl overflow-hidden border border-neutral-100 shadow-md hover:shadow-xl transition-all duration-300 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-[#00a650]/30 focus:ring-offset-2" style="height: 380px;" {{ $attributes }}>
    {{-- Imagen: 200px, radius solo arriba, object-cover --}}
    <div class="relative w-full h-[200px] overflow-hidden rounded-t-xl bg-neutral-100">
        <img
            src="{{ $imgSrc }}"
            alt="{{ $event->title }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            loading="lazy"
            decoding="async"
            width="280"
            height="200"
        >
        @if($soldOut)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg">Agotado</span>
            </div>
        @endif
    </div>

    {{-- Contenido: p-4 --}}
    <div class="p-4 flex flex-col h-[180px]">
        {{-- Fecha (badge pequeño arriba) --}}
        <span class="inline-flex self-start px-2.5 py-1 rounded-md bg-[#00a650]/10 text-[#00a650] text-xs font-bold mb-2">
            {{ $event->start_date->format('d') }} {{ strtoupper($event->start_date->translatedFormat('M')) }}
        </span>
        {{-- Ciudad --}}
        <p class="flex items-center gap-1.5 text-neutral-500 text-xs mb-1 truncate">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $event->city }}
        </p>
        {{-- Título del evento --}}
        <h3 class="font-semibold text-neutral-900 text-sm leading-snug line-clamp-2 group-hover:text-[#00a650] transition-colors flex-1 min-h-0">
            {{ $event->title }}
        </h3>
        {{-- Precio desde + botones --}}
        <div class="flex items-center justify-between gap-2 mt-2 pt-2 border-t border-neutral-100">
            <span class="text-sm font-bold text-[#00a650]">Desde S/ {{ number_format($price, 2) }}</span>
            <div class="flex items-center gap-1">
                <button type="button" class="p-2 rounded-full text-neutral-400 hover:text-[#00a650] hover:bg-[#00a650]/10 transition-colors" onclick="event.preventDefault();" aria-label="Guardar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
                <button type="button" class="p-2 rounded-full text-neutral-400 hover:text-[#00a650] hover:bg-[#00a650]/10 transition-colors" onclick="event.preventDefault();" aria-label="Compartir">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                </button>
            </div>
        </div>
    </div>
</a>
