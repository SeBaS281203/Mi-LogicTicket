@extends('layouts.organizer')

@section('title', 'Ventas')

@section('content')
<h1 class="text-3xl font-bold text-slate-900 mb-6">Ventas</h1>

<form method="GET" class="mb-6 flex flex-wrap gap-3">
    <select name="event_id" class="rounded-xl border-slate-200 shadow-sm px-4 py-2 text-sm">
        <option value="">Todos los eventos</option>
        @foreach($events as $e)
            <option value="{{ $e->id }}" @selected(request('event_id') == $e->id)>{{ $e->title }}</option>
        @endforeach
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border-slate-200 shadow-sm px-4 py-2 text-sm" placeholder="Desde">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border-slate-200 shadow-sm px-4 py-2 text-sm" placeholder="Hasta">
    <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 text-sm">Filtrar</button>
</form>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Fecha</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Evento</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Tipo entrada</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Cantidad</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-700">Subtotal</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Cliente</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $item)
                    <tr class="border-t border-slate-100 hover:bg-slate-50/50">
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $item->order?->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $item->event_title }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->ticket_type_name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right font-medium text-emerald-600">S/ {{ number_format($item->subtotal, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $item->order?->customer_email }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">No hay ventas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $sales->links() }}</div>
@endsection
