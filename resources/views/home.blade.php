@extends('layouts.app')

@section('title', 'Eventos')

@section('content')
<h1 class="text-3xl font-bold mb-6">Eventos</h1>

<form method="GET" action="{{ route('home') }}" class="mb-8 p-4 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
        <select name="category" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Todas</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="min-w-[180px]">
        <label class="block text-sm font-medium text-slate-700 mb-1">Ciudad</label>
        <input type="text" name="city" value="{{ request('city') }}" placeholder="Ej. Lima"
            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div class="min-w-[180px]">
        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
        <input type="date" name="date" value="{{ request('date') }}"
            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div class="min-w-[180px]">
        <label class="block text-sm font-medium text-slate-700 mb-1">Buscar</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nombre del evento"
            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Filtrar</button>
</form>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($events as $event)
        <a href="{{ route('events.show', $event) }}" class="block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">
            @if($event->image)
                <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-slate-200 flex items-center justify-center text-slate-400">
                    <span class="text-4xl">🎫</span>
                </div>
            @endif
            <div class="p-4">
                <span class="text-xs font-medium text-indigo-600">{{ $event->category->name }}</span>
                <h2 class="font-semibold text-lg mt-1">{{ $event->title }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $event->venue_name }}, {{ $event->city }}</p>
                <p class="text-sm text-slate-600 mt-2">{{ $event->start_date->format('d/m/Y H:i') }}</p>
                <p class="text-sm font-medium text-indigo-600 mt-2">Desde S/ {{ number_format($event->ticketTypes->min('price') ?? 0, 2) }}</p>
            </div>
        </a>
    @empty
        <p class="col-span-full text-slate-500 text-center py-12">No hay eventos que coincidan con tu búsqueda.</p>
    @endforelse
</div>

<div class="mt-8">
    {{ $events->withQueryString()->links() }}
</div>
@endsection
