@extends('layouts.organizer')

@section('title', 'Compradores')

@section('content')
<h1 class="text-3xl font-bold text-slate-900 mb-6">Lista de compradores</h1>

<form method="GET" class="mb-6 flex flex-wrap gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por email, nombre o orden" class="rounded-xl border-slate-200 shadow-sm px-4 py-2 text-sm w-64">
    <select name="event_id" class="rounded-xl border-slate-200 shadow-sm px-4 py-2 text-sm">
        <option value="">Todos los eventos</option>
        @foreach($events as $e)
            <option value="{{ $e->id }}" @selected(request('event_id') == $e->id)>{{ $e->title }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 text-sm">Buscar</button>
</form>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Orden</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Fecha</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Nombre</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Email</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Eventos comprados</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-700">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buyers as $order)
                    <tr class="border-t border-slate-100 hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-mono text-sm text-slate-600">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $order->customer_name ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $order->customer_email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            @foreach($order->items as $item)
                                <span class="block">{{ $item->event_title }} — {{ $item->ticket_type_name }} x{{ $item->quantity }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-emerald-600">S/ {{ number_format($order->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">No hay compradores.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $buyers->links() }}</div>
@endsection
