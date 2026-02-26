@extends('layouts.admin')

@section('title', 'Órdenes')

@section('content')
<h1 class="text-3xl font-bold mb-6">Órdenes / Ventas</h1>

<form method="GET" class="mb-4 flex flex-wrap gap-2 items-end">
    <div>
        <label for="q" class="block text-xs text-slate-500 mb-0.5">Buscar</label>
        <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Nº orden, email, cliente" class="rounded-lg border-slate-300 shadow-sm w-48">
    </div>
    <div>
        <label for="status" class="block text-xs text-slate-500 mb-0.5">Estado</label>
        <select name="status" id="status" class="rounded-lg border-slate-300 shadow-sm">
            <option value="">Todos</option>
            <option value="paid" @selected(request('status') === 'paid')>Pagado</option>
            <option value="pending" @selected(request('status') === 'pending')>Pendiente</option>
            <option value="failed" @selected(request('status') === 'failed')>Fallido</option>
            <option value="refunded" @selected(request('status') === 'refunded')>Reembolsado</option>
        </select>
    </div>
    <div>
        <label for="date_from" class="block text-xs text-slate-500 mb-0.5">Desde</label>
        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="rounded-lg border-slate-300 shadow-sm">
    </div>
    <div>
        <label for="date_to" class="block text-xs text-slate-500 mb-0.5">Hasta</label>
        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="rounded-lg border-slate-300 shadow-sm">
    </div>
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Filtrar</button>
</form>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Orden</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Cliente</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Total</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Método</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Estado</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Fecha</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-t border-slate-100 hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-sm">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $order->customer_name ?: $order->customer_email }}</p>
                            <p class="text-xs text-slate-500">{{ $order->customer_email }}</p>
                        </td>
                        <td class="px-4 py-3 font-medium">S/ {{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $order->payment_method ?: '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClasses = [
                                    'paid' => 'bg-emerald-100 text-emerald-800',
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-slate-100 text-slate-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded {{ $statusClasses[$order->status] ?? 'bg-slate-100' }}">{{ $order->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-500">No hay órdenes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
