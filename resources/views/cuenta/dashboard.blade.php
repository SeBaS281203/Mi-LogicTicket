@extends('layouts.cuenta')

@section('title', 'Mi cuenta')

@section('content')
<h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-6">Mis entradas</h1>

@if(($ticketsCount ?? 0) === 0)
    <div class="bg-slate-50 border border-slate-100 rounded-3xl py-16 px-6 text-center">
        <div class="w-20 h-20 rounded-full bg-violet-50 text-emerald-500 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-ticket-alt text-3xl"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900 mb-2">Aún no tienes entradas</h2>
        <p class="text-sm text-slate-500 mb-6">Encuentra los mejores eventos de tu ciudad.</p>
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-violet-600 hover:bg-emerald-600 text-white text-sm font-semibold transition-colors">
            Ver eventos
        </a>
    </div>
@else
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <p class="text-sm text-slate-500">Tienes</p>
            <p class="text-2xl font-black text-slate-900">{{ $ticketsCount }} entradas en tu cuenta</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('cuenta.tickets.index') }}" class="px-5 py-2.5 rounded-2xl bg-violet-600 hover:bg-emerald-600 text-white text-sm font-semibold transition-colors">
                Ver mis entradas
            </a>
            <a href="{{ route('orders.index') }}" class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-colors">
                Ver historial de compras
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <p class="text-sm text-slate-500 mb-3">Resumen rápido</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Órdenes</p>
                <p class="text-xl font-bold text-slate-900 mt-1">{{ $totalOrders }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pagadas</p>
                <p class="text-xl font-bold text-violet-600 mt-1">{{ $ordersPaid }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total comprado</p>
                <p class="text-xl font-bold text-slate-900 mt-1">S/ {{ number_format($totalSpent, 2) }}</p>
            </div>
        </div>
    </div>
@endif
@endsection
