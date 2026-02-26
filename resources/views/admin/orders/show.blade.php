@extends('layouts.admin')

@section('title', 'Orden ' . $order->order_number)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Orden {{ $order->order_number }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">← Volver</a>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <h2 class="px-4 py-3 border-b border-slate-200 font-semibold">Detalle del cliente</h2>
        <div class="p-4 space-y-2">
            <p><span class="text-slate-500 text-sm">Nombre:</span> {{ $order->customer_name ?: '-' }}</p>
            <p><span class="text-slate-500 text-sm">Email:</span> {{ $order->customer_email }}</p>
            <p><span class="text-slate-500 text-sm">Teléfono:</span> {{ $order->customer_phone ?: '-' }}</p>
            @if($order->user)
                <p class="pt-2"><a href="{{ route('admin.users.edit', $order->user) }}" class="text-indigo-600 hover:underline">Ver usuario →</a></p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <h2 class="px-4 py-3 border-b border-slate-200 font-semibold">Pago</h2>
        <div class="p-4 space-y-2">
            <p><span class="text-slate-500 text-sm">Estado:</span>
                @php
                    $statusClasses = ['paid' => 'text-emerald-600', 'pending' => 'text-amber-600', 'failed' => 'text-red-600', 'refunded' => 'text-slate-600'];
                @endphp
                <span class="font-medium {{ $statusClasses[$order->status] ?? '' }}">{{ strtoupper($order->status) }}</span>
            </p>
            <p><span class="text-slate-500 text-sm">Método:</span> {{ $order->payment_method ?: '-' }}</p>
            <p><span class="text-slate-500 text-sm">ID pago:</span> {{ $order->payment_id ?: '-' }}</p>
            <p><span class="text-slate-500 text-sm">Fecha:</span> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <h2 class="px-4 py-3 border-b border-slate-200 font-semibold">Items de la orden</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-700">Evento</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-700">Tipo entrada</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-700">Cant.</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-700">P. unit.</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-700">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $item->event_title }}</td>
                        <td class="px-4 py-3">{{ $item->ticket_type_name }}</td>
                        <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right">S/ {{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium">S/ {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-6">
        <p><span class="text-slate-600">Subtotal:</span> <strong>S/ {{ number_format($order->subtotal, 2) }}</strong></p>
        @if($order->commission_amount > 0)
            <p><span class="text-slate-600">Comisión:</span> <strong>S/ {{ number_format($order->commission_amount, 2) }}</strong></p>
        @endif
        <p><span class="text-slate-600">Total:</span> <strong class="text-lg text-indigo-600">S/ {{ number_format($order->total, 2) }}</strong></p>
    </div>
</div>
@endsection
