@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h1 class="text-3xl font-bold mb-6">Finalizar compra</h1>

<div class="grid lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Datos de contacto</h2>
        <form method="POST" action="{{ route('checkout.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-1">Nombre completo</label>
                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('customer_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="customer_email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('customer_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="customer_phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono (opcional)</label>
                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', auth()->user()?->phone) }}"
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('customer_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                @if(config('logic-ticket.stripe.enabled'))
                    Pagar con tarjeta (Stripe)
                @elseif(config('logic-ticket.mercadopago.enabled'))
                    Pagar con Mercado Pago
                @else
                    Confirmar compra
                @endif
            </button>
        </form>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Resumen del pedido</h2>
        <ul class="space-y-3">
            @foreach($items as $item)
                <li class="flex justify-between text-slate-700">
                    <span>{{ $item->ticket_type->event->title }} - {{ $item->ticket_type->name }} x{{ $item->quantity }}</span>
                    <span>S/ {{ number_format($item->subtotal, 2) }}</span>
                </li>
            @endforeach
        </ul>
        <p class="flex justify-between text-slate-600 mt-2"><span>Subtotal</span> S/ {{ number_format($subtotal, 2) }}</p>
        @if(isset($commission_amount) && $commission_amount > 0)
            <p class="flex justify-between text-slate-600"><span>Comisión ({{ number_format($commission_percentage ?? 0, 1) }}%)</span> S/ {{ number_format($commission_amount, 2) }}</p>
        @endif
        <hr class="my-4 border-slate-200">
        <p class="text-xl font-bold text-indigo-600">Total: S/ {{ number_format($total, 2) }}</p>
    </div>
</div>
@endsection
