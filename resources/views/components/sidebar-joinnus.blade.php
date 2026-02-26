@props(['tendencias' => collect(), 'categories' => collect()])

<aside class="w-full lg:w-72 xl:w-80 flex-shrink-0 space-y-5" aria-label="Sidebar">
    {{-- Nuestras Tendencias - Eventos publicitarios con imágenes --}}
    @include('components.tendencias-panel')

    {{-- Banner publicitario --}}
    <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700/50 shadow-lg">
        <div class="p-6 text-center">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Patrocinado</p>
            <div class="h-32 rounded-xl bg-slate-700/50 flex items-center justify-center mb-4">
                <span class="text-slate-500 text-sm">Tu anuncio aquí</span>
            </div>
            <a href="#" class="inline-block text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors">Más información</a>
        </div>
    </div>

    {{-- Organizadores --}}
    <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 text-white p-6 shadow-lg border border-slate-700/50">
        <h3 class="text-sm font-bold uppercase tracking-wider text-white/90 mb-1">Organizadores</h3>
        <h4 class="text-lg font-bold leading-snug mb-2">¿Creas o organizas eventos?</h4>
        <p class="text-white/70 text-sm leading-relaxed mb-5">Publica tu evento y vende entradas de forma segura. Miles de organizadores confían en ChiclayoTicket.</p>
        @auth
            @if(auth()->user()->isOrganizer())
                <a href="{{ route('events.create') }}" class="block w-full py-3.5 text-center bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm">Crear evento</a>
            @else
                <a href="{{ route('register') }}?role=organizer" class="block w-full py-3.5 text-center bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm">Ser organizador</a>
            @endif
        @else
            <a href="{{ route('register') }}?role=organizer" class="block w-full py-3.5 text-center bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm">Registrarme como organizador</a>
        @endauth
    </div>

    {{-- Blog mini carrusel --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Lo más leído</h3>
            <a href="#" class="text-xs font-semibold text-violet-600 hover:text-violet-700 transition-colors">Ver blog</a>
        </div>
        <div id="blog-mini-track" class="p-4 overflow-x-auto scrollbar-hide snap-x snap-mandatory flex gap-4">
            @foreach([
                ['titulo' => '10 conciertos que no te puedes perder', 'fecha' => 'Hace 2 días'],
                ['titulo' => 'Cómo elegir el mejor asiento', 'fecha' => 'Hace 5 días'],
                ['titulo' => 'Guía de festivales 2025', 'fecha' => 'Hace 1 semana'],
            ] as $post)
                <a href="#" class="flex-shrink-0 w-64 snap-start block p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="h-24 rounded-lg bg-slate-100 mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m4-4h-4m-4 0H7"/></svg>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900 line-clamp-2 group-hover:text-violet-600 transition-colors">{{ $post['titulo'] }}</h4>
                    <p class="text-xs text-slate-500 mt-1">{{ $post['fecha'] }}</p>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Botón verde principal --}}
    <a href="{{ route('events.index') }}" class="block w-full py-4 text-center bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg shadow-[#00a650]/25 hover:shadow-xl hover:shadow-[#00a650]/30 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 text-base">
        Ver todos los eventos
    </a>
</aside>
