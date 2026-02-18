@props(['events', 'title' => 'Eventos destacados', 'seeAllUrl' => null])

@if($events->isNotEmpty())
<section class="py-10 lg:py-14" aria-labelledby="carousel-{{ Str::slug($title) }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 id="carousel-{{ Str::slug($title) }}" class="text-2xl font-bold text-neutral-900">{{ $title }}</h2>
            @if($seeAllUrl)
                <a href="{{ $seeAllUrl }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700 flex items-center gap-1 transition-colors">
                    Ver todos <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>
        <div class="relative -mx-4 sm:mx-0">
            <div class="flex gap-4 overflow-x-auto pb-4 px-4 sm:px-0 snap-x snap-mandatory scroll-smooth scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($events as $event)
                    <div class="flex-shrink-0 w-[280px] sm:w-[300px] snap-start">
                        @include('components.event-card', ['event' => $event])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
