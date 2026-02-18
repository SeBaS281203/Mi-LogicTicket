@extends('layouts.admin')

@section('title', 'Libro de Reclamaciones')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Libro de Reclamaciones</h1>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.libro-reclamaciones.export.excel', request()->query()) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg">Exportar Excel</a>
        <a href="{{ route('admin.libro-reclamaciones.export.pdf', request()->query()) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">Exportar PDF</a>
    </div>
</div>

<form method="GET" action="{{ route('admin.libro-reclamaciones.index') }}" class="bg-white rounded-xl border border-slate-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Estado</label>
        <select name="estado" class="rounded-lg border-slate-200 text-sm">
            <option value="">Todos</option>
            @foreach(\App\Models\LibroReclamacion::ESTADOS as $e)
                <option value="{{ $e }}" @if(request('estado') === $e) selected @endif>{{ ucfirst($e) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Tipo</label>
        <select name="tipo_reclamo" class="rounded-lg border-slate-200 text-sm">
            <option value="">Todos</option>
            <option value="reclamo" @if(request('tipo_reclamo') === 'reclamo') selected @endif>Reclamo</option>
            <option value="queja" @if(request('tipo_reclamo') === 'queja') selected @endif>Queja</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Desde</label>
        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="rounded-lg border-slate-200 text-sm">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Hasta</label>
        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="rounded-lg border-slate-200 text-sm">
    </div>
    <button type="submit" class="px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white text-sm font-medium rounded-lg">Filtrar</button>
</form>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Código</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Consumidor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($reclamos as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-mono font-medium">{{ $r->codigo_reclamo }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $r->nombre_completo }}<br><span class="text-slate-500 text-xs">{{ $r->email }}</span></td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs {{ $r->tipo_reclamo === 'reclamo' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($r->tipo_reclamo) }}</span></td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs {{ $r->estado === 'pendiente' ? 'bg-red-100 text-red-800' : ($r->estado === 'atendido' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">{{ ucfirst($r->estado) }}</span></td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.libro-reclamaciones.show', $r) }}" class="text-indigo-600 hover:underline text-sm font-medium">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No hay registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reclamos->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $reclamos->links() }}</div>
    @endif
</div>
@endsection
