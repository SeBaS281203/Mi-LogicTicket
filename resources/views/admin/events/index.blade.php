@extends('layouts.admin')

@section('title', 'Eventos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Eventos</h1>
</div>

<form method="GET" class="mb-4 flex gap-2 flex-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por título o ciudad" class="rounded-lg border-slate-300 shadow-sm">
    <select name="status" class="rounded-lg border-slate-300 shadow-sm">
        <option value="">Todos</option>
        <option value="draft" @selected(request('status') === 'draft')>Borrador</option>
        <option value="pending_approval" @selected(request('status') === 'pending_approval')>Pendiente aprobación</option>
        <option value="published" @selected(request('status') === 'published')>Publicado</option>
        <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelado</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Filtrar</button>
</form>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Evento</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Organizador</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Ciudad</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Fecha</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Estado</th>
                <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.events.show', $event) }}" class="font-medium text-indigo-600 hover:underline">{{ $event->title }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $event->user?->name ?? $event->user?->email }}</td>
                    <td class="px-4 py-3">{{ $event->city }}</td>
                    <td class="px-4 py-3 text-sm">{{ $event->start_date?->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if($event->status === 'published') bg-green-100 text-green-800
                            @elseif($event->status === 'pending_approval') bg-amber-100 text-amber-800
                            @elseif($event->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-slate-100 text-slate-800 @endif">
                            {{ $event->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.events.show', $event) }}" class="text-indigo-600 hover:underline text-sm">Ver</a>
                        @if($event->status === 'pending_approval')
                            <form method="POST" action="{{ route('admin.events.approve', $event) }}" class="inline ml-1">
                                @csrf
                                <button type="submit" class="text-green-600 hover:underline text-sm">Aprobar</button>
                            </form>
                            <form method="POST" action="{{ route('admin.events.reject', $event) }}" class="inline ml-1" onsubmit="return confirm('¿Rechazar evento?')">
                                @csrf
                                <button type="submit" class="text-red-600 hover:underline text-sm">Rechazar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $events->links() }}</div>
@endsection
