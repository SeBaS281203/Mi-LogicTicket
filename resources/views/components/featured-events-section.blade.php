{{--
  Sección Eventos destacados
  Grid: 4 cols desktop, 2 tablet · Mobile: scroll horizontal
  Gap: 24px · Cards: event-card-featured (260–280px, 380px, lazy loading)
--}}
@props(['events', 'title' => 'Imperdibles', 'sectionSubtitle' => 'Planes', 'seeAllUrl' => null])

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-white rounded-2xl shadow-sm border border-neutral-100" aria-labelledby="featured-events-heading">
    <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 id="featured-events-heading" class="text-xl font-bold text-neutral-900 tracking-tight">
                    <span class="block text-[#00a650] text-sm uppercase tracking-wider">{{ $sectionSubtitle }}</span>
                    <span class="block text-neutral-900 leading-tight uppercase">{{ $title }}</span>
                </h2>
                @if($events->isNotEmpty())
                <div class="flex items-center gap-1.5 flex-shrink-0 md:hidden">
                    <button type="button" data-featured-prev class="w-9 h-9 rounded-full border border-neutral-200 bg-white hover:bg-neutral-50 flex items-center justify-center text-neutral-600 transition-colors" aria-label="Anterior">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" data-featured-next class="w-9 h-9 rounded-full border border-neutral-200 bg-white hover:bg-neutral-50 flex items-center justify-center text-neutral-600 transition-colors" aria-label="Siguiente">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @endif
            </div>

            @if($events->isNotEmpty())
            {{-- Mobile: scroll horizontal · Tablet+: grid 2 cols · Desktop: 4 cols · gap-6 (24px) --}}
            <div id="featured-events-track" class="flex md:grid md:grid-cols-2 lg:grid-cols-4 gap-6 overflow-x-auto md:overflow-visible pb-2 md:pb-0 snap-x snap-mandatory scroll-smooth scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($events as $event)
                    <div class="flex-shrink-0 w-[260px] sm:w-[270px] md:flex-shrink md:w-auto snap-start min-w-0">
                        @include('components.event-card-featured', ['event' => $event])
                    </div>
                @endforeach
            </div>
            @if($seeAllUrl)
            <div class="mt-4 text-center md:text-left">
                <a href="{{ $seeAllUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#00a650] hover:text-[#009345] transition-colors">
                    Ver todos los eventos
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            @endif
            @else
            <div class="rounded-xl border border-neutral-100 bg-neutral-50 p-8 text-center">
                <p class="text-neutral-500 text-sm mb-2">No hay eventos destacados</p>
                <a href="{{ route('events.index') }}" class="text-[#00a650] font-semibold text-sm hover:underline">Ver todos los eventos</a>
            </div>
            @endif
        </div>

        {{-- Panel lateral (opcional, mismo que index actual) --}}
        @if(isset($withSidebar) && $withSidebar)
        <aside class="flex-shrink-0 w-full lg:w-64 xl:w-72">
            <div class="rounded-xl overflow-hidden bg-gradient-to-b from-slate-800 to-slate-900 text-white shadow-lg ring-1 ring-black/10 p-4">
                <p class="text-[10px] font-semibold text-[#00a650]/90 uppercase tracking-widest mb-2">LogicTicket Organizadores</p>
                <h3 class="text-sm font-bold leading-snug mb-2">¡Te ayudamos a crear y vender tu evento!</h3>
                <p class="text-white/70 text-xs leading-snug mb-3">Miles de organizadores venden con nosotros.</p>
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('events.create') }}" class="block w-full py-2.5 text-center bg-[#00a650] hover:bg-[#009345] text-white text-xs font-bold rounded-lg transition-colors">Crear evento</a>
                    @else
                        <a href="{{ route('register') }}?role=organizer" class="block w-full py-2.5 text-center bg-[#00a650] hover:bg-[#009345] text-white text-xs font-bold rounded-lg transition-colors">Ser organizador</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="block w-full py-2.5 text-center bg-[#00a650] hover:bg-[#009345] text-white text-xs font-bold rounded-lg transition-colors">Contáctanos</a>
                @endauth
                <div class="mt-3 pt-3 border-t border-white/10">
                    <p class="text-[10px] text-white/40 uppercase tracking-wider mb-2">Publicidad</p>
                    <a href="{{ route('events.index') }}" class="flex gap-2 p-2 rounded-lg bg-white/5 hover:bg-white/10 transition-colors">
                        <img src="https://picsum.photos/seed/side/80/56" alt="" class="w-14 h-10 rounded object-cover flex-shrink-0" loading="lazy" width="56" height="40">
                        <div class="min-w-0 flex-1">
                            <p class="text-white text-xs font-semibold truncate leading-tight">Eventos destacados</p>
                            <p class="text-white/50 text-[10px]">Ver planes</p>
                        </div>
                    </a>
                </div>
            </div>
        </aside>
        @endif
    </div>
</section>

@push('scripts')
@if($events->isNotEmpty())
<script>
(function() {
    var track = document.getElementById('featured-events-track');
    var prev = document.querySelector('[data-featured-prev]');
    var next = document.querySelector('[data-featured-next]');
    if (!track) return;
    var getStep = function() {
        var card = track.querySelector('.flex-shrink-0, [class*="snap-start"]');
        return card ? card.offsetWidth + 24 : 294;
    };
    if (prev) prev.addEventListener('click', function() { track.scrollBy({ left: -getStep(), behavior: 'smooth' }); });
    if (next) next.addEventListener('click', function() { track.scrollBy({ left: getStep(), behavior: 'smooth' }); });
})();
</script>
@endif
@endpush
