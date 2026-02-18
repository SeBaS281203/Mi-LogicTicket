{{-- CTA Organizadores: sección llamada a la acción para crear/vender eventos. --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-labelledby="cta-organizers-heading">
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-8 sm:p-10 lg:p-12 text-center">
        <h2 id="cta-organizers-heading" class="text-xl sm:text-2xl font-bold text-neutral-900 mb-2">
            ¿Organizas eventos?
        </h2>
        <p class="text-neutral-600 max-w-xl mx-auto mb-6">
            Publica tu evento en LogicTicket y vende entradas de forma segura. Miles de organizadores ya confían en nosotros.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            @auth
                @if(auth()->user()->isOrganizer())
                    <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#00a650] text-white font-bold rounded-xl hover:bg-[#009345] shadow-sm hover:shadow transition-all">
                        Crear evento
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}?role=organizer" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#00a650] text-white font-bold rounded-xl hover:bg-[#009345] shadow-sm hover:shadow transition-all">
                        Ser organizador
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}?role=organizer" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#00a650] text-white font-bold rounded-xl hover:bg-[#009345] shadow-sm hover:shadow transition-all">
                    Ser organizador
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            @endauth
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white border border-neutral-200 text-neutral-700 font-semibold rounded-xl hover:bg-neutral-50 transition-all">
                Ver eventos
            </a>
        </div>
    </div>
</section>
