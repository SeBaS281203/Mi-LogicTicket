@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-3xl font-bold mb-6">Panel de administración</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Usuarios</p>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['users'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Organizadores</p>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['organizers'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Eventos activos (próximos)</p>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['events_active'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Pendientes de aprobación</p>
        <p class="text-2xl font-bold text-amber-600">{{ $stats['events_pending'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Ventas (órdenes pagadas)</p>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['orders'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <p class="text-slate-500 text-sm">Ingresos totales</p>
        <p class="text-2xl font-bold text-emerald-600">S/ {{ number_format($stats['revenue'], 2) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Ingresos por mes (últimos 12 meses)</h2>
    <div class="h-64">
        <canvas id="revenueChart" width="400" height="200"></canvas>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <h2 class="text-lg font-semibold p-4 border-b border-slate-200">Últimas órdenes</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-700">Orden</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-700">Cliente</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-700">Total</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-700">Estado</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-700">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ $order->customer_email }}</td>
                        <td class="px-4 py-3 font-medium">S/ {{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">{{ $order->status }}</span></td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($revenueByMonthLabels),
            datasets: [{
                label: 'Ingresos (S/)',
                data: @json($revenueByMonthData),
                backgroundColor: 'rgba(79, 70, 229, 0.5)',
                borderColor: 'rgb(79, 70, 229)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
@endsection
