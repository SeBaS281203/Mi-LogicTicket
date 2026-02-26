@extends('layouts.organizer')

@section('title', 'Reporte de ingresos')

@section('content')
<h1 class="text-3xl font-bold text-slate-900 mb-6">Reporte de ingresos</h1>

<div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="text-sm font-medium text-slate-500">Ingresos totales (mis eventos)</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1">S/ {{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="text-sm font-medium text-slate-500">Entradas vendidas</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ number_format($totalTickets) }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8">
    <h2 class="text-lg font-semibold text-slate-900 mb-4">Por evento</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Evento</th>
                    <th class="text-right px-6 py-3 font-semibold text-slate-700">Entradas vendidas</th>
                    <th class="text-right px-6 py-3 font-semibold text-slate-700">Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byEvent as $row)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-3 font-medium text-slate-900">{{ $row->event?->title ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-right text-slate-600">{{ $row->tickets }}</td>
                        <td class="px-6 py-3 text-right font-medium text-emerald-600">S/ {{ number_format($row->revenue, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <h2 class="text-lg font-semibold text-slate-900 mb-4">Descargar reporte</h2>
    <p class="text-slate-600 text-sm mb-4">Exporta tus ingresos en el rango de fechas indicado.</p>
    <form method="GET" action="{{ route('organizer.reports.export') }}" class="space-y-4" id="export-form">
        <div class="flex flex-wrap gap-4">
            <div>
                <label for="date_from" class="block text-sm font-medium text-slate-700 mb-1">Desde</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from', now()->subMonth()->format('Y-m-d')) }}" class="rounded-xl border-slate-200 shadow-sm px-4 py-2">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-slate-700 mb-1">Hasta</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}" class="rounded-xl border-slate-200 shadow-sm px-4 py-2">
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Descargar Excel</button>
            <button type="submit" formaction="{{ route('organizer.reports.export.pdf') }}" form="export-form" class="px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700">Descargar PDF</button>
        </div>
    </form>
</div>
@endsection
