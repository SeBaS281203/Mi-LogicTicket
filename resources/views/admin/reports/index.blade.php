@extends('layouts.admin')

@section('title', 'Reportes')

@section('content')
<h1 class="text-3xl font-bold text-slate-900 mb-6">Reportes descargables</h1>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-2">Reporte de órdenes</h2>
        <p class="text-slate-600 text-sm mb-6">Exporta las órdenes en el rango de fechas indicado.</p>
        <form method="GET" action="{{ route('admin.reports.orders.excel') }}" class="space-y-5" id="orders-report-form">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-slate-700 mb-1">Desde</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from', now()->subMonth()->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-slate-700 mb-1">Hasta</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Descargar Excel</button>
                <button type="submit" formaction="{{ route('admin.reports.orders.pdf') }}" form="orders-report-form" class="px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700">Descargar PDF</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-2">Reporte de eventos</h2>
        <p class="text-slate-600 text-sm mb-6">Exporta el listado de eventos (opcionalmente por estado).</p>
        <form method="GET" action="{{ route('admin.reports.events.excel') }}" class="space-y-5">
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                <select name="status" id="status" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2">
                    <option value="">Todos</option>
                    <option value="draft">Borrador</option>
                    <option value="pending_approval">Pendiente aprobación</option>
                    <option value="published">Publicado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Descargar Excel</button>
        </form>
    </div>
</div>
@endsection
