@props(['categories' => collect()])

<aside class="w-full lg:w-72 xl:w-80 flex-shrink-0" aria-label="Tendencias">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_1px_3px_0_rgb(0_0_0_/_.05)] p-5 lg:p-6 sticky top-24">
        <h3 class="section-subtitle mb-5">Tendencias</h3>
        <ul class="space-y-1">
            @foreach($categories->take(6) as $cat)
                <li>
                    <a href="{{ route('events.index', ['category_slug' => $cat->slug]) }}" class="flex items-center gap-3 py-3 px-3 rounded-xl text-slate-700 hover:bg-[#00a650]/5 hover:text-[#00a650] transition-colors duration-200 text-sm font-medium">
                        <span class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </span>
                        {{ $cat->name }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="mt-5 pt-5 border-t border-slate-100">
            <a href="{{ route('events.index') }}" class="flex items-center gap-2 text-sm font-semibold text-[#00a650] hover:text-[#008f42] transition-colors duration-200">
                Ver todas las categorías
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</aside>
