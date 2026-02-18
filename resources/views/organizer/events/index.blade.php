@extends('layouts.app')

@section('title', 'Mis eventos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Mis eventos</h1>
    <a href="{{ route('organizer.events.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Nuevo evento</a>
</div>

@if(session('success'))
    <p class="text-green-600 mb-4">{{ session('success') }}</p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Evento</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Categoría</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Fecha</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Estado</th>
                <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 font-medium">{{ $event->title }}</td>
                    <td class="px-4 py-3">{{ $event->category->name }}</td>
                    <td class="px-4 py-3">{{ $event->start_date->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if($event->status === 'published') bg-green-100 text-green-800
                            @elseif($event->status === 'pending_approval') bg-amber-100 text-amber-800
                            @elseif($event->status === 'draft') bg-slate-100 text-slate-600
                            @else bg-red-100 text-red-800 @endif">{{ $event->status === 'pending_approval' ? 'Pend. aprobación' : $event->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('organizer.events.edit', $event) }}" class="text-indigo-600 hover:underline text-sm">Editar</a>
                        @if($event->status !== 'cancelled')
                            <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" class="inline ml-2" onsubmit="return confirm('¿Cancelar este evento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Cancelar</button>
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
