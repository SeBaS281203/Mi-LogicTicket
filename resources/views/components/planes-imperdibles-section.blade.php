{{-- Planes Imperdibles: grid de eventos + columna Tendencias en desktop. Card blanca, sombra suave. --}}
@props(['events' => collect(), 'categories' => collect()])

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-labelledby="planes-imperdibles-heading">
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 lg:p-8 flex flex-col lg:flex-row gap-6 lg:gap-8">
        <div class="flex-1 min-w-0">
            <h2 id="planes-imperdibles-heading" class="text-xl font-bold text-neutral-900 tracking-tight mb-4">
                <span class="block text-[#00a650] text-sm uppercase tracking-wider">Planes</span>
                <span class="block text-neutral-900 leading-tight uppercase">Imperdibles</span>
            </h2>
            @if($events->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events->take(6) as $event)
                    <div class="min-w-0">
                        @include('components.event-card-featured', ['event' => $event])
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#00a650] hover:text-[#009345] transition-colors">
                    Ver todos los eventos
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            @else
            <div class="rounded-xl border border-neutral-100 bg-neutral-50 p-8 text-center">
                <p class="text-neutral-500 text-sm mb-2">No hay planes destacados</p>
                <a href="{{ route('events.index') }}" class="text-[#00a650] font-semibold text-sm hover:underline">Ver todos los eventos</a>
            </div>
            @endif
        </div>
        <div class="lg:w-auto">
            @include('components.trends-sidebar', ['categories' => $categories])
        </div>
    </div>
</section>
