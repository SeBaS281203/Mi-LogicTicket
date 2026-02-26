@extends('layouts.app')

@section('title', 'Eventos - Conciertos, Deportes, Teatro y más')
@section('meta_description', 'Compra entradas para conciertos, deportes, teatro, conferencias y eventos. Filtra por ciudad, fecha y categoría. LogicTicket.')

@push('styles')
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { scrollbar-width: none; -ms-overflow-style: none; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-100">
    @if(request()->routeIs('home'))
        {{-- PÁGINA PRINCIPAL (Home) --}}
        @include('components.hero', ['categories' => $categories ?? collect(), 'banners' => $banners ?? collect()])
        @include('components.planes-imperdibles-section', [
            'events' => $featuredEvents ?? collect(),
            'tendencias' => $tendencias ?? collect(),
            'categories' => $categories ?? collect(),
        ])
        <div class="pt-6 sm:pt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @include('components.featured-events-section', [
                    'events' => $featuredEvents ?? collect(),
                    'title' => 'Destacados',
                    'sectionSubtitle' => 'Eventos',
                    'seeAllUrl' => route('events.index'),
                    'withSidebar' => false,
                ])
            </div>
        </div>
        @include('components.feed-personalizado')
        <div class="pt-8 sm:pt-10 pb-10 sm:pb-12">
            @include('components.interests-section', ['categories' => $categories ?? collect()])
        </div>
    @else
        {{-- VISTA CATÁLOGO DE EVENTOS (/eventos) - Interfaz mejorada --}}
        <section id="eventos-listado" class="pb-16 sm:pb-24">
            {{-- Header con gradiente sutil --}}
            <div class="relative bg-gradient-to-br from-slate-50 via-white to-slate-50/80 border-b border-slate-200/80 overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(124,58,237,0.08),transparent)] pointer-events-none"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold uppercase tracking-wider mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                Catálogo
                            </div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight">Todos los eventos</h1>
                            <p class="mt-2 text-slate-600 text-sm sm:text-base max-w-xl">Conciertos, deportes, teatro, festivales y más. Usa el buscador de arriba para filtrar.</p>
                        </div>
                        @auth
                            @if(auth()->user()->isOrganizer())
                                <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-violet-500/20 hover:shadow-xl hover:shadow-violet-500/25 hover:-translate-y-0.5 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Crear evento
                                </a>
                            @endif
                        @endauth
                    </div>
                    {{-- Accesos rápidos por categoría --}}
                    @if(isset($categories) && $categories->isNotEmpty())
                        <div class="mt-8 flex flex-wrap gap-2">
                            <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ !request('category') ? 'bg-violet-600 text-white shadow-md' : 'bg-white/80 text-slate-600 hover:bg-violet-50 hover:border-violet-200 border border-slate-200' }} transition-all duration-200">Todos</a>
                            @foreach($categories->take(6) as $cat)
                                <a href="{{ route('events.index', ['category_slug' => $cat->slug]) }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ request('category') == $cat->id ? 'bg-violet-600 text-white shadow-md' : 'bg-white/80 text-slate-600 hover:bg-violet-50 hover:border-violet-200 border border-slate-200' }} transition-all duration-200">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10">
                @if($events->isNotEmpty())
                    {{-- Contador con badge --}}
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-sm text-sm">
                                <span class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></span>
                                <span class="font-semibold text-slate-900">{{ $events->total() }}</span>
                                <span class="text-slate-600">eventos</span>
                            </span>
                        </div>
                    </div>
                    {{-- Grid de eventos --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
                        @foreach($events as $event)
                            <div class="animate-slide-up" style="animation-delay: {{ min($loop->index * 50, 250) }}ms;">
                                @include('components.event-card', ['event' => $event])
                            </div>
                        @endforeach
                    </div>
                    {{-- Paginación --}}
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-2xl border border-slate-100 px-4 py-3 shadow-sm">
                            {{ $events->withQueryString()->links() }}
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-lg py-20 sm:py-28 px-8 sm:px-16 text-center overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-violet-100/60 to-transparent pointer-events-none"></div>
                        <div class="relative">
                            <div class="w-24 h-24 mx-auto mb-8 rounded-2xl bg-slate-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 mb-3">No hay resultados</h2>
                            <p class="text-slate-600 mb-10 max-w-md mx-auto leading-relaxed">No encontramos eventos con los filtros aplicados. Prueba el buscador o explora todas las categorías.</p>
                            <div class="flex flex-wrap justify-center gap-3">
                                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                    Ver todos
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <button type="button" @click="$store.search.open = true" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:border-violet-300 hover:bg-violet-50 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif
</div>
@endsection
