{{-- Panel "Nuestras Tendencias" - Slider automático con imágenes de eventos publicitarios --}}
@php
    $eventosPublicitarios = [
        [
            'imagen' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600',
            'titulo' => 'Conciertos en vivo',
            'link' => route('events.index', ['category_slug' => 'conciertos']),
        ],
        [
            'imagen' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600',
            'titulo' => 'Festivales y más',
            'link' => route('events.index'),
        ],
        [
            'imagen' => 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=600',
            'titulo' => 'Música y entretenimiento',
            'link' => route('events.index'),
        ],
        [
            'imagen' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600',
            'titulo' => 'Eventos deportivos',
            'link' => route('events.index', ['category_slug' => 'deportes']),
        ],
        [
            'imagen' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=600',
            'titulo' => 'Teatro y cultura',
            'link' => route('events.index', ['category_slug' => 'teatro']),
        ],
    ];
@endphp

<div class="tendencias-panel bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden" id="tendencias-panel">
    <div class="px-4 py-3 border-b border-slate-100">
        <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-600">Nuestras Tendencias</h3>
    </div>

    <div class="relative overflow-hidden min-h-[350px] sm:min-h-[390px]">
        @foreach($eventosPublicitarios as $i => $item)
            <div class="tendencias-slide absolute inset-0 transition-opacity duration-500 {{ $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-index="{{ $i }}">
                <a href="{{ $item['link'] ?? route('events.index') }}" class="block rounded-b-2xl overflow-hidden bg-slate-50 group">
                    <div class="h-[300px] sm:h-[340px] overflow-hidden">
                        <img loading="lazy" src="{{ $item['imagen'] }}" alt="{{ $item['titulo'] }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <p class="text-sm font-semibold text-slate-800 px-4 py-3 line-clamp-2 group-hover:text-[#00a650] transition-colors bg-white">{{ $item['titulo'] }}</p>
                </a>
            </div>
        @endforeach
    </div>

    @if(count($eventosPublicitarios) > 1)
    <div class="flex justify-center gap-1.5 py-3 px-2 border-t border-slate-100">
        @foreach($eventosPublicitarios as $i => $item)
            <button type="button" class="tendencias-dot w-2 h-2 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-[#00a650] w-5' : 'bg-slate-300 hover:bg-slate-400' }}" aria-label="Ver tendencia {{ $i + 1 }}" data-index="{{ $i }}"></button>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script>
(function() {
    var panel = document.getElementById('tendencias-panel');
    if (!panel) return;
    var slides = panel.querySelectorAll('.tendencias-slide');
    var dots = panel.querySelectorAll('.tendencias-dot');
    var total = slides.length;
    if (total <= 1) return;
    var current = 0;
    var intervalMs = 4000;
    var autoplayTimer = null;

    function updateDots() {
        dots.forEach(function(dot, i) {
            if (i === current) {
                dot.classList.add('bg-[#00a650]', 'w-5');
                dot.classList.remove('bg-slate-300');
            } else {
                dot.classList.remove('bg-[#00a650]', 'w-5');
                dot.classList.add('bg-slate-300');
            }
        });
    }
    function goTo(i) {
        slides[current].classList.remove('opacity-100', 'z-10');
        slides[current].classList.add('opacity-0', 'z-0');
        current = (i + total) % total;
        slides[current].classList.remove('opacity-0', 'z-0');
        slides[current].classList.add('opacity-100', 'z-10');
        updateDots();
        if (autoplayTimer) clearTimeout(autoplayTimer);
        autoplayTimer = setTimeout(function() { goTo(current + 1); }, intervalMs);
    }
    panel.addEventListener('mouseenter', function() {
        if (autoplayTimer) { clearTimeout(autoplayTimer); autoplayTimer = null; }
    });
    panel.addEventListener('mouseleave', function() {
        autoplayTimer = setTimeout(function() { goTo(current + 1); }, intervalMs);
    });
    dots.forEach(function(dot, i) {
        dot.addEventListener('click', function() { goTo(i); });
    });
    autoplayTimer = setTimeout(function() { goTo(current + 1); }, intervalMs);
})();
</script>
@endpush
