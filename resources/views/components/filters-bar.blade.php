@props(['categories'])

<form method="GET" action="{{ route('events.index') }}" class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 sm:p-5">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-end">
        <div class="flex-1 min-w-0">
            <label for="filter-q" class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1.5">Buscar</label>
            <input type="text" name="q" id="filter-q" value="{{ request('q') }}" placeholder="Nombre del evento"
                class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20 transition-colors text-sm">
        </div>
        <div class="w-full sm:w-44">
            <label for="filter-city" class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1.5">Ciudad</label>
            <input type="text" name="city" id="filter-city" value="{{ request('city') }}" placeholder="Ej. Lima"
                class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20 transition-colors text-sm">
        </div>
        <div class="w-full sm:w-44">
            <label for="filter-date" class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1.5">Fecha</label>
            <input type="date" name="date" id="filter-date" value="{{ request('date') }}"
                class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20 transition-colors text-sm">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-3 bg-[#00a650] hover:bg-[#009345] text-white font-semibold rounded-xl transition-colors shadow-sm text-sm">
                Filtrar
            </button>
            <a href="{{ route('events.index') }}" class="px-5 py-3 border border-neutral-200 rounded-xl font-medium text-neutral-600 hover:bg-neutral-50 transition-colors text-sm">
                Limpiar
            </a>
        </div>
    </div>
</form>
