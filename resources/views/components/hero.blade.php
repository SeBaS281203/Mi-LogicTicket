@props(['categories' => collect(), 'banners' => collect()])

<section class="relative bg-slate-100 overflow-hidden" aria-label="Destacados">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-10 pt-4 sm:pt-6 pb-6 sm:pb-10">
        <div id="hero-joinnus" class="relative w-full rounded-3xl overflow-hidden border border-slate-200/60 shadow-xl" style="min-height: 480px; height: 62vh; max-height: 700px; box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.12), 0 0 0 1px rgb(0 0 0 / 0.04);">
            @if($banners->isNotEmpty())
                @foreach($banners as $i => $banner)
                    @php
                        $bannerImg = $banner->image_url ?: 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1920';
                    @endphp
                    <div class="hero-joinnus-slide absolute inset-0 {{ $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-index="{{ $i }}">
                        <img src="{{ $bannerImg }}" alt="{{ $banner->title }}" class="hero-slide-img w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/70 via-slate-800/30 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="max-w-2xl px-8 sm:px-12 lg:px-16 py-10 sm:py-12 text-white">
                                <span class="inline-block px-4 py-1.5 rounded-full bg-violet-600 text-white text-xs font-bold uppercase tracking-wider mb-5 shadow-lg shadow-violet-500/30">Destacado</span>
                                <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold leading-[1.1] mb-4 tracking-tight" style="text-shadow: 0 2px 20px rgb(0 0 0 / 0.3);">{{ $banner->title }}</h2>
                                @if($banner->subtitle)
                                    <p class="text-white/90 text-lg sm:text-xl mb-8 max-w-lg leading-relaxed">{{ $banner->subtitle }}</p>
                                @endif
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" class="inline-flex items-center gap-2.5 px-7 py-3.5 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 hover:-translate-y-0.5 active:translate-y-0">
                                        {{ $banner->link_text ?: 'Ver más' }}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if($banners->isEmpty() || !auth()->check())
                <div class="hero-joinnus-slide absolute inset-0 {{ $banners->isEmpty() ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-index="{{ $banners->count() }}">
                    <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1920" alt="Eventos" class="hero-slide-img w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/70 via-slate-800/30 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center sm:justify-start">
                        <div class="max-w-2xl px-8 sm:px-12 lg:px-16 text-center sm:text-left text-white">
                            <span class="inline-block px-4 py-1.5 rounded-full bg-amber-500 text-white text-xs font-bold uppercase tracking-wider mb-5">Nuevo</span>
                            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4 tracking-tight" style="text-shadow: 0 2px 20px rgb(0 0 0 / 0.3);">Tu feed con eventos personalizados</h2>
                            <p class="text-white/90 text-lg sm:text-xl mb-8 max-w-md leading-relaxed">Inicia sesión y descubre recomendaciones hechas para ti.</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 px-7 py-3.5 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                Iniciar sesión
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if($banners->isEmpty() && auth()->check())
                <div class="hero-joinnus-slide absolute inset-0 opacity-100 z-10" data-index="{{ $banners->count() }}">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920" alt="Eventos" class="hero-slide-img w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/70 via-slate-800/30 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center sm:justify-start">
                        <div class="max-w-2xl px-8 sm:px-12 lg:px-16 text-center sm:text-left text-white">
                            <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight mb-4 tracking-tight" style="text-shadow: 0 2px 20px rgb(0 0 0 / 0.3);">Encuentra tu próximo evento</h2>
                            <p class="text-white/90 text-lg sm:text-xl max-w-lg leading-relaxed">Conciertos, deportes, teatro y más. Compra entradas de forma segura.</p>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $totalSlides = $banners->isNotEmpty()
                    ? $banners->count() + (!auth()->check() ? 1 : 0)
                    : (auth()->check() ? 2 : 1);
            @endphp
            @if($totalSlides > 1)
                <button type="button" id="hero-joinnus-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/15 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white/50 border border-white/20" aria-label="Anterior">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="hero-joinnus-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/15 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white/50 border border-white/20" aria-label="Siguiente">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div id="hero-joinnus-dots" class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2.5"></div>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
.hero-joinnus-slide { transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
.hero-slide-img { transition: transform 8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.hero-joinnus-slide.opacity-100 .hero-slide-img { animation: hero-zoom 8s ease-out forwards; }
@keyframes hero-zoom {
    from { transform: scale(1.05); }
    to { transform: scale(1); }
}
#hero-joinnus-dots button { width: 10px; height: 10px; border-radius: 50%; transition: all 0.3s ease; }
#hero-joinnus-dots button.active { background-color: #7c3aed; transform: scale(1.3); box-shadow: 0 0 0 2px rgba(255,255,255,0.5); }
#hero-joinnus-dots button:not(.active) { background-color: rgba(255,255,255,0.4); }
#hero-joinnus-dots button:hover:not(.active) { background-color: rgba(255,255,255,0.7); }
</style>
@endpush
@push('scripts')
<script>
(function() {
    function initHeroJoinnus() {
        var container = document.getElementById('hero-joinnus');
        if (!container) return;
        var slides = container.querySelectorAll('.hero-joinnus-slide');
        var total = slides.length;
        if (total <= 1) return;
        var dotsEl = document.getElementById('hero-joinnus-dots');
        var prevBtn = document.getElementById('hero-joinnus-prev');
        var nextBtn = document.getElementById('hero-joinnus-next');
        var current = 0;
        var autoplayMs = 3000;
        var autoplayTimer = null;
        function updateDots() {
            if (!dotsEl) return;
            dotsEl.querySelectorAll('button').forEach(function(btn, i) { btn.classList.toggle('active', i === current); });
        }
        function goTo(i) {
            slides[current].classList.remove('opacity-100', 'z-10');
            slides[current].classList.add('opacity-0', 'z-0');
            current = (i + total) % total;
            slides[current].classList.remove('opacity-0', 'z-0');
            slides[current].classList.add('opacity-100', 'z-10');
            updateDots();
            resetAutoplay();
        }
        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }
        function resetAutoplay() {
            if (autoplayTimer) clearTimeout(autoplayTimer);
            autoplayTimer = setTimeout(next, autoplayMs);
        }
        container.addEventListener('mouseenter', function() { if (autoplayTimer) { clearTimeout(autoplayTimer); autoplayTimer = null; } });
        container.addEventListener('mouseleave', resetAutoplay);
        if (dotsEl) {
            for (var i = 0; i < total; i++) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = i === 0 ? 'active' : '';
                btn.setAttribute('aria-label', 'Ir a slide ' + (i + 1));
                btn.setAttribute('data-index', i);
                btn.addEventListener('click', function() { goTo(parseInt(this.getAttribute('data-index'), 10)); });
                dotsEl.appendChild(btn);
            }
        }
        if (prevBtn) prevBtn.addEventListener('click', prev);
        if (nextBtn) nextBtn.addEventListener('click', next);
        resetAutoplay();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initHeroJoinnus);
    else initHeroJoinnus();
})();
</script>
@endpush
