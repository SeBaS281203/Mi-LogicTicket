@extends('layouts.app')

@section('title', 'Panel Organizador')

@section('content')
<h1 class="text-3xl font-bold mb-6">Dashboard de ventas</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Ingresos totales</p>
        <p class="text-2xl font-bold text-indigo-600">S/ {{ number_format($totalSales, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Entradas vendidas</p>
        <p class="text-2xl font-bold text-indigo-600">{{ $totalTicketsSold }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Mis eventos</p>
        <p class="text-2xl font-bold text-indigo-600">{{ auth()->user()->events()->count() }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <h2 class="text-lg font-semibold p-4 border-b border-slate-200">Últimas ventas</h2>
        <ul class="divide-y divide-slate-100">
            @forelse($recentOrders as $item)
                <li class="px-4 py-3 flex justify-between text-sm">
                    <span>{{ $item->event_title }} - {{ $item->ticket_type_name }} x{{ $item->quantity }}</span>
                    <span class="font-medium">S/ {{ number_format($item->subtotal, 2) }}</span>
                </li>
            @empty
                <li class="px-4 py-6 text-slate-500 text-center">Aún no hay ventas</li>
            @endforelse
        </ul>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <h2 class="text-lg font-semibold p-4 border-b border-slate-200">Mis eventos</h2>
        <ul class="divide-y divide-slate-100">
            @forelse($events as $event)
                <li class="px-4 py-3 flex justify-between items-center">
                    <span class="font-medium">{{ $event->title }}</span>
                    <span class="text-xs px-2 py-1 rounded {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : ($event->status === 'pending_approval' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">{{ $event->status === 'pending_approval' ? 'Pend. aprobación' : $event->status }}</span>
                    <a href="{{ route('organizer.events.edit', $event) }}" class="text-indigo-600 text-sm hover:underline">Editar</a>
                </li>
            @empty
                <li class="px-4 py-6 text-slate-500 text-center">No tienes eventos. <a href="{{ route('organizer.events.create') }}" class="text-indigo-600 hover:underline">Crear uno</a></li>
            @endforelse
        </ul>
        <div class="p-4 border-t border-slate-200">
            <a href="{{ route('organizer.events.index') }}" class="text-indigo-600 hover:underline">Ver todos los eventos →</a>
        </div>
    </div>
</div>
@endsection
