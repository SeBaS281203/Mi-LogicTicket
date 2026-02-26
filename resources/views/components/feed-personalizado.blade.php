<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10" aria-labelledby="feed-personalizado-heading">
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 shadow-xl border border-blue-500/20" style="box-shadow: 0 25px 50px -12px rgb(59 130 246 / 0.4);">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50 pointer-events-none"></div>
        <div class="relative flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-10 px-6 sm:px-10 lg:px-12 py-8 sm:py-10">
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/15 text-white/95 text-sm font-medium mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Personaliza tu experiencia
                </div>
                <h2 id="feed-personalizado-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-3 tracking-tight leading-tight">
                    Descubre eventos hechos para ti
                </h2>
                <p class="text-blue-100/90 text-base sm:text-lg max-w-xl leading-relaxed">
                    Inicia sesión y recibe recomendaciones personalizadas según tus gustos. No te pierdas nada.
                </p>
            </div>
            <div class="flex-shrink-0 flex flex-col items-center lg:items-end gap-2">
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 px-8 py-4 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg shadow-violet-500/30 hover:shadow-xl hover:shadow-violet-500/40 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0">
                        Iniciar sesión
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <p class="text-white/70 text-sm">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-white font-semibold hover:underline">Regístrate gratis</a></p>
                @else
                    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2.5 px-8 py-4 bg-white text-blue-700 font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                        Explorar eventos
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>
