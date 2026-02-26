@extends('layouts.organizer')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Panel Organizador</h1>
    <p class="text-slate-500 text-sm mt-1">Resumen de tus eventos y ventas</p>
</div>

{{-- Métricas principales --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Ingresos totales</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">S/ {{ number_format($totalSales, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Entradas vendidas</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totalTicketsSold) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Mis eventos</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalEvents }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $eventsByStatus['published'] }} publicados</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Pendientes aprobación</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $eventsByStatus['pending'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
</div>

{{-- Gráficos --}}
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Ingresos por mes (mis eventos)</h2>
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

{{-- Tablas --}}
<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Últimas ventas</h2>
            <a href="{{ route('organizer.sales.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver todas →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Evento / Entrada</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $item)
                        <tr class="border-t border-slate-50 hover:bg-slate-50/50">
                            <td class="px-4 py-3 text-sm">
                                <span class="font-medium text-slate-900">{{ $item->event_title }}</span>
                                <span class="text-slate-500">— {{ $item->ticket_type_name }} x{{ $item->quantity }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600 truncate max-w-[150px]">{{ $item->order?->customer_email }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-emerald-600">S/ {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    @if($recentOrders->isEmpty())
                        <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500 text-sm">Aún no hay ventas</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Mis eventos</h2>
            <a href="{{ route('organizer.events.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver todos →</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse($events as $event)
                <li class="px-6 py-4 flex justify-between items-center hover:bg-slate-50/50">
                    <span class="font-medium text-slate-900 truncate max-w-[200px]">{{ $event->title }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs px-2.5 py-1 rounded-lg {{ $event->status === 'published' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'pending_approval' ? 'bg-amber-100 text-amber-700' : ($event->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600')) }}">{{ $event->status === 'pending_approval' ? 'Pendiente' : $event->status }}</span>
                        <a href="{{ route('organizer.events.edit', $event) }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Editar</a>
                    </div>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-slate-500 text-sm">No tienes eventos. <a href="{{ route('organizer.events.create') }}" class="text-emerald-600 hover:underline">Crear uno</a></li>
            @endforelse
        </ul>
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
                labels: @json($revenueByMonthLabels ?? []),
                datasets: [{
                    label: 'Ingresos (S/)',
                    data: @json($revenueByMonthData ?? []),
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
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
                    data: [{{ $eventsByStatus['published'] ?? 0 }}, {{ $eventsByStatus['pending'] ?? 0 }}, {{ $eventsByStatus['draft'] ?? 0 }}, {{ $eventsByStatus['cancelled'] ?? 0 }}],
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
