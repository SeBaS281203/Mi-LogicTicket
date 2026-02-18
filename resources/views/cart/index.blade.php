@extends('layouts.app')

@section('title', 'Carrito')

@section('content')
<h1 class="text-3xl font-bold mb-6">Carrito</h1>

@if(empty($items))
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
        <p class="text-slate-500 mb-4">Tu carrito está vacío.</p>
        <a href="{{ route('home') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Ver eventos</a>
    </div>
@else
    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            @foreach($items as $item)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex flex-wrap justify-between gap-4">
                    <div>
                        <h2 class="font-semibold">{{ $item->ticket_type->event->title }}</h2>
                        <p class="text-slate-600">{{ $item->ticket_type->name }}</p>
                        <p class="text-indigo-600 font-medium">S/ {{ number_format($item->ticket_type->price, 2) }} c/u</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="ticket_type_id" value="{{ $item->ticket_type->id }}">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="{{ $item->ticket_type->available_quantity }}" class="w-20 rounded-lg border-slate-300 shadow-sm">
                            <button type="submit" class="text-sm text-indigo-600 hover:underline">Actualizar</button>
                        </form>
                        <p class="font-semibold">S/ {{ number_format($item->subtotal, 2) }}</p>
                        <form method="POST" action="{{ route('cart.remove', $item->ticket_type->id) }}" class="inline" onsubmit="return confirm('¿Eliminar del carrito?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm hover:underline">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-4">
                <h2 class="text-lg font-semibold mb-4">Resumen</h2>
                <p class="flex justify-between text-slate-600"><span>Subtotal</span> S/ {{ number_format($subtotal, 2) }}</p>
                @if($commission_amount > 0)
                    <p class="flex justify-between text-slate-600 mt-1"><span>Comisión ({{ number_format($commission_percentage, 1) }}%)</span> S/ {{ number_format($commission_amount, 2) }}</p>
                @endif
                <hr class="my-3 border-slate-200">
                <p class="text-2xl font-bold text-indigo-600">Total: S/ {{ number_format($total, 2) }}</p>
                <a href="{{ route('checkout.index') }}" class="mt-4 block w-full py-3 text-center bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Ir a pagar</a>
                <a href="{{ route('home') }}" class="mt-2 block text-center text-slate-600 hover:text-indigo-600 text-sm">Seguir comprando</a>
            </div>
        </div>
    </div>
@endif
@endsection
