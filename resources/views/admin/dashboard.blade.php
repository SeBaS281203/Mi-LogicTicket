@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Panel de administración</h1>
    <p class="text-slate-500 text-sm mt-1">Resumen general del sistema LogicTicket</p>
</div>

{{-- Métricas principales --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Eventos</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['events'] }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $stats['events_pending'] }} pendientes de aprobación</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Organizadores</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['organizers'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Ventas</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['sales'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Órdenes pagadas</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Ingresos Totales</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">S/ {{ number_format($stats['revenue'], 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('admin.banners.index') }}" class="group bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Slider principal</h2>
                <p class="text-sm text-slate-500 mt-1">Administra las imagenes de eventos destacados del home.</p>
            </div>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-violet-100 text-violet-700">S</span>
        </div>
        <p class="text-sm font-medium text-indigo-600 mt-4">Cambiar o agregar imagenes -&gt;</p>
    </a>

    <a href="{{ route('admin.tendencias.index') }}" class="group bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Tendencias</h2>
                <p class="text-sm text-slate-500 mt-1">Gestiona el bloque de tendencias visibles en el sitio.</p>
            </div>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700">T</span>
        </div>
        <p class="text-sm font-medium text-indigo-600 mt-4">Ir a tendencias -&gt;</p>
    </a>
</div>

{{-- Gráficos --}}
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Ingresos por mes (últimos 12 meses)</h2>
        <div class="h-64">
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Eventos por estado</h2>
        <div class="h-64">
            <canvas id="eventsChart" width="200" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Ingresos por Organizador --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-lg font-semibold text-slate-900">Ingresos por Organizador</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Organizador</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Eventos</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tickets Vendidos</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Ingresos</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($organizerEarnings as $org)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $org->name }}</div>
                            <div class="text-xs text-slate-400">ID: {{ $org->id }}</div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-600">{{ $org->events_count }}</td>
                        <td class="px-6 py-4 text-center text-sm text-slate-600">{{ $org->tickets_sold ?? 0 }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-slate-900">S/ {{ number_format($org->total_revenue ?? 0, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.index', ['organizer_id' => $org->id, 'status' => 'paid']) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Ver detalle</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Tablas recientes --}}
<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Últimas órdenes</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Ver todas →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Orden</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr class="border-t border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-sm font-medium text-indigo-600 hover:underline">{{ $order->order_number }}</a></td>
                            <td class="px-4 py-3 text-sm text-slate-600 truncate max-w-[150px]">{{ $order->customer_email }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">S/ {{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $order->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $order->status }}</span></td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm font-medium">Ver</a></td>
                        </tr>
                    @endforeach
                    @if($recentOrders->isEmpty())
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500 text-sm">No hay órdenes recientes</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Últimos eventos</h2>
            <a href="{{ route('admin.events.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Ver todos →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Evento</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Organizador</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentEvents as $event)
                        <tr class="border-t border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3"><a href="{{ route('admin.events.show', $event) }}" class="font-medium text-slate-900 hover:text-indigo-600 truncate max-w-[180px] block">{{ $event->title }}</a></td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $event->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $event->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($event->status === 'pending_approval' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">{{ $event->status === 'pending_approval' ? 'Pendiente' : $event->status }}</span></td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.events.show', $event) }}" class="text-indigo-600 hover:underline text-sm font-medium">Ver</a></td>
                        </tr>
                    @endforeach
                    @if($recentEvents->isEmpty())
                        <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500 text-sm">No hay eventos</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json($revenueByMonthLabels),
                datasets: @json($chartDatasets)
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, usePointStyle: true }
                    } 
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        stacked: true 
                    },
                    x: { 
                        grid: { display: false },
                        stacked: true 
                    }
                }
            }
        });
    }
    const eventsCtx = document.getElementById('eventsChart');
    if (eventsCtx) {
        new Chart(eventsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Publicados', 'Pendientes', 'Borrador', 'Cancelados'],
                datasets: [{
                    data: [{{ $eventsByStatus['published'] }}, {{ $eventsByStatus['pending'] }}, {{ $eventsByStatus['draft'] }}, {{ $eventsByStatus['cancelled'] }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#64748b', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
@endpush
@endsection

