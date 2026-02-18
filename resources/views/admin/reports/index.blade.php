@extends('layouts.admin')

@section('title', 'Reportes')

@section('content')
<h1 class="text-3xl font-bold mb-6">Reportes exportables</h1>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Reporte de órdenes</h2>
        <p class="text-slate-600 text-sm mb-4">Exporta las órdenes en el rango de fechas indicado.</p>
        <form method="GET" action="{{ route('admin.reports.orders.excel') }}" class="space-y-4 mb-4" id="orders-report-form">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-slate-700 mb-1">Desde</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from', now()->subMonth()->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 shadow-sm">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-slate-700 mb-1">Hasta</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 shadow-sm">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Descargar CSV (Excel)</button>
                <button type="submit" formaction="{{ route('admin.reports.orders.pdf') }}" form="orders-report-form" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Descargar PDF</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Reporte de eventos</h2>
        <p class="text-slate-600 text-sm mb-4">Exporta el listado de eventos (opcionalmente por estado).</p>
        <form method="GET" action="{{ route('admin.reports.events.excel') }}" class="space-y-4">
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                <select name="status" id="status" class="w-full rounded-lg border-slate-300 shadow-sm">
                    <option value="">Todos</option>
                    <option value="draft">Borrador</option>
                    <option value="pending_approval">Pendiente aprobación</option>
                    <option value="published">Publicado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Descargar Excel (CSV)</button>
        </form>
    </div>
</div>
@endsection
