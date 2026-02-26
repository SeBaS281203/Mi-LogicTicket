@props(['events', 'title' => 'Imperdibles', 'sectionSubtitle' => 'Planes', 'seeAllUrl' => null])

<section class="bg-white rounded-2xl border border-slate-100 shadow-[0_1px_3px_0_rgb(0_0_0_/_.05)] overflow-hidden p-6 sm:p-8 lg:p-10" aria-labelledby="featured-events-heading">
    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-4 mb-8">
                <h2 id="featured-events-heading">
                    <span class="block text-sm font-extrabold uppercase tracking-widest text-[#00a650] mb-2">{{ $sectionSubtitle }}</span>
                    <span class="block text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 drop-shadow-sm" style="text-shadow: 0 1px 2px rgba(0,0,0,0.05);">{{ $title }}</span>
                </h2>
                @if($events->isNotEmpty())
                <div class="flex items-center gap-2 flex-shrink-0 md:hidden">
                    <button type="button" data-featured-prev class="w-10 h-10 rounded-full border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-all duration-200 shadow-sm hover:shadow" aria-label="Anterior">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" data-featured-next class="w-10 h-10 rounded-full border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-all duration-200 shadow-sm hover:shadow" aria-label="Siguiente">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @endif
            </div>

            @if($events->isNotEmpty())
            <div id="featured-events-track" class="flex md:grid md:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 overflow-x-auto md:overflow-visible pb-2 md:pb-0 snap-x snap-mandatory scroll-smooth scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($events as $event)
                    <div class="flex-shrink-0 w-[280px] sm:w-[300px] md:flex-shrink md:w-auto snap-start min-w-0 animate-slide-up" style="animation-delay: {{ $loop->index * 50 }}ms;">
                        @include('components.event-card-featured', ['event' => $event])
                    </div>
                @endforeach
            </div>
            @else
            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-14 text-center">
                <p class="text-slate-500 text-sm mb-5">No hay eventos destacados</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#00a650] hover:bg-[#008f42] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">Ver eventos</a>
            </div>
            @endif
        </div>
        @if(isset($withSidebar) && $withSidebar)
        <aside class="flex-shrink-0 w-full lg:w-64 xl:w-72">
            <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 text-white p-6 shadow-lg">
                <p class="section-subtitle text-white/90 mb-2">Organizadores</p>
                <h3 class="text-lg font-bold leading-snug mb-2">¡Crea y vende tu evento!</h3>
                <p class="text-white/70 text-sm leading-relaxed mb-6">Miles de organizadores confían en nosotros.</p>
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('events.create') }}" class="block w-full py-3.5 text-center bg-[#00a650] hover:bg-[#008f42] text-white font-bold rounded-xl transition-all duration-300 text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5">Crear evento</a>
                    @else
                        <a href="{{ route('register') }}?role=organizer" class="block w-full py-3.5 text-center bg-[#00a650] hover:bg-[#008f42] text-white font-bold rounded-xl transition-all duration-300 text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5">Ser organizador</a>
                    @endif
                @else
                    <a href="{{ route('register') }}?role=organizer" class="block w-full py-3.5 text-center bg-[#00a650] hover:bg-[#008f42] text-white font-bold rounded-xl transition-all duration-300 text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5">Registrarme</a>
                @endauth
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
        var card = track.querySelector('.flex-shrink-0');
        return card ? card.offsetWidth + 24 : 304;
    };
    if (prev) prev.addEventListener('click', function() { track.scrollBy({ left: -getStep(), behavior: 'smooth' }); });
    if (next) next.addEventListener('click', function() { track.scrollBy({ left: getStep(), behavior: 'smooth' }); });
})();
</script>
@endif
@endpush
