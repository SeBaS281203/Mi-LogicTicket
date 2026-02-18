@extends('layouts.admin')

@section('title', $event->title)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">{{ $event->title }}</h1>
    <div class="flex gap-2">
        @if($event->status === 'pending_approval')
            <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Aprobar y publicar</button>
            </form>
            <form method="POST" action="{{ route('admin.events.reject', $event) }}" onsubmit="return confirm('¿Rechazar evento?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Rechazar</button>
            </form>
        @endif
        <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">Volver</a>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="font-semibold mb-4">Detalles</h2>
        <dl class="space-y-2 text-sm">
            <dt class="text-slate-500">Estado</dt>
            <dd><span class="px-2 py-1 text-xs rounded {{ $event->status === 'published' ? 'bg-green-100' : ($event->status === 'pending_approval' ? 'bg-amber-100' : 'bg-slate-100') }}">{{ $event->status }}</span></dd>
            <dt class="text-slate-500">Organizador</dt>
            <dd>{{ $event->user?->name }} ({{ $event->user?->email }})</dd>
            <dt class="text-slate-500">Categoría</dt>
            <dd>{{ $event->category?->name }}</dd>
            <dt class="text-slate-500">Lugar</dt>
            <dd>{{ $event->venue_name }}, {{ $event->city }}</dd>
            <dt class="text-slate-500">Fecha inicio</dt>
            <dd>{{ $event->start_date?->format('d/m/Y H:i') }}</dd>
            <dt class="text-slate-500">Fecha fin</dt>
            <dd>{{ $event->end_date?->format('d/m/Y H:i') ?? '-' }}</dd>
        </dl>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="font-semibold mb-4">Tipos de entrada</h2>
        <ul class="space-y-2">
            @foreach($event->ticketTypes as $tt)
                <li class="flex justify-between py-2 border-b border-slate-100">
                    <span>{{ $tt->name }}</span>
                    <span>S/ {{ number_format($tt->price, 2) }} · {{ $tt->quantity - $tt->quantity_sold }} disponibles</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h2 class="font-semibold mb-2">Descripción</h2>
    <div class="prose prose-slate max-w-none text-sm">{{ Str::limit($event->description, 500) }}</div>
</div>
@endsection
