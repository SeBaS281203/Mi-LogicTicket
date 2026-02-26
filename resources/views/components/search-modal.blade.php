@props(['categories' => collect()])

<div
    x-show="$store.search.open"
    x-cloak
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="$store.search.open = false"
    x-effect="document.body.style.overflow = $store.search.open ? 'hidden' : ''"
    class="fixed inset-0 z-[90] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="search-modal-title"
>
    {{-- Overlay --}}
    <div
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        @click="$store.search.open = false"
    ></div>

    {{-- Panel del buscador --}}
    <div
        x-show="$store.search.open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden"
        @click.stop
    >
        <div class="p-6 sm:p-8">
            <h2 id="search-modal-title" class="sr-only">Buscador avanzado de eventos</h2>

            <form method="GET" action="{{ route('events.index') }}" id="search-modal-form" class="space-y-5">
                {{-- Input principal con lupa --}}
                <div>
                    <label for="search-modal-q" class="sr-only">Buscar por eventos o artistas</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input
                            type="text"
                            name="q"
                            id="search-modal-q"
                            value="{{ request('q') }}"
                            placeholder="Buscar por eventos o artistas"
                            autocomplete="off"
                            class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00a650]/30 focus:border-[#00a650] transition-all text-sm"
                        />
                    </div>
                </div>

                {{-- Filtros tipo botones / inputs --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="search-price" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Precio (S/)</label>
                        <select name="price_max" id="search-price" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#00a650]/20 focus:border-[#00a650] transition-all">
                            <option value="">Cualquier precio</option>
                            <option value="50" {{ request('price_max') == '50' ? 'selected' : '' }}>Hasta S/ 50</option>
                            <option value="100" {{ request('price_max') == '100' ? 'selected' : '' }}>Hasta S/ 100</option>
                            <option value="200" {{ request('price_max') == '200' ? 'selected' : '' }}>Hasta S/ 200</option>
                            <option value="500" {{ request('price_max') == '500' ? 'selected' : '' }}>Hasta S/ 500</option>
                        </select>
                    </div>
                    <div>
                        <label for="search-category" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Categorías</label>
                        <select name="category" id="search-category" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#00a650]/20 focus:border-[#00a650] transition-all">
                            <option value="">Todas</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="search-date" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Fechas</label>
                        <input type="date" name="date" id="search-date" value="{{ request('date') }}"
                            class="w-full h-10 px-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#00a650]/20 focus:border-[#00a650] transition-all" />
                    </div>
                    <div>
                        <label for="search-city" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Ubicación</label>
                        <input type="text" name="city" id="search-city" value="{{ request('city') }}" placeholder="Ciudad"
                            class="w-full h-10 px-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#00a650]/20 focus:border-[#00a650] placeholder-slate-400" />
                    </div>
                    <div>
                        <label for="search-venue" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Local</label>
                        <input type="text" name="venue" id="search-venue" value="{{ request('venue') }}" placeholder="Nombre del lugar"
                            class="w-full h-10 px-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#00a650]/20 focus:border-[#00a650] placeholder-slate-400" />
                    </div>
                </div>

                {{-- Botones inferiores --}}
                <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end pt-2">
                    <button
                        type="button"
                        @click="$store.search.open = false"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#00a650] text-white font-bold hover:bg-[#009345] transition-colors text-sm shadow-md"
                    >
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
