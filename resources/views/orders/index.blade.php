@extends('layouts.app')

@section('title', 'Mis órdenes')

@section('content')
<h1 class="text-3xl font-bold mb-6">Mis órdenes</h1>

@forelse($orders as $order)
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-4">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <p class="font-semibold">{{ $order->order_number }}</p>
                <p class="text-sm text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                <span class="inline-block mt-2 px-2 py-1 text-xs rounded
                    @if($order->status === 'paid') bg-green-100 text-green-800
                    @elseif($order->status === 'pending') bg-amber-100 text-amber-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @elseif($order->status === 'refunded') bg-slate-100 text-slate-800
                    @else bg-slate-100 text-slate-800 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <p class="text-lg font-bold text-indigo-600">S/ {{ number_format($order->total, 2) }}</p>
        </div>
        <ul class="mt-4 text-sm text-slate-600 space-y-1">
            @foreach($order->items as $item)
                <li>{{ $item->event_title }} - {{ $item->ticket_type_name }} x{{ $item->quantity }}</li>
            @endforeach
        </ul>
        <a href="{{ route('orders.confirmation', $order) }}" class="inline-block mt-4 text-indigo-600 hover:underline text-sm">Ver detalle</a>
    </div>
@empty
    <p class="text-slate-500">No tienes órdenes aún.</p>
    <a href="{{ route('home') }}" class="inline-block mt-4 text-indigo-600 hover:underline">Ver eventos</a>
@endforelse

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection
