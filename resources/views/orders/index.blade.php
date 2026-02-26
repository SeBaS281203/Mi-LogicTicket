@extends('layouts.cuenta')

@section('title', 'Historial de compras')

@section('content')
<h1 class="text-3xl font-bold text-slate-900 mb-2">Historial de compras</h1>
<p class="text-slate-600 mb-8">Todas tus órdenes y compras realizadas.</p>

@forelse($orders as $order)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                    <p class="text-sm text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <span class="inline-block mt-2 px-2.5 py-1 text-xs font-medium rounded-lg
                        @if($order->status === 'paid') bg-emerald-100 text-emerald-700
                        @elseif($order->status === 'pending') bg-amber-100 text-amber-700
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                        @else bg-slate-100 text-slate-700 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    @if($order->status === 'paid' && $order->items->sum(fn($i) => $i->tickets->count()) > 0)
                        <a href="{{ route('cuenta.tickets.download', $order) }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Descargar tickets
                        </a>
                    @endif
                    <p class="text-lg font-bold text-emerald-600">S/ {{ number_format($order->total, 2) }}</p>
                </div>
            </div>
            <ul class="mt-4 text-sm text-slate-600 space-y-1">
                @foreach($order->items as $item)
                    <li>{{ $item->event_title }} — {{ $item->ticket_type_name }} x{{ $item->quantity }}</li>
                @endforeach
            </ul>
            <a href="{{ route('orders.confirmation', $order) }}" class="inline-block mt-4 text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver detalle →</a>
        </div>
    </div>
@empty
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <p class="text-slate-600 mb-4">No tienes órdenes aún.</p>
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Explorar eventos</a>
    </div>
@endforelse

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection
