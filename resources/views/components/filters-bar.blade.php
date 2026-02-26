@props(['categories'])

<form method="GET" action="{{ route('events.index') }}" class="bg-white rounded-2xl border border-slate-100 shadow-[0_1px_3px_0_rgb(0_0_0_/_.05)] p-4 sm:p-6">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-end">
        <div class="flex-1 min-w-0">
            <label for="filter-q" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar</label>
            <input type="text" name="q" id="filter-q" value="{{ request('q') }}" placeholder="Nombre del evento o artista"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/15 transition-all text-sm">
        </div>
        <div class="w-full sm:w-40">
            <label for="filter-city" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ciudad</label>
            <input type="text" name="city" id="filter-city" value="{{ request('city') }}" placeholder="Ej. Lima"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/15 transition-all text-sm">
        </div>
        <div class="w-full sm:w-40">
            <label for="filter-date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Fecha</label>
            <input type="date" name="date" id="filter-date" value="{{ request('date') }}"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/15 transition-all text-sm">
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <button type="submit" class="h-12 px-6 bg-[#00a650] hover:bg-[#008f42] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                Filtrar
            </button>
            <a href="{{ route('events.index') }}" class="h-12 px-5 flex items-center border border-slate-200 rounded-xl font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-colors duration-200 text-sm">
                Limpiar
            </a>
        </div>
    </div>
</form>
