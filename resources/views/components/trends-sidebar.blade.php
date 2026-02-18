{{-- Tendencias: columna lateral desktop. Card blanca, sombra suave. --}}
@props(['categories' => collect()])

<aside class="w-full lg:w-72 xl:w-80 flex-shrink-0" aria-label="Tendencias">
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-5 lg:p-6 sticky top-24">
        <h3 class="text-sm font-bold text-[#00a650] uppercase tracking-wider mb-4">Tendencias</h3>
        <ul class="space-y-2">
            @foreach($categories->take(6) as $cat)
                <li>
                    <a href="{{ route('events.index', ['category_slug' => $cat->slug]) }}" class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-neutral-700 hover:bg-[#00a650]/5 hover:text-[#00a650] transition-colors text-sm font-medium">
                        <span class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-neutral-500 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </span>
                        {{ $cat->name }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="mt-4 pt-4 border-t border-neutral-100">
            <a href="{{ route('events.index') }}" class="flex items-center gap-2 text-sm font-semibold text-[#00a650] hover:text-[#009345] transition-colors">
                Ver todas las categorías
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</aside>
