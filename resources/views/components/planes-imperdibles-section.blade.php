@props(['events' => collect(), 'tendencias' => collect(), 'categories' => collect()])

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10" aria-labelledby="planes-imperdibles-heading">
    <div class="flex flex-col lg:flex-row lg:items-start gap-6 lg:gap-8">
        {{-- Columna principal: Planes Imperdibles --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 lg:p-8">
                <div class="mb-6">
                    <span class="block text-sm font-extrabold uppercase tracking-widest text-[#00a650] mb-2">Planes</span>
                    <h2 id="planes-imperdibles-heading" class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight drop-shadow-sm" style="text-shadow: 0 1px 2px rgba(0,0,0,0.05);">Imperdibles</h2>
                    <p class="mt-2 text-slate-500 text-sm sm:text-base">Los mejores eventos para no perderse</p>
                </div>

                @if($events->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                        @foreach($events->take(8) as $event)
                            <div class="animate-slide-up" style="animation-delay: {{ min($loop->index * 50, 200) }}ms;">
                                <x-event-card-planes :event="$event" />
                            </div>
                        @endforeach
                        {{-- Card CTA para rellenar espacio y evitar huecos en blanco --}}
                        <a href="{{ route('events.index') }}" class="group flex flex-col items-center justify-center min-h-[320px] rounded-xl border-2 border-dashed border-slate-200 hover:border-[#00a650]/50 bg-slate-50/80 hover:bg-[#00a650]/5 transition-all duration-300 p-6 text-center">
                            <span class="w-14 h-14 rounded-full bg-[#00a650]/10 flex items-center justify-center mb-4 group-hover:bg-[#00a650]/20 transition-colors">
                                <svg class="w-7 h-7 text-[#00a650]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </span>
                            <span class="font-semibold text-slate-700 group-hover:text-violet-600 transition-colors block mb-1">Ver más eventos</span>
                            <span class="text-sm text-slate-500">Explorar todos</span>
                        </a>
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-12 text-center">
                        <p class="text-slate-500 text-sm mb-4">No hay planes destacados</p>
                        <a href="{{ route('events.index') }}" class="text-violet-600 font-semibold text-sm hover:text-violet-700 transition-colors">Ver todos los eventos</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar derecha --}}
        <div class="lg:w-72 xl:w-80 flex-shrink-0">
            <div class="lg:sticky lg:top-24 space-y-6">
                <x-sidebar-joinnus :tendencias="$tendencias" :categories="$categories" />
            </div>
        </div>
    </div>
</section>
