@extends('layouts.app')

@section('title', 'Orden ' . $order->order_number)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
        <div class="text-5xl mb-4">✅</div>
        <h1 class="text-2xl font-bold text-green-600">¡Compra realizada!</h1>
        <p class="mt-2 text-slate-600">Número de orden: <strong>{{ $order->order_number }}</strong></p>
        <p class="mt-1 text-sm text-slate-500">Hemos enviado los detalles a {{ $order->customer_email }}</p>
    </div>
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        @php
            $organizers = $order->items->map(fn($i) => $i->event?->user)->filter()->unique('id');
        @endphp
        @if($organizers->isNotEmpty())
        <div class="mb-4 pb-4 border-b border-slate-100">
            <p class="text-sm font-medium text-slate-500">Organizador(es)</p>
            @foreach($organizers as $org)
            <p class="text-slate-800 font-medium">{{ $org->name }}@if($org->ruc) · RUC: {{ $org->ruc }}@endif</p>
            @endforeach
        </div>
        @endif
        <h2 class="font-semibold mb-4">Detalle de la orden</h2>
        <ul class="space-y-3">
            @foreach($order->items as $item)
                <li class="flex justify-between py-2 border-b border-slate-100 last:border-0">
                    <span>{{ $item->event_title }} - {{ $item->ticket_type_name }} x{{ $item->quantity }}</span>
                    <span>S/ {{ number_format($item->subtotal, 2) }}</span>
                </li>
            @endforeach
        </ul>
        @if($order->commission_amount > 0)
            <p class="flex justify-between py-2 text-slate-600"><span>Comisión</span> S/ {{ number_format($order->commission_amount, 2) }}</p>
        @endif
        <p class="mt-4 text-lg font-bold text-indigo-600">Total: S/ {{ number_format($order->total, 2) }}</p>
        <p class="mt-2 text-sm text-slate-500">Los entradas con código QR se han enviado a tu correo.</p>
        @auth
            @if($order->status === 'paid' && $order->items->sum(fn($i) => $i->tickets->count()) > 0)
                <a href="{{ route('cuenta.tickets.download', $order) }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-violet-600 text-white font-medium rounded-xl hover:bg-violet-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Descargar tickets (PDF con QR)
                </a>
            @endif
        @endauth
    </div>
    <div class="mt-6 text-center space-x-4">
        <a href="{{ route('home') }}" class="text-violet-600 hover:underline font-medium">Volver a eventos</a>
        <a href="{{ route('orders.index') }}" class="text-violet-600 hover:underline font-medium">Historial de compras</a>
        @auth
            <a href="{{ route('cuenta.dashboard') }}" class="text-violet-600 hover:underline font-medium">Mi cuenta</a>
        @endauth
    </div>
</div>
@endsection
