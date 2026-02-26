@extends('layouts.admin')

@section('title', 'Eventos')

@section('content')
<h1 class="text-3xl font-bold text-slate-900 mb-6">Eventos</h1>

<form method="GET" class="mb-6 flex gap-3 flex-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por título o ciudad" class="rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2">
    <select name="status" class="rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2">
        <option value="">Todos</option>
        <option value="draft" @selected(request('status') === 'draft')>Borrador</option>
        <option value="pending_approval" @selected(request('status') === 'pending_approval')>Pendiente aprobación</option>
        <option value="published" @selected(request('status') === 'published')>Publicado</option>
        <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelado</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Filtrar</button>
</form>

@if(session('success'))
    <p class="mb-4 text-emerald-600 font-medium">{{ session('success') }}</p>
@endif

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Evento</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Organizador</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Ciudad</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Fecha</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Estado</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border-t border-slate-100 hover:bg-slate-50/50">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.events.show', $event) }}" class="font-medium text-indigo-600 hover:text-indigo-700">{{ $event->title }}</a>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $event->user?->name ?? $event->user?->email }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $event->city }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $event->start_date?->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-lg
                                @if($event->status === 'published') bg-emerald-100 text-emerald-700
                                @elseif($event->status === 'pending_approval') bg-amber-100 text-amber-700
                                @elseif($event->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-slate-100 text-slate-700 @endif">
                                {{ $event->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.events.show', $event) }}" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">Ver</a>
                            <a href="{{ route('admin.events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm ml-3">Editar</a>
                            @if($event->status === 'pending_approval')
                                <form method="POST" action="{{ route('admin.events.approve', $event) }}" class="inline ml-1">
                                    @csrf
                                    <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">Aprobar</button>
                                </form>
                                <form method="POST" action="{{ route('admin.events.reject', $event) }}" class="inline ml-1" onsubmit="return confirm('¿Rechazar evento?')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Rechazar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $events->withQueryString()->links() }}</div>
@endsection
