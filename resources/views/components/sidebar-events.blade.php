@props([])

<aside class="space-y-3 flex-shrink-0 w-full lg:w-80">
    {{-- Panel Organizadores (desplegable) --}}
    <div class="sidebar-events-panel rounded-2xl overflow-hidden bg-gradient-to-b from-slate-800 to-slate-900 text-white shadow-xl ring-1 ring-black/5">
        <button type="button" class="sidebar-events-trigger w-full text-left p-5 sm:p-6 flex items-center justify-between gap-3" aria-expanded="true" data-panel="org">
            <div class="flex-1 min-w-0">
                <span class="text-xs font-semibold text-teal-300 uppercase tracking-wider">LogicTicket Organizadores</span>
                <h3 class="text-lg font-bold mt-1 leading-tight">¡Te ayudamos a crear y vender tu evento!</h3>
            </div>
            <span class="sidebar-events-chevron w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white transition-transform duration-300 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </span>
        </button>
        <div class="sidebar-events-content overflow-hidden transition-all duration-300 ease-out" data-panel="org">
            <div class="px-5 sm:px-6 pb-5 sm:pb-6 pt-0 space-y-4">
                <p class="text-white/80 text-sm leading-relaxed">Miles de organizadores venden con nosotros. Publica tu evento, define precios y recibe pagos de forma segura.</p>
                <ul class="text-white/70 text-sm space-y-2">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Creación de eventos en minutos</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Reportes y estadísticas</li>
                </ul>
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('events.create') }}" class="block w-full py-3 text-center bg-teal-500 hover:bg-teal-600 font-semibold rounded-xl transition-colors">Crear evento</a>
                    @else
                        <a href="{{ route('register') }}?role=organizer" class="block w-full py-3 text-center bg-teal-500 hover:bg-teal-600 font-semibold rounded-xl transition-colors">Ser organizador</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="block w-full py-3 text-center bg-teal-500 hover:bg-teal-600 font-semibold rounded-xl transition-colors">Contáctanos</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Panel Publicidad / Tendencias (desplegable) --}}
    <div class="sidebar-events-panel rounded-2xl overflow-hidden bg-gradient-to-b from-slate-800 to-slate-900 text-white shadow-xl ring-1 ring-black/5">
        <button type="button" class="sidebar-events-trigger w-full text-left p-5 sm:p-6 flex items-center justify-between gap-3" aria-expanded="false" data-panel="pub">
            <div class="flex-1 min-w-0">
                <span class="text-xs font-semibold text-teal-300 uppercase tracking-wider">Publicidad</span>
                <h3 class="text-lg font-bold mt-1 leading-tight">Eventos destacados</h3>
            </div>
            <span class="sidebar-events-chevron w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white transition-transform duration-300 rotate-180 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </span>
        </button>
        <div class="sidebar-events-content overflow-hidden transition-all duration-300 ease-out max-h-0" data-panel="pub" style="max-height: 0;">
            <div class="px-5 sm:px-6 pb-5 sm:pb-6 pt-0 space-y-4">
                <p class="text-white/80 text-sm leading-relaxed">Planes imperdibles y ofertas de temporada. No te pierdas los eventos más populares.</p>
                <a href="{{ route('events.index') }}" class="flex gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                    <img src="https://picsum.photos/seed/sidebar/120/80" alt="" class="w-16 h-12 rounded-lg object-cover flex-shrink-0">
                    <div class="min-w-0 flex-1">
                        <p class="text-white text-sm font-semibold truncate">Ver todos los eventos</p>
                        <p class="text-white/60 text-xs">Conciertos, deportes, teatro y más</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</aside>

@push('styles')
<style>
.sidebar-events-content { max-height: 32rem; transition: max-height 0.35s ease-out; }
.sidebar-events-trigger[aria-expanded="false"] + .sidebar-events-content { max-height: 0 !important; }
.sidebar-events-trigger[aria-expanded="false"] .sidebar-events-chevron { transform: rotate(180deg); }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.sidebar-events-trigger').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            var chevron = this.querySelector('.sidebar-events-chevron');
            if (chevron) chevron.style.transform = expanded ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    });
});
</script>
@endpush
