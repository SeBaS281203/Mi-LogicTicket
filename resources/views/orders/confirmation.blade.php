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
    </div>
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}" class="text-indigo-600 hover:underline">Volver a eventos</a>
        <span class="mx-2">|</span>
        <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:underline">Mis órdenes</a>
    </div>
</div>
@endsection
