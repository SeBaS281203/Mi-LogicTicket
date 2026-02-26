<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-labelledby="cta-organizers-heading">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_1px_3px_0_rgb(0_0_0_/_.05)] p-10 sm:p-12 lg:p-16 text-center overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-br from-violet-600/5 via-transparent to-transparent pointer-events-none"></div>
        <div class="relative">
            <h2 id="cta-organizers-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-4 tracking-tight">
                ¿Organizas eventos?
            </h2>
            <p class="text-slate-600 max-w-xl mx-auto mb-10 text-base sm:text-lg leading-relaxed">
                Publica tu evento en LogicTicket y vende entradas de forma segura. Miles de organizadores confían en nosotros.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2.5 px-7 py-4 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                            Crear evento
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}?role=organizer" class="inline-flex items-center gap-2.5 px-7 py-4 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                            Ser organizador
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}?role=organizer" class="inline-flex items-center gap-2.5 px-7 py-4 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                        Ser organizador
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endauth
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2.5 px-7 py-4 bg-white border-2 border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 shadow-sm">
                    Ver eventos
                </a>
            </div>
        </div>
    </div>
</section>
