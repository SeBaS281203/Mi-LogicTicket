{{--
  Hero principal tipo Joinnus - Slider ancho completo
  Especificaciones: altura 480–520px, radius 20px, botones 44px, indicadores 8px, autoplay
  Container max 1280px · Fondo #F5F7FA · Verde corporativo #00a650
--}}
@props(['categories' => collect(), 'banners' => collect()])

<section class="bg-[#F5F7FA]" aria-label="Destacados">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div id="hero-joinnus" class="relative w-full rounded-[20px] overflow-hidden shadow-lg transition-shadow duration-300 hover:shadow-xl" style="height: 480px;">
            {{-- Slides --}}
            @if($banners->isNotEmpty())
                @foreach($banners as $i => $banner)
                    @php
                        $bannerImg = $banner->image
                            ? (str_starts_with($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image))
                            : 'https://picsum.photos/seed/banner-' . $i . '/1920/1080';
                    @endphp
                    <div class="hero-joinnus-slide absolute inset-0 {{ $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-index="{{ $i }}">
                        <img src="{{ $bannerImg }}" alt="{{ $banner->title }}" class="w-full h-full object-cover transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/35 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="max-w-xl px-8 sm:px-12 py-10 text-white">
                                <span class="inline-block px-3 py-1 rounded-full bg-[#00a650]/90 text-white text-xs font-bold uppercase tracking-wider mb-4">Destacado</span>
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold leading-tight mb-3">{{ $banner->title }}</h2>
                                @if($banner->subtitle)<p class="text-white/90 text-base sm:text-lg mb-6">{{ $banner->subtitle }}</p>@endif
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#00a650] hover:bg-[#009345] text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">{{ $banner->link_text ?: 'Ver más' }}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Slide invitado / sin banners --}}
            @if($banners->isEmpty() || !auth()->check())
                <div class="hero-joinnus-slide absolute inset-0 {{ $banners->isEmpty() ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-index="{{ $banners->count() }}">
                    <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1920" alt="Eventos" class="w-full h-full object-cover transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/65 via-black/30 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center sm:justify-start">
                        <div class="max-w-xl px-8 sm:px-12 text-center sm:text-left text-white">
                            <span class="inline-block px-3 py-1 rounded-full bg-amber-500/80 text-white text-xs font-bold uppercase tracking-wider mb-4">Nuevo</span>
                            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-3">Tu feed con eventos personalizados</h2>
                            <p class="text-white/90 text-lg mb-6">Inicia sesión y descubre recomendaciones hechas para ti.</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#00a650] hover:bg-[#009345] text-white font-bold rounded-xl transition-all duration-300 shadow-lg">Iniciar sesión
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if($banners->isEmpty() && auth()->check())
                <div class="hero-joinnus-slide absolute inset-0 opacity-100 z-10" data-index="{{ $banners->count() }}">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920" alt="Eventos" class="w-full h-full object-cover transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/25 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center sm:justify-start">
                        <div class="max-w-xl px-8 sm:px-12 text-white text-center sm:text-left">
                            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-3">Encuentra tu próximo evento</h2>
                            <p class="text-white/90 text-lg">Conciertos, deportes, teatro y más. Compra entradas seguro.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Navegación: botones circulares 44px --}}
            @php
                $totalSlides = $banners->isNotEmpty()
                    ? $banners->count() + (!auth()->check() ? 1 : 0)
                    : (auth()->check() ? 2 : 1);
            @endphp
            @if($totalSlides > 1)
                <button type="button" id="hero-joinnus-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/20 hover:bg-white/35 backdrop-blur-sm text-white flex items-center justify-center transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Anterior">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="hero-joinnus-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/20 hover:bg-white/35 backdrop-blur-sm text-white flex items-center justify-center transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Siguiente">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Indicadores: 8px, activo verde #00a650 --}}
                <div id="hero-joinnus-dots" class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2"></div>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
#hero-joinnus { min-height: 480px; }
@media (min-width: 640px) { #hero-joinnus { height: 500px; } }
@media (min-width: 1024px) { #hero-joinnus { height: 520px; } }
.hero-joinnus-slide { transition: opacity 0.5s ease; }
#hero-joinnus-dots button { width: 8px; height: 8px; border-radius: 50%; transition: background-color 0.3s ease, transform 0.2s ease; }
#hero-joinnus-dots button.active { background-color: #00a650; transform: scale(1.2); }
#hero-joinnus-dots button:not(.active) { background-color: rgba(255,255,255,0.4); }
#hero-joinnus-dots button:hover { background-color: rgba(255,255,255,0.7); }
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
        var autoplayMs = 5500;
        var autoplayTimer = null;

        function updateDots() {
            if (!dotsEl) return;
            dotsEl.querySelectorAll('button').forEach(function(btn, i) {
                btn.classList.toggle('active', i === current);
            });
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
        function pauseAutoplay() {
            if (autoplayTimer) { clearTimeout(autoplayTimer); autoplayTimer = null; }
        }

        container.addEventListener('mouseenter', pauseAutoplay);
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroJoinnus);
    } else {
        initHeroJoinnus();
    }
})();
</script>
@endpush
