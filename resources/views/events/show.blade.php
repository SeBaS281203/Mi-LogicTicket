@extends('layouts.app')

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->description), 160))

@section('content')
<article class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12" itemscope itemtype="https://schema.org/Event">
    <div class="mb-6">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-neutral-500 hover:text-teal-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Volver a eventos
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        <div class="aspect-[21/9] sm:aspect-[3/1] bg-neutral-100 relative">
            @if($event->event_image ?? $event->image)
                <img src="{{ Storage::url($event->event_image ?? $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover" itemprop="image">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-teal-100 to-teal-50 text-teal-300">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
            @endif
            <span class="absolute top-4 left-4 px-3 py-1.5 rounded-xl bg-white/95 backdrop-blur text-sm font-semibold text-teal-700 shadow-sm">
                {{ $event->category->name }}
            </span>
        </div>
        <div class="p-6 sm:p-8 lg:p-10">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-neutral-900 mb-4" itemprop="name">{{ $event->title }}</h1>
            <div class="flex flex-wrap gap-4 text-neutral-600 mb-6">
                <span class="flex items-center gap-2" itemprop="startDate" content="{{ $event->start_date->toIso8601String() }}">
                    <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $event->start_date->translatedFormat('l d \d\e F \d\e Y') }} · {{ $event->start_date->format('H:i') }}
                </span>
                <span class="flex items-center gap-2" itemprop="location" itemscope itemtype="https://schema.org/Place">
                    <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <span itemprop="name">{{ $event->venue_name }}, {{ $event->city }}</span>
                </span>
            </div>
            <div class="prose prose-neutral max-w-none text-neutral-700 leading-relaxed mb-8" itemprop="description">
                {!! nl2br(e($event->description)) !!}
            </div>

            <section class="border-t border-neutral-200 pt-8" aria-labelledby="entradas-heading">
                <h2 id="entradas-heading" class="text-xl font-bold text-neutral-900 mb-4">Entradas</h2>
                <div class="space-y-4">
                    @foreach($event->ticketTypes as $tt)
                        @if($tt->isOnSale())
                            <div class="flex flex-wrap items-center justify-between gap-4 p-4 sm:p-5 bg-neutral-50 rounded-xl border border-neutral-100 hover:border-teal-200 transition-colors">
                                <div>
                                    <p class="font-semibold text-neutral-900">{{ $tt->name }}</p>
                                    @if($tt->description)
                                        <p class="text-sm text-neutral-500 mt-0.5">{{ $tt->description }}</p>
                                    @endif
                                    <p class="text-teal-600 font-bold mt-2">S/ {{ number_format($tt->price, 2) }} — {{ $tt->available_quantity }} disponibles</p>
                                </div>
                                <form method="POST" action="{{ route('cart.add') }}" class="flex items-center gap-3">
                                    @csrf
                                    <input type="hidden" name="ticket_type_id" value="{{ $tt->id }}">
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $tt->available_quantity }}" class="w-20 px-3 py-2 rounded-xl border border-neutral-200 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
                                        Añadir al carrito
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
                @if($event->ticketTypes->isEmpty() || !$event->ticketTypes->contains(fn($t) => $t->isOnSale()))
                    <p class="text-neutral-500 py-4">No hay entradas a la venta en este momento.</p>
                @endif
            </section>
        </div>
    </div>
</article>
@endsection
