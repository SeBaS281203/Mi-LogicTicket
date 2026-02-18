@props(['categories', 'currentCategory' => null])

@if($categories->isNotEmpty())
<nav class="border-b border-neutral-200 bg-white sticky top-[4rem] z-40 shadow-sm" aria-label="Categorías de eventos">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex gap-2 overflow-x-auto py-3 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            <a href="{{ route('events.index', request()->except('category')) }}"
                class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('category') ? 'bg-teal-600 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}">
                Todos
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('events.index', ['category' => $cat->id] + request()->except('category')) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('category') == $cat->id ? 'bg-teal-600 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
@endif
