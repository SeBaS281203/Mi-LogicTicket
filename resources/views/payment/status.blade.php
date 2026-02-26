@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden text-center p-8 sm:p-12">
        <div class="mb-8 flex justify-center">
            @if($status === 'success')
                <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
            @elseif($status === 'failure')
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            @else
                <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            @endif
        </div>

        <h1 class="text-3xl font-black text-gray-900 mb-4">{{ $message }}</h1>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            @if($status === 'success')
                Tu orden ha sido confirmada. Hemos enviado un correo electrónico con tus tickets en formato PDF.
            @elseif($status === 'failure')
                Hubo un problema al procesar tu pago. Por favor, intenta de nuevo o utiliza otro método de pago.
            @else
                Estamos esperando la confirmación de tu pago. Te informaremos una vez que sea aprobado.
            @endif
        </p>

        @if(isset($order_number))
            <div class="bg-gray-50 rounded-2xl p-6 mb-8 inline-block">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Número de Orden</span>
                <span class="text-xl font-mono font-bold text-gray-900">{{ $order_number }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if($status === 'success')
                <a href="{{ route('cuenta.tickets.index') }}" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    Ver mis tickets
                </a>
            @elseif($status === 'failure')
                <a href="{{ route('checkout.index') }}" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200">
                    Reintentar Pago
                </a>
            @endif
            <a href="{{ route('home') }}" class="px-8 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">
                Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection
