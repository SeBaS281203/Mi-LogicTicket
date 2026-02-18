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
{{-- Fondo general gris claro · Espaciado vertical 60px entre secciones · Container max 1280px --}}
<div class="bg-[#F5F7FA] min-h-screen">
    {{-- 1. Hero Slider --}}
    @include('components.hero', ['categories' => $categories, 'banners' => $banners ?? collect()])

    {{-- 2. Eventos Destacados (full width, cards blancas) --}}
    <div class="pt-[60px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.featured-events-section', [
                'events' => $featuredEvents,
                'title' => 'Destacados',
                'sectionSubtitle' => 'Eventos',
                'seeAllUrl' => route('events.index'),
                'withSidebar' => false,
            ])
        </div>
    </div>

    {{-- 3. Tendencias (sidebar desktop) + 4. Planes Imperdibles --}}
    <div class="pt-[60px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.planes-imperdibles-section', [
                'events' => $featuredEvents,
                'categories' => $categories,
            ])
        </div>
    </div>

    {{-- 5. Descubre tus intereses --}}
    <div class="pt-[60px]">
        @include('components.interests-section', ['categories' => $categories])
    </div>

    {{-- 6. CTA Organizadores --}}
    <div class="pt-[60px] pb-[60px]">
        @include('components.cta-organizers')
    </div>

    {{-- Solo en /eventos: Filtros + grid de eventos --}}
    @if(request()->routeIs('events.index'))
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12 bg-white rounded-t-2xl shadow-sm border-t border-neutral-100">
        <div class="pt-6">
            <div class="mb-4">
                @include('components.filters-bar', ['categories' => $categories])
            </div>

            @auth
                @if(auth()->user()->isOrganizer())
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#00a650] hover:bg-[#009345] text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Crear evento
                        </a>
                    </div>
                @endif
            @endauth

            @if($events->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($events as $event)
                        <div class="animate-slide-up" style="animation-delay: {{ $loop->index * 50 }}ms;">
                            @include('components.event-card', ['event' => $event])
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 flex justify-center">
                    {{ $events->links() }}
                </div>
            @else
                <div class="text-center py-12 px-4 bg-neutral-50 rounded-xl border border-neutral-200">
                    <p class="text-neutral-500 text-sm mb-2">No hay eventos que coincidan.</p>
                    <a href="{{ route('events.index') }}" class="inline-flex gap-2 px-4 py-2 bg-[#00a650] hover:bg-[#009345] text-white text-sm font-semibold rounded-lg transition-colors">Ver todos</a>
                </div>
            @endif
        </div>
    </section>
    @endif
</div>
@endsection
