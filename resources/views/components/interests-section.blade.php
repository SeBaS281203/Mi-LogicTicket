@props(['categories' => collect()])

@php
    $iconMap = [
        'conciertos' => 'music',
        'deportes' => 'sport',
        'teatro' => 'theater',
        'conferencias' => 'course',
        'fiestas' => 'party',
        'gastronomia' => 'food',
    ];
    $visible = $categories->take(8);
@endphp

<section class="bg-white py-10 sm:py-12" aria-labelledby="descubre-intereses">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 id="descubre-intereses" class="text-center mb-6 sm:mb-8">
            <span class="section-subtitle block font-extrabold">Descubre tus</span>
            <span class="section-title block text-2xl sm:text-3xl lg:text-4xl text-slate-900 mt-2 font-extrabold">Intereses</span>
        </h2>

        <div class="flex flex-wrap justify-center gap-5 sm:gap-6 lg:gap-8">
            @foreach($visible as $cat)
                <a href="{{ route('events.index', ['category_slug' => $cat->slug]) }}" class="flex flex-col items-center group group/cat">
                    <span class="w-[100px] h-[100px] sm:w-[115px] sm:h-[115px] rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 transition-all duration-300 ease-out group-hover/cat:bg-violet-500/10 group-hover/cat:text-violet-600 group-hover/cat:shadow-[0_8px_30px_-12px_rgb(124_58_237_/_.25)] group-hover/cat:-translate-y-1 group-hover/cat:scale-105 border border-slate-100">
                        @switch($iconMap[$cat->slug] ?? 'event')
                            @case('music')
                                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                @break
                            @case('sport')
                                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @break
                            @case('theater')
                                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                @break
                            @case('course')
                                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                @break
                            @case('party')
                                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                @break
                            @case('food')
                                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                @break
                            @default
                                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        @endswitch
                    </span>
                    <span class="mt-2.5 text-sm font-medium text-slate-600 group-hover/cat:text-violet-600 transition-colors duration-300 text-center max-w-[110px]">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-violet-600 text-white font-semibold rounded-xl hover:bg-violet-700 transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:ring-offset-2">
                Ver todas las categorías
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>
</section>
